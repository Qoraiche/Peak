<?php

namespace Qoraiche\Peak\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Qoraiche\Peak\Models\Permission;
use Qoraiche\Peak\Models\Role;

class CreateRole extends Command
{
    protected $signature = 'peak:create-role';

    public function handle()
    {
        $name = $this->ask('Enter the name of the new role');

        // Validate role name
        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line($error);
            }
            return 1;
        }

        // Create the role with 'web' guard
        $role = Role::create([
            Role::NAME_COLUMN_NAME => $name,
            Role::GUARD_NAME_COLUMN_NAME => 'web',
        ]);

        // Ask if user wants to assign permissions
        if ($this->confirm('Do you want to assign permissions to this role?', true)) {
            $this->assignPermissions($role);
        }

        $this->info("Role '{$name}' created successfully.");
        return 0;
    }

    /**
     * @param Role $role
     * @return void
     */
    protected function assignPermissions(Role $role)
    {
        // Get all permissions
        $permissions = Permission::all();

        if ($permissions->isEmpty()) {
            $this->warn('No permissions available.');
            return;
        }

        // Prepare options as name => id, so that we can use name for display, but get id for selection
        $permissionChoices = $permissions->pluck('name', 'id')->toArray();

        // Allow multiple selection and return permission IDs
        $selectedPermissionIds = $this->choice(
            'Select permissions to assign to the role (multiple selection allowed, separated by comma)',
            $permissionChoices,
            null,
            null,
            true
        );

        // Convert selected names back to IDs (since we get names from choice)
        $selectedPermissions = Permission::whereIn('name', $selectedPermissionIds)->get();

        // Assign permissions to the role
        $role->syncPermissions($selectedPermissions);

        // Debugging: Output the permissions assigned to the role
        $this->info('Permissions assigned to the role:');
        foreach ($role->permissions as $permission) {
            $this->line("Permission: {$permission->name}");
        }

        $this->info('Permissions have been successfully assigned to the role.');
    }
}