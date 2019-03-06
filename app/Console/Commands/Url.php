<?php

namespace App\Console\Commands;

use App\Keyword;
use App\RankUpdate;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class Url extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'url:start';

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
        $shell = file(public_path('shell.txt'));
        foreach ($shell as $item){
            $cn = strpos($item,".cn");


        }
        dd($shell);
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

