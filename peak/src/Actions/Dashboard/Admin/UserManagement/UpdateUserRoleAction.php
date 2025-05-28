<?php

namespace Qoraiche\Peak\Actions\Dashboard\Admin\UserManagement;

use Qoraiche\Peak\Helpers;

class UpdateUserRoleAction
{
    /**
     * @param $user
     * @param array $roles
     * @return void
     */
    public function execute($user, array $roles = []): void
    {
        $userAuthModel = Helpers::getUserAuthModelName();
        $user = $userAuthModel::find($user->id);
        
        $user->syncRoles($roles);
    }
}
