<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class keywordController extends Controller
{

    public function index($id)
    {
        $where = ['userid'=>$id,'status'=>1];
        $keyword = Keyword::where($where)->paginate(15);
        return view('keyword.index',compact('keyword'));
    }

}
