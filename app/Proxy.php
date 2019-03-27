<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Proxy extends Model
{
    protected $table ='proxy';

    public static function check()
    {
        $url = $_SERVER['HTTP_HOST'];
        $data = Proxy::where(['proxy_host'=>$url])->first();
        return $data;
    }
}
