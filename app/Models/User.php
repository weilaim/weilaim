<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 生成令牌 需要监听 creating方法 created用于监听模型被创建之后的事件
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';


    /**
     * [gravatar 获取头像]
     * @Author   By_Wlaim
     * @DateTime 2019-09-22T15:05:01+0800
     * @param    string $size [default = 100]
     * strtolower      方法将邮箱转换为小写
     * trim         方法剔除邮箱的前后空白内容
     * $this->attributes['email'] 获取到用户的邮箱；
     * @return   [type]               [description]
     */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 指明用户有多条微博
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function feed()
    {
        return $this->statuses()
            ->orderBy('created_at', 'desc');
    }

    //关注的人和粉丝
    public function followers()
    {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    /**
     * 借助这两个方法可以让我们非常简单的实现用户的「关注」和「取消关注」的相关逻辑，具体在用户模型中定义关注
     * （follow）和取消关注（unfollow）的方法如下：
     *
     * is_array 用于判断参数是否为数组，如果已经是数组，则没有必要再使用 compact 方法
     */

    //关注
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    //取消关注
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    /**
     * 我们还需要一个方法用于判断当前登录的用户 A 是否关注了用户 B，代码实现逻辑很简单，
     * 我们只需判断用户 B 是否包含在用户 A 的关注人列表上即可。这里我们将用到 contains 方法来做判断。
     */
    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }

}
