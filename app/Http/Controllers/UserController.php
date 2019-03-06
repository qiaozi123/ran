<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nexmo\Client\Exception\Validation;

class UserController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function dologin(Request $request)
    {
        $rules=[
            'username'=>'required',
            'password'=>'required',
            'captcha' => 'required|captcha'
        ];
        $message=[
            'username.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
            'captcha.required'=>'验证不能为空',
            'captcha.captcha' => '验证码错误，请重试'
        ];
        $validator=$this->validate($request,$rules,$message);
        $captcha = $request->input('captcha');
        $username = $request->input('username');
        $password = $request->input('password');

        $loginbool = Auth::attempt(['name'=>$username,'password'=>$password]);
        if ($loginbool){
            return redirect('/home');
        }else{
            return redirect('/')->with('errors','用户名或密码不正确');
        }
    }

    public function regist()
    {
        return view('regist');
    }

    public function doregist(Request $request)
    {
        $username = $request->input('name');
        $telphone = $request->input('telphone');
        $password = $request->input('password');
        $repassword = $request->input('repassword');
        $qq = $request->input('qq');
        $email = $request->input('email');
        $captcha = $request->input('captcha');
        $rules=[
            'name'=>'required|unique:users',
            'password'=>'required',
            'telphone'=>'required|numeric|unique:users',
            'repassword'=>'required',
            'qq'=>'required|numeric',
            'email'=>'required|email|unique:users',
            'captcha' => 'required|captcha'
        ];
        $message=[
            'name.required'=>'用户名不能为空',
            'name.unique'=>'用户名已存在',
            'email.unique'=>'邮箱已经已存在',
            'password.required'=>'密码不能为空',
            'telphone.required'=>'手机号不能为空',
            'telphone.unique'=>'手机号已经存在',
            'qq.required'=>'手机号不能为空',
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
            'captcha.required'=>'验证不能为空',
            'captcha.captcha' => '验证码错误，请重试'
        ];
        $validator=$this->validate($request,$rules,$message);
        $user = new User();
        $user->name = $username;
        $user->telphone = $telphone;
        $user->password = Hash::make($password);
        $user->qq = $qq;
        $user->email = $email;
        $bool = $user->save();
        if ($bool){
            Auth::loginUsingId($user->id);
            return redirect('/home');
        }else{
            return redirect('/regist')->with('errors','注册失败.请联系客服');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function info()
    {
        $user =  Auth::user();
        return view('user.info',compact('user'));
    }

    public function update()
    {
        return view('user.update');
    }

    public function updatepassword(Request $request)
    {
        $password = $request->input('password');
        $id = $request->input('userid');
        if (empty($id)){
            return '用户id不能为空';
        }
        if (empty($password)){
            return '密码不能为空';
        }
        $user =  User::find($id);
        $user->password = Hash::make($password);
        $bool = $user->save();
        if ($bool){
            return 200;
        }else{
            return 500;
        }
    }
}
