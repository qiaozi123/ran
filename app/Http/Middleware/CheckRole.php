<?php

namespace App\Http\Middleware;

use App\Proxy;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

// 检查用户角色
class CheckRole
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
        return $next($request);
    }
}
