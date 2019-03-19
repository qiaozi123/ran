<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nesk\Puphpeteer\Puppeteer;
use QL\Ext\Baidu;
use QL\QueryList;

class BaiduController extends Controller
{
    public function pc(Request $request){
        set_time_limit(0);
        $keywordid = $request->input('keywordid');
        $keyword = Keyword::find($keywordid);
        if (empty($keyword)){
            return '关键词不能为空';
        }

        $ql = QueryList::getInstance();
        $ql->use(Baidu::class);
        $baidu = $ql->baidu(10);
        $searcher = $baidu->search($keyword->keyword);
        $countPage = 80;  // 获取搜索结果总页数
        for ($page = 1; $page <= $countPage; $page++)
        {
            $data= $searcher->page($page);

            foreach ($data as $key=>$url){
                $reallink = $this->get_real_url($url['link']);
                $savedata[$page-1][$key]['title'] = $url['title'];
                $savedata[$page-1][$key]['baidu_link'] = $url['link'];
                $savedata[$page-1][$key]['real_url'] = $reallink;
                $savedata[$page-1][$key]['page'] = $page;
                $savedata[$page-1][$key]['keyword_id'] = $keywordid;
                $savedata[$page-1][$key]['created_at'] = date('Y-m-d H:i:s');
                $savedata[$page-1][$key]['updated_at'] = date('Y-m-d H:i:s');
                if (strpos($reallink,$keyword->dohost) !== false){
                    if ($page==1){
                        $page=0;
                    }
                    $rank = $page*10+$key+1;
                    break ;
                }
            }
            if (!empty($rank)){
                break ;
            }
        }
        foreach ($savedata as $item){
            $bool = DB::table('rank')->insert($item);
            echo $bool;
        }

    }

    public function get_real_url($data)
    {
        $info = parse_url($data);
        $fp = fsockopen($info['host'], 80,$errno, $errstr, 30);
        fputs($fp,"GET {$info['path']}?{$info['query']} HTTP/1.1\r\n");
        fputs($fp, "Host: {$info['host']}\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        $rewrite = '';
        while(!feof($fp)) {
            $line = fgets($fp);
            if($line != "\r\n" ) {
                if(strpos($line,'Location:') !== false) {
                    $rewrite = str_replace(array("\r","\n","Location: "),'',$line);
                }
            }else {
                break;
            }
        }
        return $rewrite ;//输出http://tieba.baidu.com/p/1668410830
    }

    public function pc_rank()
    {
        $keyword = "Admin";
        $url ='https://www.baidu.com/s?cl=3&tn=baidutop10&fr=top1000&wd=&rsv_idx=2&rsv_dl=fyb_n_homepage';

    }

    public function move_push()
    {
        $url ='https://www.baidu.com/s?cl=3&tn=baidutop10&fr=top1000&wd=&rsv_idx=2&rsv_dl=fyb_n_homepage';
        
    }

}
