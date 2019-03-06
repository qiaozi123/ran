<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchEngines extends Model
{
     protected $table = 'searchengines';

    public static function getsearchengines($id)
    {
        return  SearchEngines::find($id);
     }
}
