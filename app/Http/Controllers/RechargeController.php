<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Price;
use App\User;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('recharge.index');
    }

    public function buy()
    {
        $price = Price::all();
        return view('recharge.buy',compact('price'));
    }

    // 每天每词扣费记录
    public function charging()
    {
        $user = User::all();
        foreach ($user as $item){
            $keyword = Keyword::where(['status'=>1])->get();
        }

    }


}
