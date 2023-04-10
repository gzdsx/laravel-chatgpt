<?php

namespace App\Http\Controllers\Api\OpenAi;


use AlipaySdk\AlipaySdk;
use AlipaySdk\Builder\AlipayUnifiedOrderBuilder;
use App\Http\Controllers\Api\BaseController;
use App\Models\AiDevice;
use App\Models\AiPaymentPlan;
use App\Models\UserPrepay;
use App\Models\UserTransaction;
use App\Support\TradeUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentPlanController extends BaseController
{
    /**
     * @return AiPaymentPlan|\Illuminate\Database\Eloquent\Builder
     */
    protected function repository()
    {
        return AiPaymentPlan::query()->where('enable', 1);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $query = $this->repository();
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($iap_enable = $request->input('iap_enable')) {
            $query->where('iap_enable', $iap_enable);
        }

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->offset($request->input('offset', 0))
                ->limit($request->input('count', 15))->orderBy('value')->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(Request $request)
    {
        $user = Auth::user();
        $device = AiDevice::whereDeviceId($request->input('device_id'))->first();
        $total = env('AI_FREE_AMOUNT');
        return jsonSuccess([
            'free' => [
                'total' => $total,
                'left' => $total - $device->amount
            ],
            'points' => $user->payment_plan_points,
            'days' => $user->payment_plan_expires_at ? $user->payment_plan_expires_at->format('Y-m-d 23:59:59') : '未开通'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFreeTimes(Request $request)
    {
        $device = AiDevice::whereDeviceId($request->input('device_id'))->first();
        return jsonSuccess([
            'total' => 5,
            'rest' => 5 - $device->amount
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAlipayStr(Request $request)
    {
        $plan = $this->repository()->findOrFail($request->input('id'));

        $order = new AlipayUnifiedOrderBuilder();
        $order->subject = $plan->desc;
        $order->total_amount = $plan->price;
        $order->out_trade_no = TradeUtil::createOutTradeNo();

        $prePay = new UserPrepay();
        $prePay->out_trade_no = $order->out_trade_no;
        $prePay->payable_id = $plan->id;
        $prePay->data = $order->toArray();
        $prePay->user()->associate(Auth::user());
        $prePay->save();

        $payStr = AlipaySdk::appPay()
            ->setNotifyUrl(url('notify/alipay/paymentplan/paid'))
            ->setBizContent($order->getBizContent())
            ->createPayStr();
        return jsonSuccess(['payStr' => $payStr]);
    }

    public function iapPaid(Request $request)
    {
        $plan = AiPaymentPlan::whereIapId($request->input('iap_id'))->first();
        $user = Auth::user();
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
        $transaction->pay_type = UserTransaction::PAY_TYPE_APPLE;
        $transaction->pay_at = now();
        $transaction->amount = $plan->iap_price;
        $transaction->detail = $plan->desc;
        $transaction->out_trade_no = TradeUtil::createOutTradeNo();
        $transaction->data = $request->all();
        $transaction->user()->associate($user);
        $transaction->save();

        return jsonSuccess();
    }
}
