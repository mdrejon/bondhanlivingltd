<?php

namespace App\Support;

use App\Models\Hotel;
use App\Models\Role;
use App\Models\RolePermission;

/**
 * Seeds a new hotel's three default roles — Hotel Admin, Reception, Staff — as
 * soon as it's created (called from HotelController::store()). Mirrors
 * GovernmentRoleSeeder's shape, but triggered per-hotel-creation rather than a
 * one-time seed. Only ever runs for hotels created after Phase 8C — existing
 * hotels (and their existing, possibly-shared, roles) are untouched.
 */
class HotelDefaultRoleProvisioner
{
    public static function provision(Hotel $hotel): void
    {
        static::makeRole($hotel, 'Hotel Admin', [
            'dashboard'           => ['view'],
            'room-availability'   => ['view'],
            'bookings'            => ['view', 'create', 'edit', 'delete'],
            'room-management'     => ['view', 'create', 'edit', 'delete'],
            'customers'           => ['view', 'edit', 'delete'],
            'inquiries'           => ['view', 'edit', 'delete'],
            'website-management'  => ['view', 'create', 'edit', 'delete'],
            'global-settings'     => ['view', 'create', 'edit', 'delete'],
            'email-smtp-setting'  => ['view', 'edit'],
            'reports'             => ['view'],
            'user-management'     => ['view', 'create', 'edit', 'delete'],
            'hotel-backups'       => ['view', 'create', 'delete'],
        ]);

        static::makeRole($hotel, 'Reception', [
            'dashboard'         => ['view'],
            'room-availability' => ['view'],
            'bookings'          => ['view', 'create', 'edit'],
            'customers'         => ['view', 'edit'],
            'inquiries'         => ['view', 'edit'],
        ]);

        static::makeRole($hotel, 'Staff', [
            'dashboard'         => ['view'],
            'room-availability' => ['view'],
            'bookings'          => ['view'],
            'customers'         => ['view'],
            'inquiries'         => ['view'],
        ]);
    }

    /** @param array<string, list<string>> $modules module_key => granted actions */
    private static function makeRole(Hotel $hotel, string $name, array $modules): Role
    {
        $role = Role::create([
            'hotel_id'       => $hotel->id,
            'name'           => $name,
            'slug'           => \Illuminate\Support\Str::slug($name) . '-' . \Illuminate\Support\Str::random(6),
            'description'    => "Default {$name} role for {$hotel->name}.",
            'is_super_admin' => false,
            'is_active'      => true,
            'scope_type'     => 'hotel',
        ]);

        foreach ($modules as $moduleKey => $actions) {
            RolePermission::create([
                'role_id'    => $role->id,
                'module_key' => $moduleKey,
                'can_view'   => in_array('view', $actions, true),
                'can_create' => in_array('create', $actions, true),
                'can_edit'   => in_array('edit', $actions, true),
                'can_delete' => in_array('delete', $actions, true),
            ]);
        }

        return $role;
    }
}
