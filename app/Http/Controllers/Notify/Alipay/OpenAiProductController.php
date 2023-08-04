<?php

namespace App\Http\Controllers\Notify\Alipay;

use App\Http\Controllers\Controller;
use App\Models\OpenAiProduct;
use App\Models\UserPrepay;
use App\Models\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OpenAiProductController extends Controller
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
            $product = OpenAiProduct::find($prepay->payable_id);
            $user = $prepay->user;
            $user->is_paid = 1;
            if (!$user->member_expires_at || now()->isAfter($user->member_expires_at)) {
                $user->member_expires_at = now();
            }

            if ($product->type == OpenAiProduct::TYPE_A_DAY) {
                $user->member_expires_at->addDays(1);
            }

            if ($product->type == OpenAiProduct::TYPE_A_WEEK) {
                $user->member_expires_at->addDays(7);
            }

            if ($product->type == OpenAiProduct::TYPE_A_MONTH) {
                $user->member_expires_at->addMonths(1);
            }

            if ($product->type == OpenAiProduct::TYPE_A_QUARTER) {
                $user->member_expires_at->addMonths(3);
            }

            if ($product->type == OpenAiProduct::TYPE_A_YEAR) {
                $user->member_expires_at->addYears(1);
            }

            if ($product->type == OpenAiProduct::TYPE_A_LIFE) {
                $user->member_expires_at->addYears(100);
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
