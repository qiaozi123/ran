<?php

namespace App\Http\Controllers;

use App\Keyword;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;
use Excel;

class ExcelController extends Controller
{
    public function task()
    {
        $userid = \Illuminate\Support\Facades\Auth::user()->id;
        $keyword =  Keyword::where(['userid'=>$userid])->
        join('searchengines','searchengines.id','=','keywords.searchengines')->
        select('keywords.keyword as 0','keywords.dohost as 1','searchengines.name as 2','keywords.click as 3','keywords.status as 4')->get()->toArray();
        foreach ($keyword as $key=>$item){
            if($item[4]= 0){
                $keyword[$key][4] = '未优化';
            }elseif ($item[4]=1){
                $keyword[$key][4] = '优化中';
            }elseif ($item[4]=2){
                $keyword[$key][4] = '暂停中';
            }
        }
        $cellData = $keyword;
        $name = iconv('UTF-8', 'GBK', '任务列表');
        Excel::create($name,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->store('xls')->export('xls');
    }
}
