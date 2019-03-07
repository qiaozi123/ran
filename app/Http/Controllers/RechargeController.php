<?php

namespace App\Http\Controllers;

use App\Price;
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


}
