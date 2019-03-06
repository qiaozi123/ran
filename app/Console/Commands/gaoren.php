<?php

namespace App\Console\Commands;

use App\Keyword;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use function GuzzleHttp\Psr7\str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class gaoren extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cc:start';
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
        $this->uadata = file(public_path('ua.txt'));
//        while (true){
        for($i=0;$i<20;$i++){
            $this->url[] = 'http://pengfuclu.12xiaoshuo.cn';
        }
        $this->totalPageCount = count($this->url);
        $client = new Client();
        $requests = function ($total) use ($client) {
            foreach ($this->url as $uri) {
                yield function() use ($client, $uri) {
                    $data = file_get_contents(public_path('ip.txt'));
                    if (empty($data)){
                        $this->getip();
                        $data = file_get_contents(public_path('ip.txt'));
                        $json = json_decode($data);
                        $this->ip = $json->data[0]->IP;
                    }else{
                       $json = json_decode($data);
                       $this->expiretime = $json->data[0]->ExpireTime;
                       if ($this->expiretime < time()){
                           $this->ip = $json->data[0]->IP;
                       }else{
                           $this->getip();
                           $data = file_get_contents(public_path('ip.txt'));
                           $json = json_decode($data);
                           $this->ip = $json->data[0]->IP;
                       }
                    }
                    $this->ua = $str = str_replace(array("\r\n", "\r", "\n"), "", array_random($this->uadata));

                    return $client->getAsync($uri, [
                        'headers' => [
                            'User-Agent' => $this->ua,
                            'Accept'     => 'application/json',
                        ],
                        'proxy' => $this->ip
                    ]);
                };
            }
        };

        $pool = new Pool($client, $requests($this->totalPageCount), [
            'concurrency' => $this->concurrency,
            'fulfilled'   => function ($response, $index){
                $this->index = $index;
                $html = $response->getBody()->getContents();
                dd($html);
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
//        }
    }

    public function getip()
    {
        $url = 'http://ip.11jsq.com/index.php/api/entry?method=proxyServer.generate_api_url&packid=2&fa=0&fetch_key=&qty=1&time=1&pro=&city=&port=1&format=json&ss=5&css=&ipport=1&et=1&dt=1&specialTxt=3&specialJson=';
        $data = file_get_contents($url);
        $myfile = fopen(public_path("ip.txt"), "w");
        fwrite($myfile, $data);
        fclose($myfile);
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            return;
        }
    }

}

