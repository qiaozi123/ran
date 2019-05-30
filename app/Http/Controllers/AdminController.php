<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Msg;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Key;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function show()
    {
        $userid = Auth::user()->id;
        $pwhere = ['userid'=>$userid,'status'=>0];
        $weiyouhua =  Keyword::where($pwhere)->get()->count();
        $mwhere = ['userid'=>$userid,'status'=>1];
        $youhuazhong =  Keyword::where($mwhere)->get()->count();
        $mwhere = ['userid'=>$userid,'status'=>2];
        $zhantingzhong =  Keyword::where($mwhere)->get()->count();
        $msg = Msg::all();
        $user = User::find($userid);
        return view('welcome',compact('weiyouhua','youhuazhong','zhantingzhong','msg','user'));
    }
}
