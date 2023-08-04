<?php

namespace App\Http\Controllers\OpenAi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index()
    {

        return $this->view('openai.index');
    }
}
