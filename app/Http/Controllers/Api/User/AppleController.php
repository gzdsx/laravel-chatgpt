<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AppleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $openid = $request->input('openid');
        $connect = UserConnect::findByOpenid($openid);
        if ($connect) {
            $user = $connect->user;
        } else {
            $user = new User();
            $user->email = $request->input('email');
            $user->nickname = $request->input('nickname') ?? 'apple_user_' . Str::random(6);
            $user->save();

            $connect = new UserConnect();
            $connect->openid = $openid;
            $connect->platform = 'apple';
            $connect->nickname = $request->input('nickname');
            $connect->user()->associate($user);
            $connect->save();
        }

        $access_token = $user->createToken('app')->accessToken;
        return jsonSuccess(['access_token' => $access_token]);
    }
}
