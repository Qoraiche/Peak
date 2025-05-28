<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Requests\Admin\BulkDeleteRequest;
use Qoraiche\Peak\Models\Permission;
use Qoraiche\Peak\Services\Admin\RolePermissionService;
use Qoraiche\Peak\Traits\HandlesPermissions;

class PermissionManagementController extends Controller
{
    use HandlesPermissions;

    /**
     * @param RolePermissionService $rolePermissionService
     */
    public function __construct(private RolePermissionService $rolePermissionService)
    {
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorizeAction('view', null, 'permissions');

        $permissions = $this->rolePermissionService->getSearchablePaginatedPermissions($request, 25);

        // get all roles in the database
        return Inertia::render('Admin/Users/Permissions/Index', [
            'items' => $permissions,
            'exportableData' => Permission::exportableDataColumnNames(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAction('create', null, 'permissions');

        Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name',
            'group' => 'nullable|string'
        ])->validate();

        Permission::create([
            'name' => $request->get('name'),
            'guard_name' => 'web',
            'group' => $request->get('group')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorizeAction('edit', null, 'permissions');

        Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name',
            'group' => 'nullable|string'
        ])->validate();

        $permission->update([
            'name' => $request->get('name'),
            'group' => $request->get('group')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->authorizeAction('delete', $permission, 'permissions');

        $permission->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function bulkDestroy(BulkDeleteRequest $bulkDeleteRequest)
    {
        $this->authorizeAction('delete', null, 'permissions');

        Permission::whereIn('id', $bulkDeleteRequest->ids)
            ->chunkById(50, function ($pages) {
                Permission::whereIn('id', $pages->pluck('id'))->delete();
            });
    }
}
