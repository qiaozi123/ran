<?php

namespace App\Api\Controllers;

use App\Keyword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeywordController extends Controller
{
    public function index()
    {
        $keyword =  Keyword::all();
        return $keyword;
     }
}
