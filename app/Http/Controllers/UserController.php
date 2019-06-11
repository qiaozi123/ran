<?php

namespace App\Http\Controllers;

use App\CoinMark;
use App\Http\Requests\UserLoginRequest;
use App\Proxy;
use App\User;
use App\UserRole;
use Bican\Roles\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nexmo\Client\Exception\Validation;

class UserController extends Controller
{
    public function index()
    {
        if (!Auth::check()){
            return redirect('/');
        }
        $proxy = Proxy::check();
        return view('index',compact('proxy'));
    }

    public function login()
    {
        if (Auth::check()){
            return redirect('home');
        }
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
            return redirect('/') ->withErrors(['用户名或密码不正确！']);
        }
    }

    public function regist()
    {
        if (Auth::check()){
            return redirect('home');
        }
        return view('regist');
    }

    public function doregist(Request $request)
    {

        $url = $_SERVER['HTTP_HOST'];
        $proxy = Proxy::where(['proxy_host'=>$url])->first();
        if (empty($proxy)){
            $belongto = 0;
        }else{
            $belongto = $proxy->userid;
        }
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
        $user->belongto = $belongto;
        $bool = $user->save();
        if ($bool){
            $roleuser = new UserRole();
            $roleuser->role_id = 3;
            $roleuser->user_id = $user->id;
            $roleuserbool = $roleuser->save();
            if ($roleuserbool){
                Auth::loginUsingId($user->id);
                return redirect('/home');
            }
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

    public function list(Request $request)
    {
        $roleid = $request->input('roleid');
        $username = $request->input('name');


        if (!empty($username)){
            $user = UserRole::where(['users.name'=>$username])
                ->join('users','user_id','users.id')
                ->join('roles','roles.id','role_user.role_id')
                ->select('users.id','users.name','users.qq','users.telphone','users.email','users.coin','users.m_coin','roles.name as rolename')
                ->orderby('users.id','desc')
                ->paginate(15);
            return view('user.index',compact('user','username'));
        }


        if (!empty($roleid)){
            $user = UserRole::where(['role_id'=>$roleid])
                ->join('users','user_id','users.id')
                ->join('roles','roles.id','role_user.role_id')
                ->select('users.id','users.name','users.qq','users.telphone','users.email','users.coin','users.m_coin','roles.name as rolename')
                ->orderby('users.id','desc')
                ->paginate(15);
            return view('user.index',compact('user'));
        }else{
            $user = UserRole::where([])
                ->join('users','user_id','users.id')
                ->join('roles','roles.id','role_user.role_id')
                ->select('users.id','users.name','users.qq','users.telphone','users.email','users.coin','users.m_coin','roles.name as rolename')
                ->orderby('users.id','desc')
                ->paginate(15);
            return view('user.index',compact('user'));
        }
    }


    public function recharge(Request $request)
    {
        $userid = $request->input('userid');
        $user = User::find($userid);
        return view('user.recharge.update',compact('user'));
    }

    public function dorecharge(Request $request)
    {
        $userid = $request->input('userid');
        $coin = $request->input('coin');
        $update_people = $request->input('update_people');
        if (empty($userid)){
            return response()->json(['status'=>500,'msg'=>'userid不能为空']);
        }
        if ($coin == ""){
            return response()->json(['status'=>500,'msg'=>'PC积分不能为空']);
        }

        $user = User::find($userid);
        $user->coin = $user->coin+$coin;
        $bool = $user->save();
        if ($bool){
            $coinmark = new CoinMark();
            $coinmark->coin = $coin;
            $coinmark->update_people = $update_people;
            $coinmark->save();
            return response()->json(['status'=>200,'msg'=>'添加积分成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'添加积分失败']);
        }
    }

    public function hasrecharge(Request $request)
    {
        $user = UserRole::where('users.coin','>',0)
            ->join('users','user_id','users.id')
            ->join('roles','roles.id','role_user.role_id')
            ->select('users.id','users.name','users.qq','users.telphone','users.email','users.coin','users.m_coin','roles.name as rolename')
            ->orderby('users.id','desc')
            ->paginate(15);
        return view('user.recharge.index',compact('user','username'));
    }

}
