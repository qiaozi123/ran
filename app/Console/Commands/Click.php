<?php

namespace App\Console\Commands;

use App\Keyword;
use App\RankUpdate;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class Click extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'click:start';
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 10;  // 同时并发抓取
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $keyword = \App\Click::where(['keyword_id'=>10])->get();

        foreach ($keyword as $key=>$item){
                $ip = self::getip();
                $uaarr = file(public_path('ua.txt'));
                $ua = $str = str_replace(array("\r\n", "\r", "\n"), "", array_random($uaarr)); ;
                $client = new Client();
                $client->request('GET', $item['url'], [
                    'proxy' => $ip->IP,
                    'headers' => [
                        'User-Agent' => $ua,
                    ]
                ]);

               echo '第'.$key.'次点击';
        }
//
//
//        $this->totalPageCount = count($this->url);
//        $client = new Client();
//        $requests = function ($total) use ($client) {
//            foreach ($this->url as $uri) {
//                yield function() use ($client, $uri) {
//
//                    return $client->getAsync($uri);
//                };
//            }
//        };
//        $pool = new Pool($client, $requests($this->totalPageCount), [
//            'concurrency' => $this->concurrency,
//            'fulfilled'   => function ($response, $index){
//                $this->index = $index;
//                $this->info('第'.$index.'展现');
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
//
//                $this->countedAndCheckEnded();
//            },
//            'rejected' => function ($reason, $index){
////                    log('test',"rejected" );
////                    log('test',"rejected reason: " . $reason );
//                $this->countedAndCheckEnded();
//            },
//        ]);
//
//        $promise = $pool->promise();
//        $promise->wait();
//
//
//        for($i=0;$i<5000;$i++){
//            $this->i = $i;
//            $this->url = 'http://www.baidu.com/s?wd='.urlencode($keyword->text).'&pn='.($keyword->pagenumber-1).'0&oq='.urlencode($keyword->text).'&tn=baiduhome_pg&ie=utf-8&rsv_idx=2&rsv_pq=a433278d00092133';
//            $client = new Client();
//            $html = $client->get($this->url);
//            $crawler = new Crawler();
//            $html = $html->getBody()->getContents();
//
//            $crawler->addHtmlContent($html);
//            $arr = $crawler->filter('#content_left > div')->each(function ($node,$i) use ($html) {
//                try
//                {
//                    $data['link'] = $node->filter('div > div.f13 > a.c-showurl')->text();
//                    $data['jump'] = $node->filter('div > h3 > a')->attr('href');
//                    return $data;
//                }
//                catch(\Exception $e)
//                {
//                    echo 'successfully';
//                }
//            });
//
//            foreach ($arr as $item){
//                if (!empty($item)){
//
//                    if (strstr($item['link'],$this->mulu)){
//                        $uaarr = file(public_path('ua.txt'));
//                        $ua = $str = str_replace(array("\r\n", "\r", "\n"), "", array_random($uaarr)); ;
//                        $client = new Client();
//                        $ip = self::getip();
//                        $client->get($item['jump'],[]);
//                        $client->request('GET', $item['jump'], [
//                            'proxy' => $ip->IP,
//                            'headers' => [
//                                'User-Agent' => $ua,
//                            ]
//                        ]);
//                        echo '点击'. $this->i.'<br>';
//                    }
//                }
//            }
//        }
//
//
//
//
//        $pool = new Pool($client, $requests($this->totalPageCount), [
//            'concurrency' => $this->concurrency,
//            'fulfilled'   => function ($response, $index){
//                $this->index = $index;
//                $this->info('第'.$index.'展现');
//
//                $crawler = new Crawler();
//                $html = $response->getBody()->getContents();
//
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
//                        echo 'successfully'.'<br>';
//                    }
//
//                });
//
//                foreach ($arr as $item){
//                    if (!empty($item)){
//
//                        if (strstr($item['link'],$this->mulu)){
//                            $uaarr = file(public_path('ua.txt'));
//                            $ua = $str = str_replace(array("\r\n", "\r", "\n"), "", array_random($uaarr)); ;
//                            $client = new Client();
//                            $ip = self::getip();
//                            $client->get($item['jump'],[]);
//                            $client->request('GET', $item['jump'], [
//                                'proxy' => $ip->IP,
//                                'headers' => [
//                                    'User-Agent' => $ua,
//                                ]
//                            ]);
//                            echo '点击'.$index.'<br>';
//                        }
//                    }
//                }
//                $this->countedAndCheckEnded();
//            },
//            'rejected' => function ($reason, $index){
////                    log('test',"rejected" );
////                    log('test',"rejected reason: " . $reason );
//                $this->countedAndCheckEnded();
//            },
//        ]);
//        $promise = $pool->promise();
//        $promise->wait();

    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            return;
        }
    }



    public function getip()
    {
        if (empty($this->ipdata)){
            $ipurl = 'http://ip.11jsq.com/index.php/api/entry?method=proxyServer.generate_api_url&packid=2&fa=0&fetch_key=&qty=20&time=1&pro=&city=&port=1&format=json&ss=5&css=&ipport=1&et=1&pi=1&co=1&dt=1&specialTxt=3&specialJson=';
            $ipjson = file_get_contents($ipurl);
            $this->ipdata = json_decode($ipjson)->data;
        }else{
            foreach ($this->ipdata as $key=>$item){
                if (time() > strtotime($item->ExpireTime)){
                    unset($this->ipdata[$key]);
                    $this->ipdata = array_values($this->ipdata);
                }
            }
        }

        return array_random($this->ipdata);
    }



}

