<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use Qoraiche\Peak\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Qoraiche\Peak\Models\User;

class DeleteUserProfilePhoto extends Controller
{
    public function __invoke(User $user)
    {
        $user->deleteProfilePhoto();

        return back(303)->with('status', 'profile-photo-deleted');
    }
}
