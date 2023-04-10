<?php

namespace App\Http\Controllers\Api\Auth;


use AlibabaCloud\Dypnsapi\Dypnsapi;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NumberController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function login(Request $request)
    {
        $token = $request->input('token');
        $api = Dypnsapi::v20170525()->getMobile();
        $result = $api
            ->withAccessToken($token)
            ->debug(false) // Enable the debug will output detailed information
            ->request()->toArray();

        if (isset($result['GetMobileResultDTO'])) {
            $phone = $result['GetMobileResultDTO']['Mobile'] ?? null;
            if ($phone) {
                $user = User::where('phone', $phone)->firstOrNew();
                if (!$user->phone) {
                    $user->phone = $phone;
                }

                if (!$user->nickname) {
                    $user->nickname = 'user_' . Str::random(10);
                }

                $user->save();

                return jsonSuccess(['access_token' => $user->createToken('')->accessToken]);
            }
        }

        return jsonFail($result['Message']);

    }
}
