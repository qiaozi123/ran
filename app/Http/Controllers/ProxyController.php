<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Proxy;
use App\SearchEngines;
use App\User;
use App\UserRole;
use Bican\Roles\Models\Role;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProxyController extends Controller
{

    public function system($id)
    {
        $userid = $id;
        $proxy =  Proxy::where(['userid'=>$userid])->first();
        return view('proxy.system.index',compact('proxy'));
    }

    public function dosystem(Request $request)
    {
        $userid = $request->input('userid');
        $title = $request->input('title');
        if (empty($userid)){
            return response()->json(['status'=>500,'msg'=>'代理id不能为空']);
        }
        if (empty($title)){
            return response()->json(['status'=>500,'msg'=>'代理title不能为空']);
        }
        $proxy =  Proxy::where(['userid'=>$userid])->first();
        $proxy->title = $title;
        $bool =  $proxy->save();
        if ($bool){
            return response()->json(['status'=>200,'msg'=>'代理的网站标题已更新']);
        }else{
            return response()->json(['status'=>500,'msg'=>'代理的网站标题更新失败']);
        }
    }

    public function recharge($id)
    {
        $proxy = Proxy::where(['userid'=>$id])->first();
        return view('proxy.recharge.index',compact('proxy'));
    }

    public function dorecharge(Request $request)
    {
        $userid = $request->input('userid');
        $recharge = $request->input('recharge');
        if (empty($userid)){
            return response()->json(['status'=>500,'msg'=>'代理id不能为空']);
        }
        if (empty($recharge)){
            return response()->json(['status'=>500,'msg'=>'代币比例不能为空']);
        }
        $proxy =  Proxy::where(['userid'=>$userid])->first();
        $proxy->recharge = $recharge;
        $bool =  $proxy->save();
        if ($bool){
            return response()->json(['status'=>200,'msg'=>'代币比例已更新']);
        }else{
            return response()->json(['status'=>500,'msg'=>'代币比例标题更新失败']);
        }
    }

    public function user($userid)
    {
        $user = UserRole::where(['belongto'=>$userid])
            ->join('users','user_id','users.id')
            ->join('roles','roles.id','role_user.role_id')
            ->select('users.id','users.name','users.qq','users.telphone','users.email','users.coin','users.m_coin','users.mark','roles.name as rolename')
            ->orderby('users.id','desc')
            ->paginate(15);
        return view('proxy.user.index',compact('user'));
    }


    public function usermarkupdate(Request $request) //代理角色添加角色的备注
    {
        $userid = $request->input('userid');
        $mark = $request->input('mark');
        $user = User::find($userid);
        $user->mark = $mark;
        $bool = $user->save();
        if ($bool){
            return response()->json(['status'=>200,'msg'=>$user->mark]);
        }else{
            return response()->json(['status'=>500,'msg'=>'备注插入失败']);
        }
    }

}
