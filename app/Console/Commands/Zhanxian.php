<?php

namespace App\Console\Commands;

use App\Keyword;
use App\RankUpdate;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

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
    private $concurrency    = 10;  // 同时并发抓取
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
        $newsUrl = 'https://m.toutiao.com/i6546884151050502660/';

        $html = Browsershot::url($newsUrl)
            ->windowSize(480, 800)
            ->userAgent('Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Mobile Safari/537.36')
            ->mobile()
            ->touch()
            ->bodyHtml();
        dd($html);
        \Log::info($html);
    }



}

