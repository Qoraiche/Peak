<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Api;

use Qoraiche\Peak\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Qoraiche\Peak\Services\Admin\UserService;

class SearchUserController extends Controller
{
    /**
     * @param Request $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function __invoke(Request $request, UserService $userService)
    {
        $roles = $request->input('roles', []);
        $search = $request->input('search', '');
        $limit = $request->input('limit', 10);

        $users = $userService->getUsersByRole($search, $roles, $limit, ['name', 'id']);

        return response()->json($users);
    }
}