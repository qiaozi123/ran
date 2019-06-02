<?php

namespace App\Console\Commands;

use App\Keyword;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use QL\QueryList;
use QL\Ext\Baidu;

class InitRankTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:start';

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
        set_time_limit(0);
        ini_set('memory_limit', '20M');
        $bool = Keyword::where([])->update(['today' => date('Y-m-d'),'today_status'=>0,'has_click'=>0]);
        echo "每日点击初始化任务已经结束。影响任务数量:".$bool;
    }



}

