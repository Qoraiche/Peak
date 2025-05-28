<?php

namespace Qoraiche\Peak\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Qoraiche\Peak\Traits\HasExportableData;
use Qoraiche\Peak\Traits\HasFormattedDates;
use Spatie\Permission\Models\Permission;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory,
        HasExportableData,
        HasFormattedDates;

    const NAME_COLUMN_NAME = 'name';
    const GUARD_NAME_COLUMN_NAME = 'guard_name';
    const ID_COLUMN_NAME = 'id';

    /** @var string[] */
    protected $appends = [
        'total_role_users_count',
        'permissions_ids',
        'permission_names',
        'role_permissions_based_on_all'
    ];

    public function getExportableColumns(): array
    {
        return [
            'id' => fn($role) => $role->id,
            'name' => fn($role) => $role->name,
            'permission_names' => fn(Role $role) => $role->getPermissionNames(),
            'total_role_users_count' => fn($role) => $role->total_role_users_count
        ];
    }

    /**
     * @return string|null
     */
    public function getPermissionNamesAttribute(): ?string
    {
        return implode(', ', $this->getPermissionNames()->all());
    }

    /**
     * @return array
     */
    public function getRolePermissionsBasedOnAllAttribute(): array
    {
        $allPermissions = Permission::all()->pluck('name')->all();
        $roleExistingPermissions = $this->permissions->pluck('name')->all();

        return collect($allPermissions)->map(function ($permission) use ($roleExistingPermissions) {
            return [
                $permission => in_array($permission, $roleExistingPermissions, true)
            ];
        })->collapse()->all();
    }

    /**
     * Total users using a custom role
     *
     * @return int
     */
    public function usersCount(): int
    {
        return $this->users()->count();
    }

    /**
     * @return array|null
     */
    public function getPermissionsIdsAttribute(): ?array
    {
        return $this->permissions->pluck('id')->all();
    }

    /**
     * @return array|null
     */
    public function getPermissionsNamesAttribute(): ?array
    {
        return $this->permissions->pluck('name')->all();
    }

    /**
     * @return int
     */
    public function getTotalRoleUsersCountAttribute(): int
    {
        return $this->usersCount();
    }
}
