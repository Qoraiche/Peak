<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Filters\Admin\TeamSearchFilter;
use Qoraiche\Peak\Http\Filters\Admin\UserSearchFilter;
use Qoraiche\Peak\Http\Requests\Admin\BulkDeleteRequest;
use Qoraiche\Peak\Models\Role;
use Qoraiche\Peak\Models\User;
use Qoraiche\Peak\Services\Admin\RolePermissionService;
use Qoraiche\Peak\Traits\HandlesPermissions;
use Spatie\Permission\Models\Permission;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TeamManagementController extends Controller
{
    use HandlesPermissions;

    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $this->authorizeAction('view', null, 'teams');

        $limitPagination = (int)$request->input('limit', 25);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = 25;
        }

        $teams = QueryBuilder::for(Team::class)
            ->with('owner')
            ->withCount('users')
            ->allowedFilters([
                AllowedFilter::custom('search', new TeamSearchFilter)
            ])->allowedSorts([
                'name'
            ])->orderByDesc('id')
            ->paginate($limitPagination);

        return Inertia::render('Admin/Users/Teams/Index', [
            'items' => $teams,
            'exportableData' => Team::exportableDataColumnNames(),
        ]);
    }

    /**
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Teams/Create');
    }

    /**
     * @param Team $team
     * @return Response
     */
    public function edit(Team $team)
    {
        return Inertia::render('Admin/Users/Teams/Edit', [
            'team' => $team->load('owner', 'users', 'teamInvitations'),
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
                'canUpdateTeamMembers' => Gate::check('updateTeamMember', $team),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAction('create', null, 'teams');

        /** @var User $user */
        $user = User::query()->findOrFail($request->owner);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'exists:users,id']
        ]);

        AddingTeam::dispatch($user);

        $team = $user->ownedTeams()->create([
            'name' => $request->name,
            'personal_team' => false,
        ]);

        return redirect()->route('admin.user-management.teams.edit', $team->id);

        //todo: redirect to team

        /*$user->switchTeam($team = $user->ownedTeams()->create([
            'name' => $request->name,
            'personal_team' => false,
        ]));*/
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required',
            'owner' => ['required', 'exists:users,id']
        ]);

        $team->update([
            'name' => $request->name,
            'user_id' => $request->owner,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $this->authorizeAction('delete', $team, 'teams');

        $team->purge();

        return redirect()->route('admin.user-management.teams.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function bulkDestroy(BulkDeleteRequest $bulkDeleteRequest)
    {
        $this->authorizeAction('delete', null, 'teams');

        Team::query()->whereIn('id', $bulkDeleteRequest->ids)
            ->chunkById(50, function ($pages) {
                Team::query()->whereIn('id', $pages->pluck('id'))
                    ->purge();
            });
    }
}
