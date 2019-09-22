<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}
