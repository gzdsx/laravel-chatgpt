<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Models\UserWithrawalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends BaseController
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserWithrawalLog
     */
    protected function repository()
    {
        return Auth::user()->withdrawals();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apply(Request $request)
    {
        $amount = $request->input('amount');
        if (!$amount) {
            abort(500, 'missing amount value');
        }

        $account = Auth::user()->account()->firstOrNew();
        if ($amount > $account->commission) {
            abort(500, trans('account.insufficient account balance'));
        }

        $account->commission -= $amount;
        $account->save();

        $withdrawal = $this->repository()->make();
        $withdrawal->amount = $amount;
        $withdrawal->state = 0;
        $withdrawal->save();

        return jsonSuccess();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $query = $this->repository();

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->offset($request->input('offset', 0))
                ->take($request->input('count', 10))->orderByDesc('id')->get()
        ]);
    }
}
