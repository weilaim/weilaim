<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @param Status $status
     * @return bool
     * AuthServiceProvider 中设置了「授权策略自动注册」，所以这里不需要做任何处理 StatusPolicy 将会被自动识别。
     *
     * 授权 只有当被删除的微博作者为当前用户，授权才能通过。
     */
    public function destroy(User $user,Status $status){
        return $user->id===$status->user_id;
    }
}
