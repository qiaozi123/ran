<?php

namespace App\Http\Controllers;

use App\Keyword;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Symfony\Component\DomCrawler\Crawler;

class HomeController extends Controller
{


    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 100;  // 同时并发抓取
    /**
     * The console command description.
     *
     * @var string
     */


    /**
     * Create a new command instance.
     *
     * @return void
     */


    public function index($id)
    {
        $this->url = Keyword::where(['id'=>$id])->get()->toArray();
        $this->mulu = $this->url[0]['mulu'];
//        $this->keyword_id = $this->url[0]['id'];
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

        foreach ($this->alldata as $page=>$item){
            $client = new Client();
            $html =  $client->get($item['url'])->getBody()->getContents();
            $this->page = $page;
            $find = strpos($html,'https://www.pengfu.com/clu');

            if ($find<0){
                echo('第'.$this->alldata[$page]['pagenumber'].'页没有');
                flush();
            }else{
                $crawler = new Crawler();
                $crawler->addHtmlContent($html);

                $arr = $crawler->filter('#content_left > div')->each(function ($node,$i) use ($html) {
                    try
                    {
                        $data['link'] = $node->filter('div > div.f13 > a.c-showurl')->text();
                        $data['jump'] = $node->filter('div > h3 > a')->attr('href');
                        return $data;
                    }
                    catch(\Exception $e)
                    {
                        echo('第'.$this->alldata[$this->page]['pagenumber'].'页排名数据抓取失败');
                    }
                });

                foreach ($arr as $key=>$item){
                    if (!empty($item)){
                        if (strstr($item['link'],$this->mulu)){
                            $rank = Keyword::find($this->alldata[$this->page]['id']);
                            $rank->pagenumber =  $this->alldata[$this->page]['pagenumber']+1;
                            $rank->rank =  $key+1;
                            $bool = $rank->save();
                            if ($bool){
                                echo 'insert database success';
                            }else{
                                echo 'insert database fail';
                            }
                            echo "<script>alert('查询成功!');history.back();</script>";
                            exit();
                        }else{
                            echo ('第'.$this->alldata[$key]['pagenumber'].'页,第'.($key+1).'行没有');
                        }
                    }
                }

            }
        }
    }

    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            return;
        }
    }

}
