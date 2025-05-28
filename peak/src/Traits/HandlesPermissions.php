<?php

namespace Qoraiche\Peak\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HandlesPermissions
{
    /**
     * Authorize an action against a given model and permission pattern.
     *
     * @param string $action (e.g., 'edit', 'delete', 'view')
     * @param Model|null $model
     * @param string|null $resource (optional resource name, e.g., 'posts')
     * @throws HttpResponseException|\Exception
     */
    protected function authorizeAction(string $action, Model $model = null, ?string $resource = null): void
    {
        $user = auth()->user();

        // Determine resource slug if not provided
        if (!$resource) {
            if ($model) {
                $resource = Str::kebab(Str::pluralStudly(class_basename($model))); // e.g. "posts"
            } else {
                throw new \Exception('Resource must be provided if no model is given');
            }
        }

        $permission = "$action-$resource"; // e.g. "edit-posts"

        // Admin bypasses all permission and ownership checks
        if ($this->isAdmin($user)) {
            return;
        }

        // Check if user has the needed permission
        if (!$user || !$user->can($permission)) {
            abort(403, "Unauthorized: Missing permission [$permission]");
        }

        // Ownership check if model supports ownedBy()
        if ($model && method_exists($model, 'ownedBy')) {
            if (!$model->ownedBy($user)) {
                abort(403, "Unauthorized: You do not own this $resource item.");
            }
        }
    }

    /**
     * Check if user has any admin role.
     *
     * @param $user
     * @return bool
     */
    protected function isAdmin($user): bool
    {
        if (!$user) return false;

        $adminRoles = config('peak.default_roles.admin', []);

        return $user->hasAnyRole($adminRoles);
    }
}