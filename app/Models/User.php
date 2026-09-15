<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'hotel_id',
        'district_id',
        'upazila_id',
        'police_station_id',
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The hotel this user works for. Null for platform/government accounts not tied
     * to one hotel. Not global-scoped like the other tenant models — see
     * App\Models\Concerns\BelongsToHotel's docblock for why.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /** Jurisdiction fields below are set for government-role users matching their
     *  role's scope_type — see App\Support\HotelAccess. */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function policeStation(): BelongsTo
    {
        return $this->belongsTo(PoliceStation::class);
    }

    /** True if the user has no role assigned or their role is super admin */
    public function isSuperAdmin(): bool
    {
        return is_null($this->role_id) || ($this->role && $this->role->is_super_admin);
    }

    /**
     * Only police-scoped accounts (and super admin) may view/set a guest's
     * is_flagged/flagged_note — PRD § 5.4's "suspicious person" flag. Checked here,
     * not just hidden client-side, so the field never even reaches an unauthorized
     * role's response payload.
     */
    public function canManagePoliceFlag(): bool
    {
        return $this->isSuperAdmin() || $this->role?->scope_type === 'police_station';
    }

    public function hasPermission(string $module, string $action = 'view'): bool
    {
        if ($this->isSuperAdmin()) return true;

        if (!$this->role) return false;

        // Eager-load permissions if not already loaded
        if (!$this->role->relationLoaded('permissions')) {
            $this->role->load('permissions');
        }

        return $this->role->hasPermission($module, $action);
    }

    /** Returns permissions keyed array for Inertia shared props; null = super admin */
    public function sharedPermissions(): ?array
    {
        if ($this->isSuperAdmin()) return null;

        if (!$this->role) return [];

        if (!$this->role->relationLoaded('permissions')) {
            $this->role->load('permissions');
        }

        return $this->role->permissionsArray();
    }

    /**
     * The module/action ceiling this user may grant when creating or editing a
     * role — see App\Http\Controllers\Admin\RoleController. A super admin can
     * grant every action every module defines; anyone else can only ever grant
     * what they themselves currently hold, so a hotel admin can't hand out
     * capabilities (backups, hotel-registration, gov-reports, ...) they don't
     * have. Same shape as Role::permissionsArray().
     */
    public function grantablePermissions(): array
    {
        if ($this->isSuperAdmin()) {
            $all = [];
            foreach (\App\Support\ModuleRegistry::all() as $module) {
                $all[$module['key']] = [
                    'view'   => in_array('view', $module['actions'], true),
                    'create' => in_array('create', $module['actions'], true),
                    'edit'   => in_array('edit', $module['actions'], true),
                    'delete' => in_array('delete', $module['actions'], true),
                ];
            }

            return $all;
        }

        return $this->sharedPermissions() ?? [];
    }
}
