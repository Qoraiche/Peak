<?php

namespace Qoraiche\Peak\Services;

class SearchResourceManager
{
    /** @var array */
    protected array $searchResources = [];

    /** @var array */
    protected array $hooks = [];

    /**
     * @param string $slug
     * @param string $title
     * @param string $resourceRoute
     * @param string|null $description
     * @param string $searchParam
     * @param string|null $icon
     * @param int $order
     * @param array $roles
     * @param array $permissions
     * @return $this
     */
    public function register(
        string $slug,
        string $title,
        string $resourceRoute,
        string $colorClass = 'bg-blue-500',
        string $searchParam = 'search',
        string $icon = null,
        int    $order = 0,
        array  $roles = [],
        array  $permissions = []
    ): self
    {
        $this->searchResources[$slug] = compact(
            'slug', 'title', 'resourceRoute', 'colorClass', 'searchParam', 'order', 'roles', 'icon', 'permissions'
        );

        return $this;
    }

    /**
     * Unregister a searchResource.
     */
    public function unregister(string $slug): self
    {
        unset($this->searchResources[$slug]);
        return $this;
    }

    /**
     * Get a registered searchResource.
     */
    public function get(string $slug): ?array
    {
        return $this->searchResources[$slug] ?? null;
    }

    /**
     * Filter out searchResources that the user doesn't have permission to access.
     * This assumes you have a user object available to check roles and permissions.
     */
    protected function filterSearchResourcesByUserPermissions($user): array
    {
        return collect($this->searchResources)
            ->filter(function ($searchResource) use ($user) {
                if (!$user) {
                    return false; // No user means no access
                }

                // Check if the user has any of the roles required for the searchResource
                $hasRole = empty($searchResource['roles']) || $user->hasRole($searchResource['roles']);

                // Check if the user has any of the permissions required for the searchResource
                $hasPermission = empty($searchResource['permissions']) || $user->can($searchResource['permissions']);

                // If the user has both the required roles and permissions, keep the searchResource
                return $hasRole && $hasPermission;
            })
            ->toArray();
    }

    /**
     * Add a hook to modify searchResources dynamically before registration.
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
     * Get all registered searchResources sorted by order and filtered by user permissions.
     */
    public function all(): array
    {
        foreach ($this->hooks as $hook) {
            $hook($this);
        }

        // Pass the current authenticated user to filter the searchResources by their permissions
        $filteredSearchResources = $this->filterSearchResourcesByUserPermissions(auth()->user());

        return collect($filteredSearchResources)
            ->sortBy('order')
            ->map(fn($searchResource) => [
                'slug' => $searchResource['slug'],
                'title' => $searchResource['title'],
                'colorClass' => $searchResource['colorClass'],
                'icon' => $searchResource['icon'],
                'resourceRoute' => $searchResource['resourceRoute'],
                'searchParam' => $searchResource['searchParam'],
                'order' => $searchResource['order']
            ])
            ->values()
            ->toArray();
    }
}

