<?php

namespace App\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
class UserPolicy
{
    use HandlesAuthorization;

    /**       动作
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUser, User $user){
        return $currentUser->id === $user->id;
    }

    /**
     * @param User $currentUser
     * @param User $user
     * @return bool
     * destroy 动作
     *  删除用户的动作，有两个逻辑需要提前考虑：
     *  只有当前登录用户为管理员才能执行删除操作；
     *  删除的用户对象不是自己（即使是管理员也不能自己删自己）。
     */
    public function destroy(User $currentUser, User $user){
        //我们使用了下面这行代码来指明，只有当前用户拥有管理员权限且删除的用户不是自己时才显示链接。
        //$currentUser->is_admin && $currentUser->id !== $user->id;
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }

    //在开发一个功能前，需仔细考虑下此功能的授权策略。在我们的场景中，什么人不能
    //关注用户？是的，自己不能关注自己。故新增授权策略方法取名 follow()：
    public function follow(User $currentUser, User $user)
    {
        return $currentUser->id !== $user->id;
    }

}
