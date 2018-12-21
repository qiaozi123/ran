<?php

namespace App\Console\Commands;

use App\Keyword;
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
        $this->url = Keyword::where('id','=','161')->get()->toArray();
        $this->mulu = $this->url[0]['mulu'];
        foreach ($this->url as $key=>$item){
            for ($i=0;$i<20;$i++){
                $newurl[$key][$i]['id'] = $item['id'];
                $newurl[$key][$i]['pagenumber'] = $i;
                $newurl[$key][$i]['text'] = $item['text'];
                $newurl[$key][$i]['mulu'] = $item['mulu'];
                $newurl[$key][$i]['url'] = 'http://www.baidu.com/s?wd='.urlencode($item['text']).'&pn='.$i.'0&oq='.urlencode($item['text']).'&tn=baiduhome_pg&ie=utf-8&rsv_idx=2&rsv_pq=a433278d00092133';

            }
        }


        foreach ($newurl as $item){
            foreach ($item as $value){
                $this->alldata[] = $value;
            }
        }

        $this->totalPageCount = count($this->alldata);
        $client = new Client();
        $requests = function ($total) use ($client) {
            foreach ($this->alldata as $uri) {
                yield function() use ($client, $uri) {
                    $this->trueurl = $uri['url'];
                    return $client->getAsync($this->trueurl);
                };
            }
        };

        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => function ($response, $index){
                $this->index = $index;
                echo '获取《'.$this->trueurl.'》的排名'.PHP_EOL;
                $http = $response->getBody()->getContents();
                $find = strpos($http,'https://www.pengfu.com/clu',1);
                dd($http);
                if (!$find){
                    $this->info('第'.$this->alldata[$index]['pagenumber'].'页没有');
                }else{
                    $crawler = new Crawler();
                    $crawler->addHtmlContent($http);
                    if ($response->getStatusCode() ==200){
                        $arr = $crawler->filter('#content_left > div')->each(function ($node,$i) use ($http) {
//                        try
//                        {
                            $data['link'] = $node->filter('div > div.f13 > a.c-showurl')->text();
                            return $data;
//                        }
//                        catch(\Exception $e)
//                        {
//                            $this->error('第'.$this->alldata[$this->index]['pagenumber'].'页排名数据抓取失败');
//                        }
                        });
                        dd($arr);
                        foreach ($arr as $key=>$item){
                            $this->info($item['link']);
                            sleep(1);
                            if (strstr($item['link'],$this->mulu)){
                                dd('第'.$this->alldata[$index]['pagenumber'].'页'.'第'.($key+1).'位');
                            }else{
                                $this->info('第'.$this->alldata[$index]['pagenumber'].'页,第'.($key+1).'行没有');
                            }
                        }
                    }
                }

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

