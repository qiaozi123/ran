<?php

namespace App\Http\Controllers;

use App\Keyword;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class RankController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function show()
    {
        $keyword = Keyword::all();

        return view('list',compact('keyword'));
    }

    public function youhua($id)
    {
        $keyword = Keyword::where(['id'=>$id])->first();
        $this->mulu =  $keyword->mulu;
        $i=1;
        $d=1;
        while (true){
            $i=$i+1;
            echo '展现量'.$i;
            $url = 'http://www.baidu.com/s?wd='.urlencode($keyword->text).'&pn='.($keyword->pagenumber-1).'0&oq='.urlencode($keyword->text).'&tn=baiduhome_pg&ie=utf-8&rsv_idx=2&rsv_pq=a433278d00092133';
            $client = new Client();
            $html = $client->get($url)->getBody()->getContents();
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
                    echo 'successfully'.'<br>';
                    ob_flush();
                    flush();
                }
            });

            if ($i%10==0){
                foreach ($arr as $item){
                    if (!empty($item)){
                        if (strstr($item['link'],$this->mulu)){
                            $d=$d+1;
                            $uaarr = file(public_path('ua.txt'));
                            $ua = $str = str_replace(array("\r\n", "\r", "\n"), "", array_random($uaarr)); ;
                            $client = new Client();

                            $client->get($item['jump'],[
                                'headers' => [
                                'User-Agent' => $ua,
                                'Accept'     => 'application/json',
                                'X-Foo'      => ['Bar', 'Baz']
                                ]]);
                            echo '点击'.$d.'<br>';
                        }
                    }
                }
            }
            ob_flush();
            flush();
        }
    }


}
