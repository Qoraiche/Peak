<?php

namespace Qoraiche\Peak\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;
use Qoraiche\Peak\Services\Widgets\BaseWidget;

class WidgetManager
{
    protected array $widgets = [];
    protected array $hooks = [];

    /**
     * Register multiple widgets at once.
     */
    public function registerMany(array $widgets): self
    {
        foreach ($widgets as $widget) {
            if (!isset($widget['slug'], $widget['title'], $widget['component'], $widget['group'])) {
                throw new InvalidArgumentException("Each widget must have 'slug', 'title', 'icon', 'component', and 'group'.");
            }

            $this->register(
                $widget['slug'],
                $widget['title'],
                $widget['component'],
                $widget['icon'] ?? null,
                $widget['group'],
                $widget['order'] ?? 0,
                $widget['lazy'] ?? true,
                $widget['role'] ?? null,
                $widget['permission'] ?? null
            );
        }

        return $this;
    }

    /**
     * @param string $slug
     * @param string $title
     * @param string|null $icon
     * @param string $component
     * @param string $group
     * @param int $order
     * @param bool $lazy
     * @param string|null $role
     * @param string|null $permission
     * @return $this
     */
    public function register(
        string  $slug,
        string  $title,
        string  $component,
        ?string $icon = null,
        string  $group = 'default',
        int     $order = 0,
        bool    $lazy = true,
        ?string $role = null,
        ?string $permission = null
    ): self
    {
        if (!is_subclass_of($component, BaseWidget::class)) {
            throw new InvalidArgumentException("$component must extend BaseWidget.");
        }

        $this->widgets[$slug] = compact('slug', 'title', 'group', 'lazy', 'icon', 'component', 'order', 'role', 'permission');

        return $this;
    }

    /**
     * Unregister a widget.
     */
    public function unregister(string $slug): self
    {
        unset($this->widgets[$slug]);
        return $this;
    }

    /**
     * Add a hook to modify widgets dynamically before registration.
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
     * Get all widgets sorted by order.
     */
    public function all(string $group = 'default', $user = null): array
    {
        foreach ($this->hooks as $hook) {
            $hook($this);
        }

        // Transform widgets to return only necessary data
        return collect($this->widgets)
            ->where('group', $group)
            ->filter(function ($widget) {
                $user = auth()->user(); // Get the current authenticated user
                return $this->filterWidgetByPermissions($widget, $user);
            })
            ->sortBy('order')
            ->map(fn($widget) => [
                'slug' => $widget['slug'],
                'title' => $widget['title'],
                'icon' => $widget['icon'],
                'group' => $widget['group'],
                'component' => $this->instance($widget['slug'])?->getComponent(),
                'order' => $widget['order'],
                'lazy' => $widget['lazy'],
                'data' => $this->instance($widget['slug'])?->data(),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Filters widgets based on the user's role and permissions.
     */
    protected function filterWidgetByPermissions(array $widget, $user): bool
    {
        if (!$user) {
            return false; // No user means no access
        }

        // Check if the widget has a role restriction
        if (isset($widget['role']) && !$user->hasRole($widget['role'])) {
            return false;
        }

        // Check if the widget has a permission restriction
        if (isset($widget['permission']) && !$user->can($widget['permission'])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $slug
     * @return mixed|null
     * @throws BindingResolutionException
     */
    public function instance(string $slug)
    {
        $widgetConfig = $this->get($slug);

        if ($widgetConfig) {
            $widgetClass = $widgetConfig['component'];
            return app()->make($widgetClass); // Call data() method from the widget class
        }

        return null;
    }

    /**
     * @param string $slug
     * @return array|null
     */
    public function get(string $slug): ?array
    {
        return $this->widgets[$slug] ?? null;
    }

    public function data(string $slug): ?array
    {
        return $this->instance($slug)?->data();
    }
}

