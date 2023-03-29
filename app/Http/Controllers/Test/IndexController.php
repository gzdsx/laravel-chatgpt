<?php

namespace App\Http\Controllers\Test;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Dypnsapi\Dypnsapi;
use App\Http\Controllers\Controller;
use App\Models\AiQuickly;
use App\Models\User;
use App\Models\UserInviteCode;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use SmsSdk\SmsSdk;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        try {
            $code = UserVerify::generateCode();
            $phone = $request->input('phone');
            SmsSdk::aliyun()
                ->setSignName(env('ALIYUN_SIGN_NAME'))
                ->setTemplateId(env('ALIYUN_TEMPLATE_CODE'))
                ->setPhoneNumbers($phone)
                ->setTemplateParam(['seccode'=>$code])
                ->send();
        }catch (\AlibabaCloud\Client\Exception\ServerException $exception){
            return $exception->getMessage();
        }
    }
}

