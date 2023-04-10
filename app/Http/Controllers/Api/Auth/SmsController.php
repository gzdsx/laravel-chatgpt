<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SmsSdk\SmsSdk;
use AlibabaCloud\Client\Exception\ServerException;

class SmsController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        try {
            $code = UserVerify::generateCode();
            $phone = $request->input('phone');
            SmsSdk::aliyun()
                ->setPhoneNumbers($phone)
                ->setTemplateParam(['seccode' => $code])
                ->send();

            $model = UserVerify::query()->where('phone', $phone)->firstOrNew();
            $model->phone = $phone;
            $model->code = $code;
            $model->token = Str::random(100);
            $model->expires_at = now()->addSeconds(1800);
            $model->save();

            return jsonSuccess([
                'token' => $model->token,
                'expires_at' => $model->expires_at
            ]);
        } catch (ServerException $exception) {
            return jsonFail($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $code = $request->input('code');
        $phone = $request->input('phone');
        $token = $request->input('token');

        $model = UserVerify::where(['phone' => $phone, 'code' => $code])->first();
        if ($model) {
            if ($model->token == $token) {
                $user = User::query()->where('phone', $phone)->firstOrNew();
                if (!$user->phone) {
                    $user->phone = $phone;
                }

                if (!$user->nickname) {
                    $user->nickname = 'user_' . Str::random(10);
                }

                $user->save();

                return jsonSuccess(['access_token' => $user->createToken('app')->accessToken]);
            }
        }

        return jsonFail('验证码输入错误');
    }
}
