<?php

namespace Qoraiche\Peak\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Qoraiche\Peak\Http\Controllers\Controller;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Dashboard/Account/API/Index', [
            'tokens' => $request->user()->tokens->map(function ($token) {
                return $token->toArray() + [
                        'last_used_ago' => optional($token->last_used_at)->diffForHumans(),
                    ];
            }),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
        ]);
    }

    /**
     * Create a new API token.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $token = $request->user()->createToken(
            $request->name,
            Jetstream::validPermissions($request->input('permissions', []))
        );

        return back()->with('flash', [
            'token' => explode('|', $token->plainTextToken, 2)[1],
        ]);
    }

    /**
     * Update the given API token's permissions.
     *
     * @param Request $request
     * @param string $tokenId
     * @return RedirectResponse
     */
    public function update(Request $request, $tokenId)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => Jetstream::validPermissions($request->input('permissions', [])),
        ])->save();

        return back(303);
    }

    /**
     * Delete the given API token.
     *
     * @param Request $request
     * @param string $tokenId
     * @return RedirectResponse
     */
    public function destroy(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->first()->delete();

        return back(303);
    }
}
