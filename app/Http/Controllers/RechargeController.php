<?php

namespace App\Http\Controllers;

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
        return view('recharge.buy');
    }
}
