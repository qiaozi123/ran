<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nesk\Puphpeteer\Puppeteer;
use QL\Ext\Baidu;
use QL\QueryList;

class TaskController extends Controller
{

    public function list(Request $request)
    {
        $keyword = $request->input('keyword');
        $status = $request->input('status');
        $rank = $request->input('rank');
        $dohost = $request->input('dohost');
        $userid = Auth::user()->id;

        if (!empty($keyword)){
            $data = Keyword::where(['userid'=>$userid,'keyword'=>$keyword])
                ->join('searchengines','searchengines.id','=','keywords.searchengines')
                ->select('searchengines.name as searchengines','keywords.id','keywords.keyword','keywords.dohost','keywords.status','keywords.rank','keywords.created_at','keywords.click')
                ->paginate(15);
            return view('task.list',compact('data'));
        }
        $data = Keyword::where(['userid'=>$userid])
            ->join('searchengines','searchengines.id','=','keywords.searchengines')
            ->select('searchengines.name as searchengines','keywords.id','keywords.keyword','keywords.dohost','keywords.status','keywords.rank','keywords.created_at','keywords.click')
            ->paginate(15);

        return view('task.list',compact('data'));
    }

    public function piliang()
    {
        return view('task.piliang');
    }

    public function dopiliang(Request $request)
    {
        $userid = Auth::user()->id;
        $searchengineid =$request->input('searchengineid');
        $keywords = $request->input('keywords');
        if (empty($searchengineid)){
            return "搜索引擎不能为空";
        }
        if (empty($keywords)){
            return '关键词不能为空';
        }
        $data = explode("\n",$keywords);
        foreach ($data as $key=>$item){
            $renwu[$key]['keyword'] = explode('>',$item)[0];
            $renwu[$key]['dohost'] = explode('>',$item)[1];
            $renwu[$key]['click'] = explode('>',$item)[2];
            $renwu[$key]['searchengines'] = $searchengineid;
            $renwu[$key]['status'] = 0;
            $renwu[$key]['type'] = 0;
            $renwu[$key]['userid'] = $userid;
            $renwu[$key]['created_at'] = date('Y-m-d h:i:s');
            $renwu[$key]['updated_at'] = date('Y-m-d h:i:s');
        }
        $res = DB::table('keywords')->insert($renwu);
        if ($res){
            return response()->json(['status'=>200,'msg'=>'任务批量添加成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'任务批量添加失败']);
        }
    }

    public function update(Request $request)
    {
        $keyword_id = $request->input('id');
        $data = Keyword::find($keyword_id);
        return view('task.update',compact('data'));
    }

    public function doupdate(Request $request)
    {
        $searchengines = $request->input('searchengines');
        $userid = $request->input('userid');
        $keyword_id = $request->input('keyword_id');
        $keyword = $request->input('keyword');
        $dohost = $request->input('dohost');
        $click = $request->input('click');
        if ($searchengines ==""){
            return '搜索引擎不能为空';
        }
        if ($userid ==""){
            return '用户不能为空';
        }
        if ($keyword_id ==""){
            return '关键词id不能为空';
        }
        if ($keyword ==""){
            return '关键词不能为空';
        }
        if ($dohost ==""){
            return '网址不能为空';
        }
        if ($click ==""){
            return '点击数量不能为空';
        }
        $bool = Keyword::where(['userid'=>$userid,'id'=>$keyword_id])->first(); //该用户是否拥有该任务
        if (empty($bool)){
            return '没有该任务,非法操作';
        }
        $bool->keyword = $keyword;
        $bool->dohost = $dohost;
        $bool->keyword = $keyword;
        $bool->click = $click;
        $res = $bool->save();
        if ($res){
            return response()->json(['status'=>200,'msg'=>'修改成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'修改失败']);

        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        if ($id ==""){
            return '任务id不能为空';
        }
        $userid = Auth::user()->id;
        $bool = Keyword::where(['userid'=>$userid,'id'=>$id])->first(); //该用户是否拥有该任务
        if (empty($bool)){
            return '没有该任务,非法操作';
        }
        $res = $bool->delete();
        if ($res){
            return response()->json(['status'=>200,'msg'=>'删除成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'删除失败']);
        }
    }

    public function updatestatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if ($id ==""){
            return '任务id不能为空';
        }
        if ($status ==""){
            return '任务状态不能为空';
        }
        $userid = Auth::user()->id;
        $bool = Keyword::where(['userid'=>$userid,'id'=>$id])->first(); //该用户是否拥有该任务
        if (empty($bool)){
            return '没有该任务,非法操作';
        }
        $bool->status = $status;
        $res = $bool->save();
        if ($res){
            return response()->json(['status'=>200,'msg'=>'任务状态修改成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'任务状态修改失败']);
        }
    }

    public function updatestatus_many(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if (!is_array($id)){
            return '任务id参数格式不正确';
        }
        if ($status ==""){
            return '任务状态不能为空';
        }
        if ($status >2){
            return '任务状态参数异常';
        }
        $userid = Auth::user()->id;
        $bool = Keyword::where(['userid'=>$userid])->wherein('id',$id)->get()->toArray(); //该用户是否拥有该任务
        if (empty($bool)){
            return '没有该任务,非法操作';
        }
        $res =Keyword::wherein('id', $id)
            ->update(['status' => $status]);

        if ($res){
            return response()->json(['status'=>200,'msg'=>'任务状态修改成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'任务状态修改失败']);
        }
    }

    public function one()
    {
        return view('task.one');
    }

    public function docreate(Request $request)
    {
        $userid = Auth::user()->id;
        $searchengineid =$request->input('searchengineid');
        $keywords = $request->input('keywords');
        $dohost = $request->input('dohost');
        $click = $request->input('click');
        if (empty($searchengineid)){
            return "收索引擎不能为空";
        }
        if(empty($keywords)){
            return "关键词不能为空";
        }
        if (empty($click)){
            return "每日点击数量不能为空";
        }
        if (empty($dohost)){
            return "网址不能为空";
        }
        foreach ($searchengineid as $key=>$item){
            $data[$key]['keyword']=$keywords;
            $data[$key]['dohost'] =$dohost;
            $data[$key]['click'] =$click;
            $data[$key]['searchengines'] =$item;
            $data[$key]['status'] =0;
            $data[$key]['userid'] =$userid;
            $data[$key]['created_at'] =date('Y-m-d H:i:s');
            $data[$key]['updated_at'] =date('Y-m-d H:i:s');
        }
        $bool = DB::table('keywords')->insert($data);
        if ($bool){
            return response()->json(['status'=>200,'msg'=>'单个任务创建成功']);
        }else{
            return response()->json(['status'=>500,'msg'=>'单个任务创建失败']);
        }
    }
}
