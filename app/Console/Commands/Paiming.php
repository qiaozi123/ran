<?php

namespace App\Console\Commands;

use App\Keyword;
use App\Ranklog;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class Paiming extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paiming:start';
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 100;  // 同时并发抓取
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷展现';

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
        $this->url = Keyword::all()->toArray();
        $this->mulu = $this->url[0]['mulu'];

        foreach ($this->url as $key=>$one){
            $this->info( '更新《'.$one['text'].'》的排名');
            for ($i=0;$i<20;$i++){
                $newurl[$key][$i]['id'] = $one['id'];
                $newurl[$key][$i]['pagenumber'] = $i;
                $newurl[$key][$i]['text'] = $one['text'];
                $newurl[$key][$i]['mulu'] = $one['mulu'];
                $newurl[$key][$i]['url'] = 'http://www.baidu.com/s?wd='.urlencode($one['text']).'&pn='.$i.'0&oq='.urlencode($one['text']).'&tn=baiduhome_pg&ie=utf-8&rsv_idx=2&rsv_pq=a433278d00092133';
            }

            foreach ($newurl as $item){
                foreach ($item as $value){
                    $this->alldata[] = $value;
                }
            }

            $bool = self::getpagenumer($this->alldata,$one,$this->mulu);
            if ($bool){
                continue ;
            }
        }
    }


    //处理排名页数 如果存在执行 rank计算
    public function getpagenumer($alldata,$one,$mulu)
    {

        $this->alldata = $alldata;
        $pagenumber =null;
        foreach ($alldata as $page=>$item){
            $client = new Client();
            $html =  $client->get($item['url'])->getBody()->getContents();
            $this->page = $page;
            $find = strpos($html,'https://www.pengfu.com/clu');
            $this->info($find);
            if ($find == false){
                $this->info('第'.$this->alldata[$page]['pagenumber'].'页没有收录');
                continue ;
            }

            if ($find>0)
            {
                $pagenumber = $this->alldata[$page]['pagenumber'];
                $crawler = new Crawler();
                $crawler->addHtmlContent($html);
                $arr = $crawler->filter('#content_left > div')->each(function ($node,$i) use ($html) {
                    try
                    {
                        $data['link'] = $node->filter('div > div.f13 > a.c-showurl')->text();
                        $data['jump'] = $node->filter('div > h3 > a')->attr('href');
                        return $data;
                    }
                    catch(\Exception $e)
                    {
                        $this->info('第'.$this->alldata[$this->page]['pagenumber'].'页排名数据抓取失败');
                    }
                });

               $bool = self::getrank($arr,$mulu,$page,$one);

               if ($bool ==2){
                   return true;
               }
            }
            if ($page==count($alldata)-1){
                if ($pagenumber==null){
                    $this->info('没有找到数据');
                    $rank = new Ranklog();
                    $rank->pagenumber =  999;
                    $rank->keyword_id =  $one['id'];
                    $rank->rank =  999;
                    $bool = $rank->save();
                    if ($bool){
                        $this->info('排名在20页之外');
                        return true;
                    }else{
                        $this->info('排名在20页之外,插入失败');
                    }
                }
            }
        }
    }


    public function getrank($arr,$mulu,$page,$currendata)
    {
        foreach ($arr as $key=>$item){
            if (!empty($item)){
                if (strstr($item['link'],$mulu)){
                    $this->info('排名在第'.($this->alldata[$page]['pagenumber']+1).'页,第'.($key+1).'行');
                    $rank = new Ranklog();
                    $rank->pagenumber =  $this->alldata[$this->page]['pagenumber']+1;
                    $rank->keyword_id =  $currendata['id'];
                    $rank->rank =  $key+1;
                    $bool = $rank->save();
                    if ($bool){
                        $this->info('排名更新插入成功');
                        return 2;
                    }else{
                        $this->info('insert database fail');
                    }
                }
            }
        }
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            return;
        }
    }

}

