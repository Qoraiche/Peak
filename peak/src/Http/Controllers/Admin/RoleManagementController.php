<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Requests\Admin\BulkDeleteRequest;
use Qoraiche\Peak\Models\Role;
use Qoraiche\Peak\Services\Admin\RolePermissionService;
use Qoraiche\Peak\Traits\HandlesPermissions;
use Spatie\Permission\Models\Permission;

class RoleManagementController extends Controller
{
    use HandlesPermissions;

    public function __construct(private RolePermissionService $rolePermissionService)
    {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $this->authorizeAction('view', null, 'roles');

        $roles = $this->rolePermissionService->getSearchablePaginatedRoles($request, 25);
        $permissions = $this->rolePermissionService->getGroupedPermissions();

        // get all roles in the database
        return Inertia::render('Admin/Users/Roles/Index', [
            'items' => $roles,
            'permissions' => $permissions,
            'exportableData' => Role::exportableDataColumnNames(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAction('create', null, 'roles');

        Validator::make($request->all(), [
            'roleName' => 'required',
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'required',
                Rule::exists('permissions', 'id')
            ],
        ])->validate();

        $role = Role::create([
            Role::GUARD_NAME_COLUMN_NAME => 'web',
            Role::NAME_COLUMN_NAME => $request->get('roleName')
        ]);

        $permissionsName = Permission::query()->whereIn('id', $request->get('permissions'))->pluck('name')->all();

        $role->givePermissionTo($permissionsName);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->authorizeAction('edit', $role, 'roles');

        Validator::make($request->all(), [
            'roleName' => 'required',
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'required',
                Rule::exists('permissions', 'id')
            ],
        ])->validate();

        $role->update(['name' => $request->get('roleName')]);

        $permissionsName = Permission::whereIn('id', $request->get('permissions'))->pluck('name')->all();

        $role->syncPermissions($permissionsName);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorizeAction('delete', $role, 'roles');

        $role->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function bulkDestroy(BulkDeleteRequest $bulkDeleteRequest)
    {
        $this->authorizeAction('delete', null, 'roles');

        Role::whereIn('id', $bulkDeleteRequest->ids)
            ->chunkById(50, function ($pages) {
                Role::whereIn('id', $pages->pluck('id'))
                    ->delete();
            });
    }
}
