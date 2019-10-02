<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');
//用户注册
Route::get('signup','UsersController@create')->name('signup');
//更新用户
Route::get('/users/{user}/edit','UsersController@edit')->name('users.edit');
Route::resource('users', 'UsersController');


//登陆
Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');


//邮箱
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');


/*密码重设*/
//显示重置密码的邮箱发送页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//只创建 store，destroy
Route::resource('statuses','StatusesController',['only' => ['store','destroy']]);

//关注人列表
Route::get('/users/{user}/followings','UsersController@followings')->name('users.followings');
//粉丝列表
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

