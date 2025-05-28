<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;
use Qoraiche\Peak\Models\Ticket;
use Qoraiche\Peak\Models\TicketComment;
use Laravel\Pulse\Facades\Pulse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->frontGates();

        Pulse::user(fn($user) => [
            'name' => $user->name,
            'extra' => $user->email,
            'avatar' => $user->profile_photo_path,
        ]);
    }

    /**
     * @return void
     */
    private function frontGates()
    {
        Gate::define('view-ticket', function ($user, Ticket $ticket) {
            return $ticket->user_id === $user->id || $user->hasRole(config('peak.default_roles.admin'));
        });

        Gate::define('edit-ticket', function ($user, Ticket $ticket) {
            return $ticket->user_id === $user->id || $user->hasRole(config('peak.default_roles.admin'));
        });

        Gate::define('comment-ticket', function ($user, Ticket $ticket) {
            return $ticket->user_id === $user->id || $user->hasRole(config('peak.default_roles.admin'));
        });

        Gate::define('report-comment', function ($user, TicketComment $comment) {
            return true;
        });

        Gate::define('edit-comment', function ($user, TicketComment $comment) {
            return $comment->user_id === $user->id || $user->hasRole(config('peak.default_roles.admin'));
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // register telescope (local env)
        if (class_exists(\Laravel\Telescope\TelescopeServiceProvider::class) && $this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
