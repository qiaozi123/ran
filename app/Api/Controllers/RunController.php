<?php

namespace App\Api\Controllers;

use App\Keyword;
use App\Renwu;
use App\User;
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
            $today = Keyword::where(['status'=>1])->select('dohost','keyword','click','xzh','userid','id as keyword_id')->get()->toArray();
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
        }elseif ($renwu->click < $renwu->has_click){
            $renwu->status = 1;
            $renwu->save();
        }else{
        //任务的点击次数+1
        $one = Renwu::where('created_at','like','%'.$time.'%')->where(['status'=>0])->first();
        $one->has_click = $one->has_click +1;
        $one->save();
        //积分-1
        $user = User::find($renwu->userid);
        $user->coin = $user->coin -1;
        $user->save();
        return $one;
        }
    }
}
