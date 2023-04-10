<?php

namespace App\Jobs;

use App\Models\AiPaymentPlan;
use App\Models\User;
use App\Models\UserCommission;
use App\Models\UserTransaction;
use App\Support\TradeUtil;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreatePaymentPlanTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $plan;
    public $isIap;
    public $out_trade_no;
    public $extrea;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, AiPaymentPlan $plan, $out_trade_no = null, $extrea = [], $isIap = false)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->isIap = $isIap;
        $this->out_trade_no = $out_trade_no;
        $this->extrea = $extrea;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $plan = $this->plan;
            $user = $this->user;
            $amount = $this->isIap ? $plan->iap_price : $plan->price;

            if ($plan->type == AiPaymentPlan::TYPE_DAYS) {
                if ($user->payment_plan_expires_at) {
                    $user->payment_plan_expires_at = $user->payment_plan_expires_at->addDays($plan->value);
                } else {
                    $user->payment_plan_expires_at = now()->addDays($plan->value);
                }
            } else {
                $user->payment_plan_points += $plan->value;
            }
            $user->is_paid = 1;
            $user->save();

            //创建交易记录
            $transaction = new UserTransaction();
            $transaction->type = UserTransaction::TYPE_OUT;
            $transaction->account_type = UserTransaction::ACCOUNT_TYPE_RECHARGE;
            $transaction->pay_type = UserTransaction::PAY_TYPE_ALIPAY;
            $transaction->pay_at = now();
            $transaction->amount = $amount;
            $transaction->detail = '购买' . $plan->desc;
            $transaction->out_trade_no = $this->out_trade_no ?: TradeUtil::createOutTradeNo();
            $transaction->data = $this->extrea;
            $transaction->user()->associate($user);
            $transaction->save();

            //创建佣金记录
            if ($parent = $user->parent) {
                if ($parent->is_agent) {
                    $commissionAmount = $amount * 0.05;
                    $commission = new UserCommission();
                    $commission->user()->associate($parent);
                    $commission->transaction()->associate($transaction);
                    $commission->amount = $amount * 0.05;
                    $commission->detail = $user->nickname . '购买了' . $plan->desc;
                    $commission->save();

                    $account = $parent->account()->firstOrNew();
                    $account->commission += $commissionAmount;
                    $account->cumulative_commission += $commissionAmount;
                    $account->save();
                }
            }
        });
    }
}
