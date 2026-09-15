<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['hotel_id', 'name', 'slug', 'description', 'is_super_admin', 'is_active', 'scope_type'];

    protected $casts = [
        'is_super_admin' => 'boolean',
        'is_active'      => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }

    public function hasPermission(string $module, string $action = 'view'): bool
    {
        if ($this->is_super_admin) return true;

        $perm = $this->permissions->firstWhere('module_key', $module);

        if (!$perm) return false;

        return (bool) match ($action) {
            'view'   => $perm->can_view,
            'create' => $perm->can_view && $perm->can_create,
            'edit'   => $perm->can_view && $perm->can_edit,
            'delete' => $perm->can_view && $perm->can_delete,
            default  => false,
        };
    }

    /** Returns permissions as keyed array for frontend sharing */
    public function permissionsArray(): array
    {
        $map = [];
        foreach ($this->permissions as $perm) {
            $map[$perm->module_key] = [
                'view'   => (bool) $perm->can_view,
                'create' => (bool) $perm->can_create,
                'edit'   => (bool) $perm->can_edit,
                'delete' => (bool) $perm->can_delete,
            ];
        }
        return $map;
    }
}
