<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Msg;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Key;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function show()
    {
        $userid = Auth::user()->id;
        $pwhere = ['userid'=>$userid,'type'=>1];
        $pcount =  Keyword::where($pwhere)->get()->count();
        $mwhere = ['userid'=>$userid,'type'=>2];
        $mcount =  Keyword::where($mwhere)->get()->count();
        $msg = Msg::all();
        return view('welcome',compact('pcount','mcount','msg'));
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

//            if ($i%10==0){
//                foreach ($arr as $item){
//                    if (!empty($item)){
//                        if (strstr($item['link'],$this->mulu)){
//                            $d=$d+1;
//                            $uaarr = file(public_path('ua.txt'));
//                            $ua = $str = str_replace(array("\r\n", "\r", "\n"), "", array_random($uaarr)); ;
//                            $client = new Client();
//                            $ip = self::getip();
//                            $client->get($item['jump'],[]);
//                            $client->request('GET', $item['jump'], [
//                                'proxy' => $ip->IP,
//                                'headers' => [
//                                    'User-Agent' => $ua,
//                                    'Accept'     => 'application/json',
//                                    'X-Foo'      => ['Bar', 'Baz']
//                                ]
//                            ]);
//
//                            echo '点击'.$d.'<br>';
//                        }
//                    }
//                }
//            }
            ob_flush();
            flush();
        }
    }

    public function getip()
    {
        if (empty($this->ipdata)){
            $ipurl = 'http://ip.11jsq.com/index.php/api/entry?method=proxyServer.generate_api_url&packid=2&fa=0&fetch_key=&qty=20&time=1&pro=&city=&port=1&format=json&ss=5&css=&ipport=1&et=1&pi=1&co=1&dt=1&specialTxt=3&specialJson=';
            $ipjson = file_get_contents($ipurl);
            $ipdata = json_decode($ipjson)->data;
        }

        foreach ($ipdata as $key=>$item){
            if (time() > strtotime($item->ExpireTime)){
                unset($ipdata[$key]);
                $this->ipdata = array_values($ipdata);
            }
        }

        return array_random($ipdata);



    }


    public function click($id)
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

//            if ($i%10==0){
//                foreach ($arr as $item){
//                    if (!empty($item)){
//                        if (strstr($item['link'],$this->mulu)){
//                            $d=$d+1;
//                            $uaarr = file(public_path('ua.txt'));
//                            $ua = $str = str_replace(array("\r\n", "\r", "\n"), "", array_random($uaarr)); ;
//                            $client = new Client();
//                            $ip = self::getip();
//                            $client->get($item['jump'],[]);
//                            $client->request('GET', $item['jump'], [
//                                'proxy' => $ip->IP,
//                                'headers' => [
//                                    'User-Agent' => $ua,
//                                    'Accept'     => 'application/json',
//                                    'X-Foo'      => ['Bar', 'Baz']
//                                ]
//                            ]);
//
//                            echo '点击'.$d.'<br>';
//                        }
//                    }
//                }
//            }
            ob_flush();
            flush();
        }
    }


}
