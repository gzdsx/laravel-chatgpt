<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelationController extends BaseController
{
    public function getParent(Request $request)
    {
        return jsonSuccess(Auth::user()->parent);
    }

    public function bindCode(Request $request)
    {
        $user = Auth::user();
        if ($parent = $user->parent) {
            return jsonFail('您已绑定过其他邀请码');
        } else {
            $model = UserCode::whereCode($request->input('code'))->first();
            if ($model) {
                if ($model->user) {
                    if ($model->uid == $user->uid) {
                        return jsonFail('不能绑定自己的邀请码');
                    }
                    $user->parent()->associate($model->user);
                    $user->free_plan_times += 5;
                    $user->save();

                    $model->user->free_plan_times += 5;
                    $model->user->save();

                    return jsonSuccess();
                }
            }

            return jsonFail('邀请码已失效');
        }
    }
}
