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
    public static function boot(){
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
     * @param    string      $size [default = 100]
     * strtolower      方法将邮箱转换为小写
     * trim         方法剔除邮箱的前后空白内容
     * $this->attributes['email'] 获取到用户的邮箱；
     * @return   [type]               [description]
     */
    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 指明用户有多条微博
     */
    public function statuses(){
        return $this->hasMany(Status::class);
    }
}
