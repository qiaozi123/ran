<?php

namespace App\Http\Controllers;

use App\ClickLog;
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

    public function history($id)
    {
        $where = ['keyword_id'=>$id];
        $keyword = ClickLog::where($where)->paginate(15);
        return view('keyword.history',compact('keyword'));
    }

    public function today($time)
    {
        if (empty($time)){
            $time = date('Y-m-d');
        }
        $keyword = ClickLog::where('created_at','like','%'.$time.'%')->paginate(100);
        return view('keyword.time',compact('keyword','time'));
    }

}
