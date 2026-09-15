<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\User;
use App\Support\CurrentHotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Phase 8C — a non-super-admin (hotel-scoped, with user-management permission)
 * only ever sees/creates/edits/deletes users belonging to their OWN hotel, and
 * can only assign roles that are also their own hotel's (never a shared/platform
 * role, never another hotel's role) — mirrors RoleController's scoping.
 */
class UserController extends Controller
{
    public function index(): Response
    {
        $query = User::with(['role', 'hotel'])->orderByDesc('id');

        if (!auth()->user()->isSuperAdmin()) {
            $query->where('hotel_id', CurrentHotel::homeId());
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => $query->get(),
        ]);
    }

    public function create(): Response
    {
        $user = auth()->user();

        return Inertia::render('Admin/Users/Create', [
            'roles'        => $this->assignableRoles($user),
            'hotels'       => [],
            'isSuperAdmin' => $user->isSuperAdmin(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'password'  => ['required', 'confirmed', Password::min(8)],
            'role_id'   => 'nullable|exists:roles,id',
            'hotel_id'  => 'nullable|exists:hotels,id',
            'is_active' => 'boolean',
        ]);

        $this->authorizeRoleAssignment($user, $data['role_id'] ?? null);

        $hotelId = $user->isSuperAdmin()
            ? ($data['hotel_id'] ?? 0)
            : CurrentHotel::homeId();

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role_id'   => $data['role_id'] ?? null,
            'hotel_id'  => $hotelId,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): Response
    {
        $actor = auth()->user();
        $this->authorizeOwnership($actor, $user);

        return Inertia::render('Admin/Users/Edit', [
            'user'         => $user->load('role'),
            'roles'        => $this->assignableRoles($actor),
            'hotels'       => [],
            'isSuperAdmin' => $actor->isSuperAdmin(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $actor = auth()->user();
        $this->authorizeOwnership($actor, $user);

        $data = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => ['nullable', 'confirmed', Password::min(8)],
            'role_id'   => 'nullable|exists:roles,id',
            'hotel_id'  => 'nullable|exists:hotels,id',
            'is_active' => 'boolean',
        ]);

        $this->authorizeRoleAssignment($actor, $data['role_id'] ?? null);

        $hotelId = $actor->isSuperAdmin()
            ? ($data['hotel_id'] ?? 0)
            : CurrentHotel::homeId();

        $update = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role_id'   => $data['role_id'] ?? null,
            'hotel_id'  => $hotelId,
            'is_active' => $data['is_active'] ?? true,
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $user->update($update);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeOwnership(auth()->user(), $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $this->authorizeOwnership(auth()->user(), $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'User status updated.');
    }

    /** 404s unless the acting user is a Super Admin or $target belongs to their own hotel. */
    private function authorizeOwnership(User $actor, User $target): void
    {
        abort_unless($actor->isSuperAdmin() || $target->hotel_id === CurrentHotel::homeId(), 404);
    }

    /**
     * A non-super-admin may only assign a role that is also their own hotel's —
     * never a shared/platform role (Super Admin, government roles, or any
     * pre-8C hotel role) and never another hotel's role. Re-validated here
     * server-side regardless of what assignableRoles() offered client-side.
     *
     * Critically, a null role_id must ALSO be rejected for a non-super-admin:
     * User::isSuperAdmin() treats "no role at all" as Super Admin, so leaving
     * role_id blank is itself a privilege-escalation path, not just "no
     * restriction" — a hotel admin must always assign a real, owned role.
     */
    private function authorizeRoleAssignment(User $actor, ?int $roleId): void
    {
        if ($actor->isSuperAdmin()) {
            return;
        }

        abort_unless($roleId, 403, 'You must assign a role — accounts you create cannot be left unrestricted.');

        $ownsRole = Role::where('id', $roleId)->where('hotel_id', CurrentHotel::homeId())->exists();
        abort_unless($ownsRole, 403, 'You can only assign roles that belong to your own hotel.');
    }

    private function assignableRoles(User $actor)
    {
        $query = Role::where('is_active', true);

        if (!$actor->isSuperAdmin()) {
            $query->where('hotel_id', CurrentHotel::homeId());
        }

        return $query->get(['id', 'name', 'is_super_admin']);
    }
}
