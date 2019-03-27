<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Proxy;
use App\SearchEngines;
use App\User;
use App\UserRole;
use Bican\Roles\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{

    public function index()
    {
        $role =  Role::all();
        return view('role.index',compact('role'));
    }

    public function create()
    {
        return view('role.create');
    }

    public function docreate(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $level = $request->input('level');
        $slug = $request->input('slug');

        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => ''
        ]);
        $user = User::find($id)->attachRole($role);
    }


    public function userupdate(Request $request)
    {
        $userid = $request->input('userid');
        $user = User::find($userid);
        $role = Role::all();
        return view('role.user.update',compact('user','role'));
    }

    public function douserupdate(Request $request)
    {
        $userid = $request->input('userid');
        $roleid = $request->input('roleid');
        $proxy_url = $request->input('proxy_url');
        if (empty($userid)){
            return response()->json(['status'=>500,'msg'=>'userid不能为空']);
        }
        if (empty($roleid)){
            return response()->json(['status'=>500,'msg'=>'roleid不能为空']);
        }
        $userrole = UserRole::where(['user_id'=>$userid])->first();
        $userrole->role_id = $roleid;
        $bool = $userrole->save();
        if (!empty($proxy_url)){
            $proxy = new Proxy();
            $proxy->userid = $userid;
            $proxy->proxy_host = $proxy_url;
            $proxy->save();
        }
        if ($bool){
            return response()->json(['status'=>200,'msg'=>'修改权限成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'修改权限失败']);
        }
    }

}
