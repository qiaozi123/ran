<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use QL\QueryList;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Psr7\Response;

class Shoulu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shoulu:start';
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 10;  // 同时并发抓取
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取目录收录量';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '128M');
        $web = 'https://www.pengfu.com';
        $mulu = 'https://www.pengfu.com/clu';
        for ($i=0; $i <20 ; $i++) {
            $urls[$i] ='http://www.baidu.com/s?wd=site%3Awww.pengfu.com%20inurl%3Aclu&pn='.$i.'0&oq=site%3Awww.pengfu.com%20inurl%3Aclu&ct=2097152&tn=baiduhome_pg&ie=utf-8&si=www.pengfu.com&rsv_idx=2&rsv_pq=edb8723f00118009&rsv_t=f0bdEQOPEp5ozru4EraHyl40tiBsvRiaBD4ZheGHxHyxjWlCgCIVT1o2U9mDR3PXLR1U&gpc=stf%3D1544261480%2C1544866280%7Cstftype%3D1&tfflag=1';
        }

       QueryList::multiGet($urls)
            ->success(function(QueryList $ql,Response $response, $index) use($urls){
                $this->info('第'.$index.'页'.'当前url: '.$urls[$index].PHP_EOL);

                $data = $ql->rules(
                    ['link' => ['a','href'],
                    // 采集所有a标签的文本内容
                    'text' => ['a','text']
                    ])->query()->getData();
                $this->newdata[$index] = $data->all();
            })->send();

       foreach ($this->newdata as $item){
            foreach ($item as $value){
                $zdata[] = $value;
            }
       }

        foreach ($zdata as $item){
            $bool = strstr($item['link'], 'http://www.baidu.com/link?url');
            if ($bool){
                $arr[] = $item;
            }
        }

        foreach ($arr as $item){
            if (strstr($item['text'],'http')) {  //判断字符串是词
            } else {
                $new[] = $item;
            }
        }

        foreach ($new as $item){
            if (!empty($item['text'])){
                $dataarr[] = $item;
            }
        }

        foreach ($dataarr as $key=>$item){
            $this->info('正在抓取'.$item['link']);
            $headers = get_headers($item['link'], TRUE);
            $dataarr[$key]['url'] = $headers['Location'];
            $dataarr[$key]['web'] = $web;
            $dataarr[$key]['mulu'] = $mulu;
            $dataarr[$key]['created_at'] = date('Y-m-d H:i:s');
            $dataarr[$key]['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $bool=  DB::table('keyword')->insert($dataarr);
        if ($bool){
            $this->info('数据库插入'.$bool);
        }else{
            $this->info('数据库插入'.$bool);
        }
//        $shoulufilepath = '收录'.date('Y-m-d').".txt";
//        foreach ($dataarr as $item){
//            $str = implode(" ",$item);
//            file_put_contents(public_path($shoulufilepath), $str."\n", FILE_APPEND);
//        }
//        $this->info('收录结果追加完毕'.PHP_EOL);
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            return;
        }
    }

}

