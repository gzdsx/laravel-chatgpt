<?php

namespace App\Http\Controllers\Api\OpenAi;

use AlipaySdk\AlipaySdk;
use AlipaySdk\Builder\AlipayUnifiedOrderBuilder;
use App\Http\Controllers\Api\BaseController;
use App\Models\OpenAiDevice;
use App\Models\OpenAiProduct;
use App\Models\UserPrepay;
use App\Models\UserTransaction;
use App\Support\TradeUtil;
use AppleIapVerify\VerifyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    /**
     * @return OpenAiProduct|\Illuminate\Database\Eloquent\Builder
     */
    protected function repository()
    {
        return OpenAiProduct::query()->where('enable', 1);
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
                ->limit($request->input('count', 15))->orderBy('price')->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(Request $request)
    {
        $user = Auth::user();
        $device = OpenAiDevice::whereDeviceId($request->input('device_id'))->first();
        $total = env('AI_FREE_AMOUNT');
        return jsonSuccess([
            'free' => [
                'total' => $total,
                'left' => max(0, $total - $device->amount)
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
        $device = OpenAiDevice::whereDeviceId($request->input('device_id'))->first();
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
        $product = $this->repository()->findOrFail($request->input('id'));

        $order = new AlipayUnifiedOrderBuilder();
        $order->subject = $product->title;
        $order->total_amount = $product->price;
        $order->out_trade_no = TradeUtil::createOutTradeNo();

        $prePay = new UserPrepay();
        $prePay->out_trade_no = $order->out_trade_no;
        $prePay->payable_id = $product->id;
        $prePay->data = $order->toArray();
        $prePay->user()->associate(Auth::user());
        $prePay->save();

        $payStr = AlipaySdk::appPay()
            ->setNotifyUrl(url('notify/alipay/openai/product/paid'))
            ->setBizContent($order->getBizContent())
            ->createPayStr();
        return jsonSuccess(['payStr' => $payStr]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function iapPaid(Request $request)
    {
        $receiptData = $request->input('receiptData');
        $client = new VerifyClient();
        $response = $client->setPassword(env('APPSTORE_SHARE_SECRET'))
            ->setReceiptData($receiptData)
            ->verify(true);

        if ($response->isSuccess()) {
            $transaction = $response->receipt['in_app'][0];
            $product_id = $transaction['product_id'] ?? 0;
            $product = OpenAiProduct::whereIapId($product_id)->first();
            $user = Auth::user();
            $user->is_paid = 1;
            if ($product->type == OpenAiProduct::TYPE_DAYS) {
                if ($user->payment_plan_expires_at) {
                    $user->payment_plan_expires_at = $user->payment_plan_expires_at->addDays($product->value);
                } else {
                    $user->payment_plan_expires_at = now()->addDays($product->value);
                }
            } else {
                $user->payment_plan_points += $product->value;
            }
            $user->save();

            $transaction = new UserTransaction();
            $transaction->type = UserTransaction::TYPE_OUT;
            $transaction->account_type = UserTransaction::ACCOUNT_TYPE_RECHARGE;
            $transaction->pay_type = UserTransaction::PAY_TYPE_APPLE;
            $transaction->pay_at = now();
            $transaction->amount = $product->iap_price;
            $transaction->detail = $product->desc;
            $transaction->out_trade_no = TradeUtil::createOutTradeNo();
            $transaction->data = $transaction;
            $transaction->user()->associate($user);
            $transaction->save();

            return jsonSuccess();
        }

        return jsonFail($response->getErrorMessage(), $response->status);
    }
}
