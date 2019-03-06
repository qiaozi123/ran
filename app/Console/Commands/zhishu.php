<?php

namespace App\Console\Commands;

use App\Keyword;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class zhishu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhishu:start';
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 300;  // 同时并发抓取
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
        $this->id = 10;
        $keyword = Keyword::where(['id'=>$this->id])->first();
        $this->ip = self::getip();
//        while (true){

            for($i=0;$i<20000;$i++){
                $this->url[] = 'http://www.baidu.com/s?wd='.urlencode($keyword->text).'&pn='.($keyword->pagenumber-1).'0&oq='.urlencode($keyword->text).'&tn=baiduhome_pg&ie=utf-8&rsv_idx=2&rsv_pq=a433278d00092133';
            }

            $this->totalPageCount = count($this->url);
            $client = new Client();
            $requests = function ($total) use ($client) {
                foreach ($this->url as $uri) {
                    yield function() use ($client, $uri) {
                        return $client->getAsync($uri,['proxy' => $this->ip->IP]);
                    };
                }
            };

            $pool = new Pool($client, $requests($this->totalPageCount), [
                'concurrency' => $this->concurrency,
                'fulfilled'   => function ($response, $index){
                    echo  $index.PHP_EOL;
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
//    }

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

