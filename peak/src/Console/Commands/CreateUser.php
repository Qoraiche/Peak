<?php

namespace Qoraiche\Peak\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class CreateUser extends Command
{
    /** @var string */
    protected $signature = 'peak:create-user
                            {--name= : The user\'s name}
                            {--email= : The user\'s email}
                            {--username= : The user\'s username}
                            {--password= : The user\'s password}
                            {--roles= : Comma-separated roles to assign to the user}';

    /** @var string */
    protected $description = 'Create a new user with optional multiple role assignment';

    /**
     * @return int
     */
    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('Enter the user\'s name');
        $email = $this->option('email') ?? $this->ask('Enter the user\'s email');
        $username = $this->option('username') ?? $this->ask('Enter the user\'s username');
        $password = $this->option('password') ?? $this->secret('Enter the user\'s password');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line($error);
            }
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $availableRoles = Role::pluck('name')->toArray();

        // Handle roles (CLI or prompt)
        $selectedRoles = $this->option('roles');

        if ($selectedRoles) {
            $selectedRoles = array_map('trim', explode(',', $selectedRoles));
            $invalidRoles = array_diff($selectedRoles, $availableRoles);

            if (!empty($invalidRoles)) {
                $this->error('Invalid roles provided: ' . implode(', ', $invalidRoles));
                return 1;
            }
        } else {
            $selectedRoles = $this->choice(
                'Select one or more roles for the user (comma-separated)',
                $availableRoles,
                multiple: true
            );
        }

        $user->syncRoles([]); // Optional: wipe any roles
        $user->assignRole($selectedRoles);

        $this->info("User created successfully with roles: " . implode(', ', $selectedRoles));
        return 0;
    }
}
