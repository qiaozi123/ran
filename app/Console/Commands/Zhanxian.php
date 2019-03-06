<?php

namespace App\Console\Commands;

use App\Keyword;
use App\RankUpdate;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;

class Zhanxian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhanxian:start';
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 200;  // 同时并发抓取
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
        ini_set('memory_limit','1280M');
        $this->id = 10;
        $keyword = Keyword::where(['id'=>$this->id])->first();
        $this->mulu = $keyword->mulu;
        for($i=0;$i<5000;$i++){
            $this->url[] = 'http://www.baidu.com/s?wd='.urlencode($keyword->text).'&pn='.($keyword->pagenumber-1).'0&oq='.urlencode($keyword->text).'&tn=baiduhome_pg&ie=utf-8&rsv_idx=2&rsv_pq=a433278d00092133';
        }

        $this->totalPageCount = count($this->url);
        $client = new Client();
        $requests = function ($total) use ($client) {
            foreach ($this->url as $uri) {
                yield function() use ($client, $uri) {
                    return $client->getAsync($uri);
                };
            }
        };
        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => function ($response, $index){
                $this->index = $index;
                $this->info('第'.$index.'展现');
                $this->
//                $crawler = new Crawler();
//                $html = $response->getBody()->getContents();
//                $crawler->addHtmlContent($html);
//                $arr = $crawler->filter('#content_left > div')->each(function ($node,$i) use ($html) {
//                    try
//                    {
//                        $data['link'] = $node->filter('div > div.f13 > a.c-showurl')->text();
//                        $data['jump'] = $node->filter('div > h3 > a')->attr('href');
//                        return $data;
//                    }
//                    catch(\Exception $e)
//                    {
//                        echo '';
//
//                    }
//                });
//
//                foreach ($arr as $item){
//                    if (!$item==null){
//                        if (strstr($item['link'],$this->mulu)){
//                            $click = new \App\Click();
//                            $click->url = $item['jump'];
//                            $click->keyword_id = $this->id;
//                            $bool = $click->save();
//                            if ($bool){
//                                echo 'insert success';
//                            }else{
//                                echo 'insert fail';
//                            }
//                        }
//                    }
//                }

                $this->countedAndCheckEnded();
            },
            'rejected' => function ($reason, $index){
//                    log('test',"rejected" );
//                    log('test',"rejected reason: " . $reason );
                $this->countedAndCheckEnded();
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            return;
        }
    }
}

