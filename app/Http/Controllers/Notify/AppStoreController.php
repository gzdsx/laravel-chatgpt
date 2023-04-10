<?php

namespace App\Http\Controllers\Notify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppStoreController extends Controller
{
    public function prod(Request $request)
    {

    }

    public function dev(Request $request)
    {
        Storage::put('appstore', json_encode($request->all()));
        return jsonSuccess();
    }
}
