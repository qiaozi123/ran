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

class NewRank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newrank:start';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新排名获取';

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
        ini_set('memory_limit', '50M');
        $this->url = Keyword::where(['status'=>1])->get();
        echo '开始内存：'.memory_get_usage()/8388608, '';
        foreach ($this->url as $key1=>$item){
            try {
                $ql = QueryList::getInstance();
                $ql->use(Baidu::class);
                $baidu = $ql->baidu(10);
                $searcher = $baidu->search($item->keyword);
                $countPage = 76;  // 获取搜索结果总页数
                for ($page = 1; $page <= $countPage; $page++)
                {
                    $data= $searcher->page($page);
                    foreach ($data as $key=>$url){
                        $reallink = $this->get_real_url($url['link']);
                        if (strpos($reallink,$item->dohost) !== false){
                            if ($page==1){
                                $page=0;
                            }
                            echo "page:".$page.PHP_EOL;
                            $rank[$key1] = $page*10+$key+1;
                            break ;
                        }
                    }
                    if (!empty( $rank[$key1])){
                        break ;
                    }
                }
                if (empty( $rank[$key1])){
                    $rank[$key1] = "760+";
                }
                $ql->destruct();
                $keyword = Keyword::find($this->url[$key1]->id);
                $keyword->new_rank = $rank[$key1];
                $keyword->rank_time = date('H-m-d H:i:s');
                $bool = $keyword->save();
                if ($bool){
                    echo  "任务id:".$item->id.'=>开始内存：'.memory_get_usage()/8388608, '';
                    echo "任务id".$item->id.'执行完毕。 旧排名:'.$keyword->rank.'新排名排名:'.$rank[$key1].PHP_EOL;
                }
            } catch (\Exception $e) {
                echo "任务:".$item->id ."排名抓取错误";
            }

        }
        echo '任务完成:'.date('Y-m-d H:i:s');
    }

    public function get_real_url($data)
    {
        $info = parse_url($data);
        $fp = fsockopen($info['host'], 80,$errno, $errstr, 30);
        fputs($fp,"GET {$info['path']}?{$info['query']} HTTP/1.1\r\n");
        fputs($fp, "Host: {$info['host']}\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        $rewrite = '';
        while(!feof($fp)) {
            $line = fgets($fp);
            if($line != "\r\n" ) {
                if(strpos($line,'Location:') !== false) {
                    $rewrite = str_replace(array("\r","\n","Location: "),'',$line);
                }
            }else {
                break;
            }
        }
        return $rewrite ;
    }


}

