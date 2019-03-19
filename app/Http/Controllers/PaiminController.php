<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nesk\Puphpeteer\Puppeteer;
use QL\Ext\Baidu;
use QL\QueryList;

class PaiminController extends Controller
{
    public function index(Request $request)
    {
        $keyword = urlencode($request->input('keyword'));
            $i=1;
            $ch = curl_init();
            $ua = self::useragent();
            $referer = self::referer();
            $url =  'https://www.baidu.com/s?&wd='.$keyword.'&ie=utf-8';
//            $url =  'https://blog.csdn.net/kimbing/article/details/80105684';
            $headers = array();
            $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
            $headers[] = 'Accept-Encoding: gzip, deflate, br';
            $headers[] = 'Accept-Language: zh-CN,zh;q=0.9';
            $headers[] = 'Cache-Control: max-age=0';
            $headers[] = 'Connection: keep-alive';
            $headers[] = 'Cookie: BAIDUID=96DECE0BA246C00438753B7DDC13C5B3:FG=1; BIDUPSID=96DECE0BA246C00438753B7DDC13C5B3; PSTM=1551335997; BD_UPN=12314753; BDUSS=lLdnBodk5SMGZoTFZ-OXZNZ09PbnNmN1BtM2FFbTBMYUUxQWhVQWwzSHY4SzVjQVFBQUFBJCQAAAAAAAAAAAEAAABM~G0pucK05bbA1MLSuQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAO9jh1zvY4dcVz; BDORZ=B490B5EBF6F3CD402E515D22BCDA1598; plus_cv=1::m:49a3f4a6; lsv=globalTjs_3aec804-wwwTcss_160499a-wwwBcss_b12fcc9-framejs_89a8ca6-atomentryjs_689bd71-globalBjs_113da98-sugjs_1b62455-wwwjs_e30a9a2; MSA_WH=400_850; H_PS_PSSID=26523_1420_21124_20697_28722_28557_28608_28585_28641_28644_28605; H_PS_645EC=ff18RMrZTuQkO3jAbXtWn0%2B6wDLSOmO0tA9J65KTXhcIIdBY0TR55IgLus4';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
            $headers[] = 'User-Agent: '.$ua;
            $headers[] = 'Host: www.baidu.com';
            $headers[] = 'Upgrade-Insecure-Requests: 1';
            $headers[] = 'Referer: '.$referer;
            $headers[] = 'X-MicrosoftAjax: Delta=true';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,CURLOPT_URL,$url);
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            dd($output);


            $info = curl_getinfo($ch);
            $i++;
            echo '第'.$i.'条,推送 '.$info['url'].'耗时'.$info['total_time'].'秒'.PHP_EOL;

            if($output === FALSE ){
                echo "CURL Error:".curl_error($ch);
            }
            // 4. 释放curl句柄
            curl_close($ch);
            dd($info);
    }

    public function useragent()
    {
        $ua = file(public_path('ua.txt'));
        return str_replace(array("\r\n", "\r", "\n"), "", array_random($ua));
    }

    public function referer()
    {
        $host = env('PUSH_HOST');
        $file = [$host.str_random(5).'/'.str_random(5).'.html',$host.str_random(3).'/'.str_random(5).'/',$host.str_random(6).'.html'];
        return array_random($file) ;
    }

}
