<?php

namespace App\Http\Middleware;

use App\Proxy;
use Closure;

class CheckProxy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $_SERVER['HTTP_HOST'];
        $data = Proxy::where(['proxy_host'=>$url])->first();
        if (!empty($data)){
            return $next($request);
        }else{
            return $next($request);
//            dd("您还不是代理，不允许解析到该服务器.请联系管理员");
        }
    }
}
