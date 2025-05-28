<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use Qoraiche\Peak\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Qoraiche\Peak\Models\User;

class UpdateUserProfilePhoto extends Controller
{
    public function __invoke(User $user, Request $request)
    {
        Validator::make($request->only('photo'), [
            'photo' => ['required', 'mimes:jpg,jpeg,png'], // max:1024
        ])->validate();

        $user->updateProfilePhoto($request->file('photo'));
    }
}
