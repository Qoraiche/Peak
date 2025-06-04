<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $defaultRoles = config('peak.default_roles', []);
        $userRoleNames = $defaultRoles['user'] ?? [];
        $adminRoleNames = $defaultRoles['admin'] ?? [];

        // Define grouped permissions
        $groupedPermissions = [
            'General' => [
                'access-admin',
                'edit-settings',
                'edit-frontend-settings',
                'clear-cache',
                'view-app-logs',
                'export',
            ],
            'Users' => $this->crudPermissions('users'),
            'Teams' => $this->crudPermissions('teams'),
            'Roles' => $this->crudPermissions('roles'),
            'Users Notes' => $this->crudPermissions('users-notes'),
            'Notifications' => $this->crudPermissions('notifications'),
            'Permissions' => $this->crudPermissions('permissions'),
        ];

        // Optional: merge custom config permissions
        $custom = config('peak.custom_permissions', []);
        foreach ($custom as $group => $permissions) {
            $groupedPermissions[$group] = $permissions;
        }

        // Create grouped permissions
        foreach ($groupedPermissions as $group => $permissions) {
            foreach ($permissions as $name) {
                Permission::updateOrCreate(
                    ['name' => $name],
                    ['group' => $group]
                );
                Log::info("Permission created/updated: $name [$group]");
            }
        }

        // Create user roles
        foreach ($userRoleNames as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Admin role gets all permissions
        foreach ($adminRoleNames as $roleName) {
            $adminRole = Role::firstOrCreate(['name' => $roleName]);

            if ($roleName === 'admin') {
                $adminRole->syncPermissions(Permission::all());
            }
        }
    }

    /**
     * @param string $resource
     * @return string[]
     */
    protected function crudPermissions(string $resource): array
    {
        return [
            "view-{$resource}",
            "create-{$resource}",
            "edit-{$resource}",
            "delete-{$resource}",
        ];
    }
}
