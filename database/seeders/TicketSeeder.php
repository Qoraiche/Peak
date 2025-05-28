<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Qoraiche\Peak\Models\Ticket;
use Qoraiche\Peak\Models\User;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a new ticket for a user
        $user = User::first(); // Assuming you have at least one user in the database

        Ticket::create([
            'uuid' => Str::uuid(),
            'subject' => 'Issue with the user login',
            'description' => 'User is unable to log in with their credentials. Please investigate the issue.',
            'user_id' => $user->id,
            'priority' => Ticket::PRIORITY_NORMAL, // Default priority
            'status' => Ticket::STATUS_OPEN, // Default status (open)
        ]);

        // Create another ticket for a user
        Ticket::create([
            'uuid' => Str::uuid(),
            'subject' => 'Payment gateway failure',
            'description' => 'The payment gateway is not responding. Transaction failed during checkout.',
            'user_id' => $user->id,
            'priority' => Ticket::PRIORITY_NORMAL,
            'status' => Ticket::STATUS_OPEN,
        ]);

        // Create a high priority ticket
        Ticket::create([
            'uuid' => Str::uuid(),
            'subject' => 'Critical bug in the checkout process',
            'description' => 'The checkout button is not clickable. This is a critical bug that needs to be addressed immediately.',
            'user_id' => $user->id,
            'priority' => Ticket::PRIORITY_HIGH, // Assuming you have a HIGH priority constant
            'status' => Ticket::STATUS_OPEN,
        ]);

        // Create a closed ticket
        Ticket::create([
            'uuid' => Str::uuid(),
            'subject' => 'Feature request: Dark mode',
            'description' => 'User has requested the ability to switch to dark mode in the settings.',
            'user_id' => $user->id,
            'priority' => Ticket::PRIORITY_LOW, // Assuming you have a LOW priority constant
            'status' => Ticket::STATUS_CLOSED,
            'closed_at' => now(), // Set the ticket as closed
        ]);
    }
}
