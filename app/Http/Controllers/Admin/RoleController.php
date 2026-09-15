<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use App\Support\CurrentHotel;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Phase 8C — delegated role management. A Super Admin's behavior here is
 * unchanged from before this phase: sees/edits every role, can grant anything,
 * can create shared/platform roles (hotel_id stays null).
 *
 * A non-super-admin (a hotel-scoped account with user-management permission) is
 * newly, strictly scoped: sees only roles with hotel_id === their own hotel,
 * can never see/touch a shared role or another hotel's role, can never set
 * is_super_admin, and can only ever grant a module/action they themselves
 * currently hold — enforced server-side in store()/update() via
 * User::hasPermission(), never trusting the submitted permissions[] alone.
 */
class RoleController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $query = Role::withCount('users')->orderByDesc('is_super_admin')->orderBy('name');

        if (!$user->isSuperAdmin()) {
            $query->where('hotel_id', CurrentHotel::homeId());
        }

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $query->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Roles/Create', [
            'modules'             => $this->grantableModules(),
            'canGrantSuperAdmin'  => auth()->user()->isSuperAdmin(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'           => 'required|string',
            'description'    => 'nullable|string',
            'is_super_admin' => 'boolean',
            'is_active'      => 'boolean',
            'permissions'    => 'nullable|array',
            'permissions.*.key'        => 'required|string',
            'permissions.*.can_view'   => 'boolean',
            'permissions.*.can_create' => 'boolean',
            'permissions.*.can_edit'   => 'boolean',
            'permissions.*.can_delete' => 'boolean',
        ]);

        $role = Role::create([
            'hotel_id'       => $user->isSuperAdmin() ? null : CurrentHotel::homeId(),
            'name'           => $data['name'],
            'slug'           => Str::slug($data['name']) . '-' . Str::random(6),
            'description'    => $data['description'] ?? null,
            'is_super_admin' => $user->isSuperAdmin() ? ($data['is_super_admin'] ?? false) : false,
            'is_active'      => $data['is_active'] ?? true,
        ]);

        $this->syncPermissions($role, $data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): Response
    {
        $this->authorizeOwnership($role);

        $role->load('permissions');

        $existingPerms = $role->permissions->keyBy('module_key');
        $ceiling       = $this->grantableModules();

        $modules = array_map(function ($module) use ($existingPerms) {
            $existing = $existingPerms->get($module['key']);
            return array_merge($module, [
                'can_view'   => (bool) ($existing->can_view   ?? false),
                'can_create' => (bool) ($existing->can_create ?? false),
                'can_edit'   => (bool) ($existing->can_edit   ?? false),
                'can_delete' => (bool) ($existing->can_delete ?? false),
            ]);
        }, $ceiling);

        return Inertia::render('Admin/Roles/Edit', [
            'role'               => $role,
            'modules'            => $modules,
            'canGrantSuperAdmin' => auth()->user()->isSuperAdmin(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $user = auth()->user();
        $this->authorizeOwnership($role);

        $data = $request->validate([
            'name'           => 'required|string',
            'description'    => 'nullable|string',
            'is_super_admin' => 'boolean',
            'is_active'      => 'boolean',
            'permissions'    => 'nullable|array',
            'permissions.*.key'        => 'required|string',
            'permissions.*.can_view'   => 'boolean',
            'permissions.*.can_create' => 'boolean',
            'permissions.*.can_edit'   => 'boolean',
            'permissions.*.can_delete' => 'boolean',
        ]);

        $role->update([
            'name'           => $data['name'],
            'slug'           => Str::slug($data['name']) . '-' . Str::random(6),
            'description'    => $data['description'] ?? null,
            'is_super_admin' => $user->isSuperAdmin() ? ($data['is_super_admin'] ?? false) : false,
            'is_active'      => $data['is_active'] ?? true,
        ]);

        $this->syncPermissions($role, $data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorizeOwnership($role);

        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete a role that has users assigned. Reassign users first.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted.');
    }

    /** 403s unless the acting user is a Super Admin or this role is their own hotel's. */
    private function authorizeOwnership(Role $role): void
    {
        $user = auth()->user();
        abort_unless($user->isSuperAdmin() || $role->hotel_id === CurrentHotel::homeId(), 404);
    }

    /**
     * ModuleRegistry::all(), filtered to only modules the acting user has at
     * least one grantable action on — a Super Admin gets everything unfiltered.
     * The per-module 'actions' list is also narrowed to the acting user's own
     * ceiling, so the UI never renders a checkbox for an action they can't grant.
     */
    private function grantableModules(): array
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return ModuleRegistry::all();
        }

        $ceiling = $user->grantablePermissions();

        $filtered = [];
        foreach (ModuleRegistry::all() as $module) {
            $grantableActions = array_values(array_filter(
                $module['actions'],
                fn ($action) => (bool) ($ceiling[$module['key']][$action] ?? false)
            ));

            if ($grantableActions) {
                $filtered[] = array_merge($module, ['actions' => $grantableActions]);
            }
        }

        return $filtered;
    }

    /**
     * Writes only the (module, action) tuples the acting user's own permission
     * ceiling actually allows — anything outside it is silently dropped, never
     * partially honored. This is the real enforcement point; grantableModules()
     * above is UI convenience only, never trusted alone.
     */
    private function syncPermissions(Role $role, array $permissions): void
    {
        $user = auth()->user();

        foreach ($permissions as $perm) {
            if (empty($perm['key'])) continue;

            $requested = [
                'can_view'   => (bool) ($perm['can_view']   ?? false),
                'can_create' => (bool) ($perm['can_create'] ?? false),
                'can_edit'   => (bool) ($perm['can_edit']   ?? false),
                'can_delete' => (bool) ($perm['can_delete'] ?? false),
            ];

            if (!$user->isSuperAdmin()) {
                $requested = [
                    'can_view'   => $requested['can_view']   && $user->hasPermission($perm['key'], 'view'),
                    'can_create' => $requested['can_create'] && $user->hasPermission($perm['key'], 'create'),
                    'can_edit'   => $requested['can_edit']   && $user->hasPermission($perm['key'], 'edit'),
                    'can_delete' => $requested['can_delete'] && $user->hasPermission($perm['key'], 'delete'),
                ];
            }

            RolePermission::updateOrCreate(
                ['role_id' => $role->id, 'module_key' => $perm['key']],
                $requested
            );
        }
    }
}
