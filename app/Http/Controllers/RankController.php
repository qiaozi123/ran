<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\SearchEngines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except('pcdocreate','movedocreate');
    }

    public function create(){
        return view('rank.create');
    }

    public function pc(){
        $userid = Auth::user()->id;
        $where = ['userid'=>$userid,'type'=>1];
        $keyword = Keyword::where($where)->paginate(15);
        $count =  Keyword::where($where)->get()->count();
        return view('rank.PC.index',compact('keyword','count'));
    }

    public function pccreate()
    {
        $searchengines =  SearchEngines::all();
        return view('rank.PC.create',compact('searchengines'));
    }

    public function pcdocreate(Request $request)
    {
        $keywords = $request->input('keyword');
        $dohost = $request->input('dohost');
        $rank =  $request->input('rank');
        $searchengines =  $request->input('searchengines');
        $userid =  $request->input('userid');

        if (empty($keywords)){
            return '关键词不能为空';
        }
        if (empty($dohost)){
            return '网址不能为空';
        }
        if (empty($rank)){
            return '排名不能为空';
        }
        if (empty($searchengines)){
            return '搜索引擎不能为空';
        }
        if (empty($userid)){
            return 'userid不能为空';
        }
        $type =  1;
        $keyword = new Keyword();
        $keyword->keyword = $keywords;
        $keyword->rank = $rank;
        $keyword->dohost = $dohost;
        $keyword->searchengines = $searchengines;
        $keyword->type = $type;
        $keyword->userid = $userid;
        $bool =  $keyword->save();
        if ($bool){
            return 200;
        }else{
            return 500;
        }
    }



    public function move(){
        $userid = Auth::user()->id;
        $where = ['userid'=>$userid,'type'=>2];
        $keyword = Keyword::where($where)->paginate(15);
        $count =  Keyword::where($where)->get()->count();
        return view('rank.move.index',compact('keyword','count'));
    }

    public function movecreate(){
        $searchengines =  SearchEngines::all();
        return view('rank.move.create',compact('searchengines'));
    }

    public function movedocreate(Request $request)
    {
        $keywords = $request->input('keyword');
        $dohost = $request->input('dohost');
//        $rank =  $request->input('rank');
        $searchengines =  $request->input('searchengines');
        $userid =  $request->input('userid');

        if (empty($keywords)){
            return '关键词不能为空';
        }
        if (empty($dohost)){
            return '网址不能为空';
        }
//        if (empty($rank)){
//            return '排名不能为空';
//        }
        if (empty($searchengines)){
            return '搜索引擎不能为空';
        }
        if (empty($userid)){
            return 'userid不能为空';
        }
        $type =  2;
        $keyword = new Keyword();
        $keyword->keyword = $keywords;
//        $keyword->rank = $rank;
        $keyword->dohost = $dohost;
        $keyword->searchengines = $searchengines;
        $keyword->type = $type;
        $keyword->userid = $userid;
        $bool =  $keyword->save();
        if ($bool){
            return 200;
        }else{
            return 500;
        }
    }

}
