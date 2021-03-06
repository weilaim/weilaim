<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mail;

class UsersController extends Controller
{
    //

    public function __construct(){
        //白名单
        $this->middleware('auth',[
            'except'=>['create','show','store','index','confirmEmail']
        ]);

        /**
         * 只让未登录用户访问注册页面：
         */
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }


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
        $statuses = $user->statuses()
            ->orderBy('created_at','desc')
            ->paginate(10);
        return view('users.show', compact('user','statuses'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

       /* //注册后自动登陆
        Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',[$user]);*/
       $this->sendEmailConfirmationTo($user);
       session()->flash('success','验证邮件已发送到你的注册邮箱上请注意查收。');
       return redirect('/');

    }
    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token){
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功');
        return redirect()->route('users.show',[$user]);
    }

    public function edit(User $user){
        return view('users.edit',compact('user'));

    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     * 注册授权策略--$this->authorize 作用（用户只能编辑自己的资料）
     */
    public function update(User $user, Request $request)
    {
        //自动授权
        $this->authorize('update',$user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user);
    }

    public function index(){
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }


    public function destroy(User $user){
        $user->delete();
        session()->flash('success','删除用户成功!');
        return back();
    }

    //用户关注人列表
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }
    //显示粉丝列表
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }










}
