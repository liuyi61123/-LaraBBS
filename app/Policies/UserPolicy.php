<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * 在用户修改的时候验证
     */
    public function update(User $currentUser, User $user){
        //第一个参数代表登录的user，第二个代表需要修改的user
        return $currentUser->id === $user->id;
    }

}
