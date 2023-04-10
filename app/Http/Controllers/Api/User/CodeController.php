<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CodeController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCode()
    {
        if ($model = Auth::user()->code) {
            $code = $model->code;
        } else {
            $code = UserCode::generateCode();
            Auth::user()->code()->create(['code' => $code]);
        }

        return jsonSuccess($code);
    }

    public function getInfo(Request $request)
    {
        $code = $request->input('code');
        if ($model = UserCode::where('code', str_replace('U_', '', $code))->first()) {
            if ($model->user) {
                return jsonSuccess($model->user->only(['uid', 'nickname', 'avatar']));
            }
        }

        return jsonFail('该二维码已失效');
    }
}
