<?php

namespace App\Console\Commands;

use App\Keyword;
use App\RankUpdate;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Nesk\Puphpeteer\Puppeteer;
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
        $puppeteer = new Puppeteer();
        $browser = $puppeteer->launch();
        $page = $browser->newPage();
        $page->goto('https://example.com');
        $page->screenshot(['path' => 'example.png']);
        $browser->close();
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

