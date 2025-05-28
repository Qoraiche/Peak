<?php

namespace Qoraiche\Peak\Services;

class MenuManager
{
    /** @var array */
    protected array $menus = [];

    /** @var array */
    protected array $hooks = [];

    /** @var array */
    protected array $registeredSlugs = [];

    /**
     * Register a menu with items
     *
     * @param string $slug
     * @param array $items
     * @return void
     */
    public function register(string $slug, array $items)
    {
        $slug = $this->sanitize_key($slug);

        $items = $this->applyHooks('before_register', $slug, $items);

        $this->menus[$slug] = $this->assignSlugs($items);

        $this->menus[$slug] = $this->applyHooks('after_register', $slug, $this->menus[$slug]);
    }

    /**
     * Add a child item to a specific menu and parent item
     *
     * @param string $menuSlug
     * @param string $parentSlug
     * @param array $child
     * @return void
     */
    public function addChildToMenu(string $menuSlug, string $parentSlug, array $child)
    {
        if (!isset($this->menus[$menuSlug])) {
            return;
        }

        $this->menus[$menuSlug] = $this->addChild($this->menus[$menuSlug], $parentSlug, $child);
    }

    /**
     * Get the entire menu with applied permissions and roles
     *
     * @param string $slug
     * @return array
     */
    public function get(string $slug)
    {
        if (!isset($this->menus[$slug])) {
            return [];
        }

        $menu = $this->menus[$slug];

        // Filter items by permissions
        $menu = $this->filterMenuByPermissions($menu);

        // Apply hooks
        $menu = $this->applyHooks('before_fetch', $slug, $menu);

        $currentRoute = request()->route()?->getName();
        $menu = $this->markActiveItems($menu, $currentRoute);

        // Sort menu items by "order" if available
        $menu = $this->sortMenu($menu);

        return $this->applyHooks('after_fetch', $slug, $menu);
    }

    /**
     * Return all menus
     *
     * @return array
     */
    public function all(): array
    {
        return $this->menus;
    }

    /**
     * Add a hook to a specific menu and hook type
     *
     * @param string $hookType
     * @param string $slug
     * @param callable $callback
     * @return void
     */
    public function addHook(string $hookType, string $slug, callable $callback)
    {
        $this->hooks[$hookType][$slug][] = $callback;
    }

    /**
     * Apply hooks to modify the menu
     *
     * @param string $hookType
     * @param string $slug
     * @param array $menu
     * @return array
     */
    protected function applyHooks(string $hookType, string $slug, array $menu)
    {
        if (!isset($this->hooks[$hookType][$slug])) {
            return $menu;
        }

        foreach ($this->hooks[$hookType][$slug] as $callback) {
            $menu = $callback($slug, $menu);
        }

        return $menu;
    }

    /**
     * Assign unique slugs to menu items
     *
     * @param array $items
     * @param string $parentSlug
     * @return array
     */
    protected function assignSlugs(array $items, string $parentSlug = ''): array
    {
        foreach ($items as &$item) {
            $slug = !empty($item['slug']) ? $this->sanitize_key($item['slug']) : $this->generateUniqueSlug($parentSlug ? $parentSlug . '-' . $this->sanitize_key($item['title']) : $this->sanitize_key($item['title']));

            $item['slug'] = $slug;

            if (!empty($item['children'])) {
                $item['children'] = $this->assignSlugs($item['children'], $slug);
            }
        }

        return $items;
    }

    /**
     * Add a child item to a menu based on the parent slug
     *
     * @param array $items
     * @param string $parentSlug
     * @param array $child
     * @return array
     */
    protected function addChild(array $items, string $parentSlug, array $child)
    {
        foreach ($items as &$item) {
            if ($item['slug'] === $parentSlug) {
                $child = $this->assignSlugs([$child], $parentSlug)[0];
                $item['children'][] = $child;
                return $items;
            }

            if (!empty($item['children'])) {
                $item['children'] = $this->addChild($item['children'], $parentSlug, $child);
            }
        }

        return $items;
    }

