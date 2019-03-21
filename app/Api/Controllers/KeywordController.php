<?php

namespace App\Api\Controllers;

use App\Keyword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeywordController extends Controller
{
    public function index()
    {
        $keyword =  Keyword::all();
        return $keyword;
     }

    public function updatestatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $userid = $request->input('userid');
        $type = $request->input('type');
        if (empty($type)){
            return response()->json(['status'=>500,'msg'=>'type不能为空']);
        }
        if (empty($userid)){
            return response()->json(['status'=>500,'msg'=>'userid不能为空']);
        }
        if (empty($id)){
            return response()->json(['status'=>500,'msg'=>'id不能为空']);
        }
        if (empty($type)){
            return response()->json(['status'=>500,'msg'=>'状态不能为空']);
        }
        $user = \App\User::find($userid);

        if ($type==1){
            if ($user->coin < 30){
                return  response()->json(['status'=>501,'msg'=>'积分不足，为了避免排名下滑，请充值']);
            }
        }

        if ($type==2){
            if ($user->m_coin < 30){
                return  response()->json(['status'=>501,'msg'=>'积分不足，为了避免排名下滑，请充值']);
            }
        }

        $keyword =  Keyword::find($id);
        if ($keyword->status == 1){
            return response()->json(['status'=>500,'msg'=>'已经开始优化']);
        }else{
            $keyword->status = $status;
            $keyword->optimized_at = date('Y-m-d H:i:s');
            $bool = $keyword->save();
            if ($bool){
                return response()->json(['status'=>200,'msg'=>'开始优化']);
            }else{
                return response()->json(['status'=>500,'msg'=>'修改失败']);
            }
        }


     }
}
