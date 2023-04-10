<?php

namespace App\Http\Controllers\Notify\Alipay;

use App\Http\Controllers\Controller;
use App\Models\AiPaymentPlan;
use App\Models\UserPrepay;
use App\Models\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentPlanController extends Controller
{
    const SUCCESS = 'success';
    const FAIL = 'fail';

    /**
     * @param Request $request
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function paid(Request $request)
    {
        Storage::put('alipay', json_encode($request->all()));
        $out_trade_no = $request->input('out_trade_no');
        if ($prepay = UserPrepay::whereOutTradeNo($out_trade_no)->first()) {
            $plan = AiPaymentPlan::find($prepay->payable_id);
            $user = $prepay->user;
            $user->is_paid = 1;
            if ($plan->type == AiPaymentPlan::TYPE_DAYS) {
                if ($user->payment_plan_expires_at) {
                    $user->payment_plan_expires_at = $user->payment_plan_expires_at->addDays($plan->value);
                } else {
                    $user->payment_plan_expires_at = now()->addDays($plan->value);
                }
            } else {
                $user->payment_plan_points += $plan->value;
            }
            $user->save();

            $transaction = new UserTransaction();
            $transaction->type = UserTransaction::TYPE_OUT;
            $transaction->account_type = UserTransaction::ACCOUNT_TYPE_RECHARGE;
            $transaction->pay_type = UserTransaction::PAY_TYPE_ALIPAY;
            $transaction->pay_at = now();
            $transaction->amount = $request->input('total_amount');
            $transaction->detail = $request->input('subject');
            $transaction->out_trade_no = $prepay->out_trade_no;
            $transaction->data = $request->all();
            $transaction->user()->associate($user);
            $transaction->save();
        }
        return static::SUCCESS;
    }
}
