<?php

namespace Qoraiche\Peak\Services;

use InvalidArgumentException;
use Qoraiche\Peak\Services\Cards\BaseCard;

class CardManager
{
    protected array $cards = [];
    protected array $hooks = [];

    /**
     * Register multiple cards at once.
     */
    public function registerMany(array $cards): self
    {
        foreach ($cards as $card) {
            if (!isset($card['slug'], $card['title'], $card['component'])) {
                throw new InvalidArgumentException("Each card must have 'slug', 'title', and 'component'.");
            }

            $this->register(
                $card['slug'],
                $card['title'],
                $card['component'],
                $card['description'] ?? null,
                $card['collapsible'] ?? true,
                $card['viewMoreRouteName'] ?? null,
                $card['order'] ?? 0,
                $card['lazy'] ?? true,
                $card['roles'] ?? [], // Add roles
                $card['permissions'] ?? [] // Add permissions
            );
        }

        return $this;
    }

    /**
     * Register a new card.
     */
    public function register(
        string $slug,
        string $title,
        string $component,
        string $description = null,
        bool   $collapsible = false,
        string $viewMoreRouteName = null,
        int    $order = 0,
        bool   $lazy = true,
        array  $roles = [], // Add roles the card requires
        array  $permissions = [] // Add permissions the card requires
    ): self
    {
        if (!is_subclass_of($component, BaseCard::class)) {
            throw new InvalidArgumentException("$component must extend BaseCard.");
        }

        $this->cards[$slug] = compact(
            'slug', 'title', 'description', 'lazy', 'collapsible', 'viewMoreRouteName', 'component', 'order', 'roles', 'permissions'
        );

        return $this;
    }

    /**
     * Unregister a card.
     */
    public function unregister(string $slug): self
    {
        unset($this->cards[$slug]);
        return $this;
    }

    /**
     * Add a hook to modify cards dynamically before registration.
     */
    public function hook(callable $callback): self
    {
        $this->hooks[] = $callback;
        return $this;
    }

    /**
     * Get all registered hooks.
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }

    /**
     * Get all registered cards sorted by order and filtered by user permissions.
     */
    public function all(): array
    {
        foreach ($this->hooks as $hook) {
            $hook($this);
        }

        // Pass the current authenticated user to filter the cards by their permissions
        $filteredCards = $this->filterCardsByUserPermissions(auth()->user());

        return collect($filteredCards)
            ->sortBy('order')
            ->map(fn($card) => [
                'slug' => $card['slug'],
                'title' => $card['title'],
                'description' => $card['description'],
                'lazy' => $card['lazy'],
                'collapsible' => $card['collapsible'],
                'viewMoreRouteName' => $card['viewMoreRouteName'],
                'component' => $this->instance($card['slug'])?->getComponent(),
                'order' => $card['order'],
                'data' => $this->instance($card['slug'])?->data(),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Filter out cards that the user doesn't have permission to access.
     * This assumes you have a user object available to check roles and permissions.
     */
    protected function filterCardsByUserPermissions($user): array
    {
        return collect($this->cards)->filter(function ($card) use ($user) {
            if (!$user) {
                return false; // No user means no access
            }

            // Check if the user has any of the roles required for the card
            $hasRole = empty($card['roles']) || $user->hasRole($card['roles']);

            // Check if the user has any of the permissions required for the card
            $hasPermission = empty($card['permissions']) || $user->can($card['permissions']);

            // If the user has both the required roles and permissions, keep the card
            return $hasRole && $hasPermission;
        })->toArray();
    }

    /**
     * Get an instance of a card.
     */
    public function instance(string $slug)
    {
        $cardConfig = $this->get($slug);

        if ($cardConfig) {
            $cardClass = $cardConfig['component'];

            return app()->make($cardClass); // Instantiate the card class
        }

        return null;
    }

    /**
     * Get a registered card.
     */
    public function get(string $slug): ?array
    {
        return $this->cards[$slug] ?? null;
    }

    /**
     * Get card data.
     */
    public function data(string $slug): ?array
    {
        return $this->instance($slug)?->data();
    }
}