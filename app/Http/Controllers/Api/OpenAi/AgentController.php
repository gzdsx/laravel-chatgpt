<?php

namespace App\Http\Controllers\Api\OpenAi;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function grant(Request $request)
    {
        $user = Auth::user();
        $user->is_agent = 1;
        $user->save();

        return jsonSuccess();
    }

    public function check()
    {
        return jsonSuccess(Auth::user()->is_agent == 1);
    }

    public function getSubUsers(Request $request)
    {
        $query = Auth::user()->subUsers();

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->offset($request->input('offset', 0))
                ->take($request->input('count', 10))->orderByDesc('uid')->get()
        ]);
    }

    public function getCommissionList(Request $request)
    {
        $query = Auth::user()->commissions();

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->offset($request->input('offset', 0))
                ->take($request->input('count', 10))->orderByDesc('id')->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bind(Request $request)
    {
        $uid = $request->input('uid');
        if ($uid === Auth::id()) {
            return jsonFail('不能绑定自己');
        }

        if (Auth::user()->parent) {
            return jsonFail('您已经绑定过代理商，不能重复绑定');
        }

        $user = Auth::user();
        $user->parent()->associate($uid);
        $user->save();

        return jsonSuccess();
    }
}
