<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use Qoraiche\Peak\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UpdateUserPassword extends Controller
{
    use PasswordValidationRules;

    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user, Request $request)
    {
        Validator::make($request->all(), [
            //'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($request->get('password')),
        ])->save();
    }
}
