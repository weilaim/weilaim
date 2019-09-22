<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    //

    public function create(){
        return view('users.create');
    }


    /**
     * [show description]
     * @Author   By_Wlaim
     * @DateTime 2019-09-22T14:23:31+0800
     * @return   [type]                   [description]
     *
     * Laravel 会自动解析定义在控制器方法（变量名匹配路由片段）中的 Eloquent 模型类型声明。在上面代码中，由于 show() 方法传参时声明了类型 —— Eloquent 模型 User，对应的变量名 $user 会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。
     */
    public function show(User $user){
        return view('users.show', compact('user'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|confirmed|min:4'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',[$user]);
    }
}
