<?php

namespace App\Console\Commands;

use App\Keyword;
use App\RankUpdate;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class Push extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:start';
    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 100;  // 同时并发抓取
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
        $i=1;
        while (true){
            $ch = curl_init();
            $ua = self::useragent();
            $referer = self::referer();
            $url =  'http://api.share.baidu.com/s.gif?r='.$referer.'&l='.self::referer();
            $headers = array();
            $headers[] = 'X-Apple-Tz: 0';
            $headers[] = 'X-Apple-Store-Front: 143444,12';
            $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
            $headers[] = 'Accept-Encoding: gzip, deflate';
            $headers[] = 'Accept-Language: en-US,en;q=0.5';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
            $headers[] = 'User-Agent: '.$ua;
            $headers[] = 'Host: api.share.baidu.com';
            $headers[] = 'Connection: keep-alive';
            $headers[] = 'Referer: '.$referer;
            $headers[] = 'X-MicrosoftAjax: Delta=true';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,CURLOPT_URL,$url);
        // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $i++;
            echo '第'.$i.'条,推送 '.$info['url'].'耗时'.$info['total_time'].'秒'.PHP_EOL;
            if($output === FALSE ){
                echo "CURL Error:".curl_error($ch);
            }
        // 4. 释放curl句柄
            curl_close($ch);
        }
    }

    public function useragent()
    {
        $ua = file(public_path('ua.txt'));
        return str_replace(array("\r\n", "\r", "\n"), "", array_random($ua));
    }

    public function referer()
    {
        $file = file(public_path('seo.txt'));
        return str_replace(array("\r\n", "\r", "\n"), "",array_random($file));
    }


}

