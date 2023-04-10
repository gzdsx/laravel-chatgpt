<?php

namespace App\Http\Controllers\Test;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Dypnsapi\Dypnsapi;
use App\Http\Controllers\Controller;
use App\Jobs\CreatePaymentPlanTransaction;
use App\Models\AiPaymentPlan;
use App\Models\AiQuickly;
use App\Models\User;
use App\Models\UserCode;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use OpenAi\OpenAiClient;
use OpenAi\OpenAiRequestConfig;
use Orhanerday\OpenAi\OpenAi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SmsSdk\SmsSdk;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        return Str::random(12);
    }
}

