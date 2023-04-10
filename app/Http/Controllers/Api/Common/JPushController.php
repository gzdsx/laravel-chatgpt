<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CommonJPush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JPushController extends BaseController
{
    /**
     * @param Request $request
     */
    public function register(Request $request)
    {
        $registrationid = $request->input('registrationid');
        $platform = $request->input('platform', 'ios');

        $model = CommonJPush::whereRegistrationid($registrationid)->firstOrNew();
        $model->platform = $platform;
        $model->registrationid = $registrationid;
        $model->user()->associate(Auth::id());
        $model->save();
    }
}
