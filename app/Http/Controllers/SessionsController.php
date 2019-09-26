<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    /**
     * SessionsController constructor.
     * 只让未登录用户访问登录页面：
     */
    public function __construct(){
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('sessions.create');
    }


    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     *用 email 字段的值在数据库中查找；
     *果用户被找到：
     * 先将传参的 password 值进行哈希加密，然后与数据库中 password 字段中已加密的密码进行匹配；
     * 如果匹配后两个值完全一致，会创建一个『会话』给通过认证的用户。会话在创建的同时，也会种下一个名为
     *laravel_session 的 HTTP Cookie，以此 Cookie 来记录用户登录状态，最终返回 true；
     *3). 如果匹配后两个值不一致，则返回 false；
     *如果用户未找到，则返回 false。
     *结合 attempt 方法对用户身份进行认证的具体代码实现如下，使用 Auth 前需要对其进行引用（注意文件顶部引入 use Auth;）：
     *
     */
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:4'
        ]);

        //dd($credentials);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            session()->flash('success', '欢迎回来！');
            $fallback = route('users.show', Auth::user());
            return redirect()->intended($fallback);
        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(){
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }


}
