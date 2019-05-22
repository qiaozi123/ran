<?php

namespace App\Api\Controllers;

use App\Keyword;
use App\Renwu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RunController extends Controller
{
    public function remote()
    {
        $time = date('Y-m-d');
        $data = Renwu::where('created_at','like','%'.$time.'%')->where(['status'=>0])->first();
        if (empty($data)){
            $today = Keyword::where(['status'=>1])->select('dohost','keyword','click','xzh')->get()->toArray();
            foreach ($today as $key=>$item){
                $today[$key]['created_at'] =  date('Y-m-d H:i:s');
                $today[$key]['updated_at'] =  date('Y-m-d H:i:s');
             }
            DB::table('renwu')->insert($today);
        }
        $renwu = Renwu::where('created_at','like','%'.$time.'%')->where(['status'=>0])->first();
        if ($renwu->click == $renwu->has_click){
            $renwu->status = 1;
            $renwu->save();
        }
        $one = Renwu::where('created_at','like','%'.$time.'%')->where(['status'=>0])->first();
        $one->has_click = $one->has_click +1;
        $one->save();
        return $one;
    }
}
