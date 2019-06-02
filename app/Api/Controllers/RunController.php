<?php

namespace App\Api\Controllers;

use App\ClickLog;
use App\Keyword;
use App\Rank;
use App\Renwu;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RunController extends Controller
{
    public function remote()
    {
        $data = Keyword::where(['status'=>1])->select('dohost','keyword','click','xzh','userid','id','has_click','today')->first();
        if (empty($data->tody)){
            $data->today = date('Y-m-d');
            $data->save();
        }
        $renwu = Keyword::where(['keywords.status'=>1,'keywords.today'=>date('Y-m-d'),'keywords.today_status'=>0])
            ->where('users.coin','>','0')
            ->join('users','users.id','=','keywords.userid')
            ->select('keywords.dohost','keywords.keyword','keywords.click','keywords.xzh','keywords.userid','keywords.id','keywords.has_click','keywords.today','users.coin')->first();
        if (empty($renwu)){
            return "今日('".date('Y-m-d')."')的任务已经执行完毕";
        }

        if ($renwu->click < $renwu->has_click || $renwu->click == $renwu->has_click ){ //任务已经完成的情况
            $renwu->today_status =  1;
            $renwu->save();  //该任务今天已经完成

            $renwu = Keyword::where(['keywords.status'=>1,'keywords.today'=>date('Y-m-d'),'keywords.today_status'=>0])
                ->where('users.coin','>','0')
                ->join('users','users.id','=','keywords.userid')
                ->select('keywords.dohost','keywords.keyword','keywords.click','keywords.xzh','keywords.userid','keywords.id','keywords.has_click','keywords.today','users.coin')->first();
            $user = User::find($renwu->userid);
            $user->coin = $user->coin -1;
            $user->save(); //用户积分 -1
            $renwu->has_click =  $renwu->has_click+1;
            $renwu->save(); //该任务点击数+1
            $clicklog =  ClickLog::where('created_at','like','%'.date('Y-m-d').'%')->where(['keyword_id'=>$renwu->id])->first();
            if (empty($clicklog)){
                $clicklog = new ClickLog();
                $clicklog->keyword = $renwu->keyword;
                $clicklog->userid = $renwu->userid;
                $clicklog->has_click = $renwu->has_click;
                $clicklog->keyword_id = $renwu->id;
                $clicklog->click = $renwu->click;
                $clicklog->coin = $user->coin;
                $clicklog->save(); //将已经点击完成的任务添加到点击日志中
            }else{
                $clicklog->keyword = $renwu->keyword;
                $clicklog->userid = $renwu->userid;
                $clicklog->has_click = $renwu->has_click;
                $clicklog->keyword_id = $renwu->id;
                $clicklog->click = $renwu->click;
                $clicklog->coin = $user->coin;
                $clicklog->save(); //将已经点击完成的任务添加到点击日志中
            }
            $renwu->coin = $renwu->coin -1;
            return $renwu;
        }else{
            $user = User::find($renwu->userid);
            $user->coin = $user->coin -1;
            $user->save(); //用户积分 -1
            $renwu->has_click =  $renwu->has_click+1;
            $renwu->save(); //该任务点击数+1
            $clicklog =  ClickLog::where('created_at','like','%'.date('Y-m-d').'%')->where(['keyword_id'=>$renwu->id])->first();
            if (empty($clicklog)){
                $clicklog = new ClickLog();
                $clicklog->keyword = $renwu->keyword;
                $clicklog->userid = $renwu->userid;
                $clicklog->has_click = $renwu->has_click;
                $clicklog->keyword_id = $renwu->id;
                $clicklog->click = $renwu->click;
                $clicklog->coin = $user->coin;
                $clicklog->save(); //将已经点击完成的任务添加到点击日志中
            }else{
                $clicklog->keyword = $renwu->keyword;
                $clicklog->userid = $renwu->userid;
                $clicklog->has_click = $renwu->has_click;
                $clicklog->keyword_id = $renwu->id;
                $clicklog->click = $renwu->click;
                $clicklog->coin = $user->coin;
                $clicklog->save(); //将已经点击完成的任务添加到点击日志中
            }
            $renwu->coin = $renwu->coin -1;
            return $renwu;
        }
    }
}
