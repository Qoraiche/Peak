<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use DateTimeZone;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Qoraiche\Peak\Actions\Dashboard\Admin\UserManagement\UpdateUserRoleAction;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Filters\Admin\UserSearchFilter;
use Qoraiche\Peak\Http\Requests\Admin\BulkDeleteRequest;
use Qoraiche\Peak\Http\Rules\ValidCountryCode;
use Qoraiche\Peak\Models\Role;
use Qoraiche\Peak\Models\SubscriptionPlan;
use Qoraiche\Peak\Models\SubscriptionPlanPricing;
use Qoraiche\Peak\Models\User;
use Qoraiche\Peak\Services\Admin\UserService;
use Qoraiche\Peak\Services\Billing\PlanService;
use Qoraiche\Peak\Services\User\ReferralService;
use Qoraiche\Peak\Traits\HandlesPermissions;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserManagementController extends Controller
{
    use PasswordValidationRules,
        HandlesPermissions;

    /**
     * @param ReferralService $referralService
     * @param UserService $userService
     * @param PlanService $planService
     */
    public function __construct(private ReferralService $referralService,
                                private UserService     $userService,
                                private PlanService     $planService)
    {
//        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeAction('view', null, 'users');

        $limitPagination = (int)$request->input('limit', 25);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = 25;
        }

        $users = QueryBuilder::for(Helpers::getUserAuthModelName())->with('roles')->allowedFilters([
            AllowedFilter::custom('search', new UserSearchFilter)
        ])->allowedSorts([
            User::ID_COLUMN_NAME,
            User::NAME_COLUMN_NAME,
            User::CREATED_AT,
            User::EMAIL_COLUMN_NAME
        ])->orderByDesc(User::ID_COLUMN_NAME)->paginate($limitPagination);

        return Inertia::render('Admin/Users/Index', [
            'items' => $users,
            'exportableData' => User::exportableDataColumnNames()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, UpdateUserRoleAction $updateUserRoleAction)
    {
        $this->authorizeAction('create', null, 'users');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => 'alpha_num|nullable',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'address' => ['nullable', 'string', 'max:255'],
            'country' => [new ValidCountryCode, 'nullable'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other', 'prefer-not-to-say'])],
            'timezone' => ['nullable', 'string', 'timezone'],
            'verified_email' => ['boolean'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'roles' => ['array', 'min:1', Rule::exists('roles', Role::NAME_COLUMN_NAME)],
            'mobile_number' => ['nullable', 'phone:mobile']
        ]);

        $userModel = Helpers::getUserAuthModelName();

        /** @var User $user */
        $user = $userModel::create(
            $request->only('name',
                'username',
                'email',
                'address',
                'country',
                'city',
                'password',
                'state',
                'gender',
                'timezone',
                'postal_code',
                'mobile_number'
            )
        );

        $user = $user->fresh();

        if ($request->has('roles')) {
            $user->assignRole($request->get('roles'));
        }

        if ($request->boolean('verified_email')) {
            $user->email_verified_at = now();
        } else {
            $user->email_verified_at = null;
        }

        $user->password = Hash::make($request->input('password'));

        $user->save();

        return to_route('admin.user-management.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeAction('create', null, 'users');

        $roles = Role::query()->pluck('name')->all();
        $timezones = DateTimeZone::listIdentifiers();

        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
            'timezones' => $timezones
        ]);
    }

    /**
     * Display the specified resource.
     */
    /*public function show(User $user)
    {
        $timezones = DateTimeZone::listIdentifiers();

        $roles = Role::pluck('name')->all();
        $selectedRoles = $user->roles->pluck('name')->all();

        return Inertia::render('Admin/Users/Create', [
            'user' => $user,
            'notes' => $user->userNotes()->with('poster')->get(),
            'selectedRoles' => $selectedRoles,
            'roles' => $roles,
            'timezones' => $timezones
        ]);
    }*/

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        $user = Helpers::getUserAuthModelInstance()->findOrFail($user);

        $this->authorizeAction('edit', $user, 'users');

        $timezones = DateTimeZone::listIdentifiers();
        $roles = Role::pluck('name')->all();
        $selectedRoles = $user->roles->pluck('name')->all();
//        $subscription = $this->userService->userSubscription($user);
//        $collectionMethod = $this->userService->getSubscriptionCollectionMethod($user);

        /*$frequentedPlans = SubscriptionPlanPricing::whereHas('subscriptionPlan', function ($query) {
            $query->where('status', SubscriptionPlan::ACTIVE_STATUS);
        })->get();*/

        $activities = Activity::latest()->take(10)->get()->map(function ($activity) {
            return [
                'id' => $activity->id,
                'user' => $activity->causer->name ?? __('System'),
                'description' => $activity->description,
                'created_at' => $activity->created_at->diffForHumans(),
                'icon' => match ($activity->event) {
                    'registered' => 'UserRoundPlus',
                    'subscribed' => 'PackageCheck',
                    default => null
                }
            ];
        });

        $referrals = $this->referralService->getUserReferrals($user);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'activities' => $activities,
//            'plans' => $frequentedPlans,
//            'collectionMethod' => $collectionMethod,
//            'seatName' => null,
//            'plan' => $subscription['plan'],
//            'subscription' => $subscription['subscription'],
            'notes' => $user->userNotes()->with('poster')->get(),
            'selectedRoles' => $selectedRoles,
            'roles' => $roles,
            'timezones' => $timezones
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user, UpdateUserRoleAction $updateUserRoleAction)
    {
        $user = Helpers::getUserAuthModelInstance()->findOrFail($user);

        $this->authorizeAction('edit', $user, 'users');

        $this->validateProfileForm($request->all(), $user);

        // update roles
        if ($request->has('roles')) {
            $updateUserRoleAction->execute($user, $request->get('roles'));
        }

        $user->update($request->only('name', 'email', 'username', 'address', 'country', 'city', 'state', 'postal_code', 'mobile_number', 'gender', 'timezone'));

        if (!$request->boolean('has_enabled_two_factor_authentication') && $user->hasEnabledTwoFactorAuthentication()) {
            // Reset 2FA fields
            $user->forceFill([
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
                'two_factor_recovery_codes' => null,
            ])->save();
        }

        if ($request->boolean('verified_email')) {
            $user->email_verified_at = now();
        } else {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    /**
     * @param array $payload
     * @param $user
     * @return void
     */
    protected function validateProfileForm(array $payload, $user)
    {
        Validator::make($payload, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'username' => 'alpha_num|nullable',
            'address' => ['nullable', 'string', 'max:255'],
            'country' => [new ValidCountryCode, 'nullable'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other', 'prefer-not-to-say'])],
            'timezone' => ['nullable', 'string', 'timezone'],
            'has_enabled_two_factor_authentication' => ['boolean'],
            'verified_email' => ['boolean'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'roles' => ['nullable', Rule::exists('roles', Role::NAME_COLUMN_NAME)],
            'mobile_number' => ['nullable', 'phone:mobile'],
        ], [
            'mobile_number.phone' => __('Invalid phone number'),
        ])->validate();
    }

    /**
     * Destroy user
     *
     * @param User $user
     * @return void
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorizeAction('delete', $user, 'users');

        app(DeletesUsers::class)->delete($user);
    }

    /**
     * Bulk destroy user
     *
     * @param Request $request
     * @return void
     */
    public function bulkDestroy(BulkDeleteRequest $bulkDeleteRequest)
    {
        $this->authorizeAction('delete', null, 'users');

        User::query()->whereIn('id', $bulkDeleteRequest->ids)
            ->chunkById(50, function ($pages) {
                User::whereIn('id', $pages->pluck('id'))
                    ->delete();
            });
    }
}
