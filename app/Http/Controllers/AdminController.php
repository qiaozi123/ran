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
        $pwhere = ['userid'=>$userid,'type'=>1];
        $pcount =  Keyword::where($pwhere)->get()->count();
        $mwhere = ['userid'=>$userid,'type'=>2];
        $mcount =  Keyword::where($mwhere)->get()->count();
        $msg = Msg::all();
        $user = User::find($userid);
        return view('welcome',compact('pcount','mcount','msg','user'));
    }
}