    /**
     * Mark active items based on the current route
     *
     * @param array $items
     * @param string|null $currentRoute
     * @return array
     */
    protected function markActiveItems(array $items, ?string $currentRoute = null)
    {
        foreach ($items as &$item) {
            $isActive = isset($item['route']) && $currentRoute && (
                    request()->routeIs($item['route']) ||
                    (isset($item['children']) && strpos($currentRoute, $item['route']) === 0)
                );

            $item['active'] = $isActive;

            if (isset($item['children']) && $currentRoute) {
                $item['children'] = $this->markActiveItems($item['children'], $currentRoute);
                $item['active'] = $item['active'] || $this->hasActiveChild($item['children']);
            }
        }

        return $items;
    }

    /**
     * Check if any child items are active
     *
     * @param array $children
     * @return bool
     */
    protected function hasActiveChild(array $children)
    {
        foreach ($children as $child) {
            if ($child['active'] || (isset($child['children']) && $this->hasActiveChild($child['children']))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Sanitize a key (slug) to ensure it is valid
     *
     * @param string $key
     * @return string
     */
    protected function sanitize_key(string $key): string
    {
        return preg_replace('/[^a-z0-9_-]/', '', strtolower(str_replace(' ', '-', $key)));
    }

    /**
     * Generate a unique slug based on a base slug
     *
     * @param string $baseSlug
     * @return string
     */
    protected function generateUniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug;
        $count = 1;

        while (in_array($slug, $this->registeredSlugs)) {
            $slug = $baseSlug . '-' . $count++;
        }

        $this->registeredSlugs[] = $slug;

        return $slug;
    }

    /**
     * Unregister a specific item from a menu
     *
     * @param string $menuSlug
     * @param string $itemSlug
     * @return void
     */
    public function unregister(string $menuSlug, string $itemSlug)
    {
        if (!isset($this->menus[$menuSlug])) {
            return;
        }

        $this->menus[$menuSlug] = $this->removeItem($this->menus[$menuSlug], $itemSlug);
    }

    /**
     * Remove an item by its slug from a menu
     *
     * @param array $items
     * @param string $itemSlug
     * @return array
     */
    protected function removeItem(array $items, string $itemSlug): array
    {
        foreach ($items as $key => &$item) {
            if ($item['slug'] === $itemSlug) {
                unset($items[$key]);
                continue;
            }

            if (!empty($item['children'])) {
                $item['children'] = $this->removeItem($item['children'], $itemSlug);

                if (empty($item['children'])) {
                    unset($item['children']);
                }
            }
        }

        return array_values($items);
    }

    /**
     * Sort the menu items based on order
     *
     * @param array $items
     * @return array
     */
    protected function sortMenu(array $items): array
    {
        usort($items, function ($a, $b) {
            return ($a['order'] ?? PHP_INT_MAX) <=> ($b['order'] ?? PHP_INT_MAX);
        });

        foreach ($items as &$item) {
            if (!empty($item['children'])) {
                $item['children'] = $this->sortMenu($item['children']);
            }
        }

        return $items;
    }

    /**
     * Filter the menu based on user permissions and roles
     *
     * @param array $items
     * @return array
     */
    protected function filterMenuByPermissions(array $items)
    {
        $user = auth()->user(); // Get the current authenticated user
        $filteredItems = [];

        foreach ($items as $item) {
            // Check if the user has the required role
            if (isset($item['role']) && !$user?->hasRole($item['role'])) {
                continue;
            }

            // Check if the user has the required permissions
            if (isset($item['permission']) && !$user?->can($item['permission'])) {
                continue;
            }

            // Recursively check children items for their permissions
            if (!empty($item['children'])) {
                $item['children'] = $this->filterMenuByPermissions($item['children']);

                // If no child is allowed, hide the parent too
                if (empty($item['children'])) {
                    continue;
                }
            }

            // Add the item to the filtered list if it passes the checks
            $filteredItems[] = $item;
        }

        return $filteredItems;
    }
}
