<?php

namespace App\Api\Controllers;

use App\Keyword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RunController extends Controller
{
    public function remote()
    {
        $keyword = Keyword::where(['status'=>1])->get()->toJson();
        return $keyword;
    }
}
