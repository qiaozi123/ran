<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\SearchEngines;
use Bican\Roles\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{

    public function index()
    {
        $permission =  Permission::all();
        return view('permission.index',compact('permission'));
    }

    public function create()
    {
        return view('permission.create');
    }

    public function docreate(Request $request)
    {
        $name =  $request->input('name');
        $slug =  $request->input('slug');
        $description =  $request->input('description');
        $model =  $request->input('model');
        $permission = new Permission();
        $permission->name = $name;
        $permission->slug = $slug;
        $permission->description = $description;
        $permission->model = $model;
        $bool = $permission->save();
        if ($bool){
            return response()->json(['status'=>200,'msg'=>'添加权限成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'添加权限失败']);
        }
    }


}
