<?php

namespace App\Providers;


use AlibabaCloud\Client\AlibabaCloud;
use AlipaySdk\AlipaySdk;
use App\Services\AccountService;
use App\Services\Contracts\AccountServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\OrderService;
use App\Validators\AccountValidator;
use App\Validators\PhoneValidaotr;
use App\Validators\PasswordValidator;
use App\Validators\NickNameValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use OpenAi\OpenAiClient;
use SmsSdk\SmsSdk;

class AppServiceProvider extends ServiceProvider
{
    protected $validators = [
        'pwd' => PasswordValidator::class,
        'phone' => PhoneValidaotr::class,
        'nickname' => NickNameValidator::class,
        'account' => AccountValidator::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidators();

        //注册OPEN
        OpenAiClient::init(env('OPENAI_KEY'), env('OPENAI_ORG_ID'));
        AlipaySdk::register(
            env('ALIPAY_APP_ID'),
            env('ALIPAY_PRIVATE_KEY'),
            env('ALIPAY_PUBLIC_KEY'),
            env('ALIPAY_NOTIFY_URL'),
            env('ALIPAY_RETRUN_URL')
        );
        SmsSdk::registerAliyun(
            env('ALIYUN_ACCESS_KEY_ID'),
            env('ALIYUN_ACCESS_SECRET'),
            env('ALIYUN_SIGN_NAME'),
            env('ALIYUN_TEMPLATE_CODE')
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Carbon\Carbon::setLocale('zh');
        $this->app->singleton(AccountServiceInterface::class, function () {
            return new AccountService();
        });

        $this->app->singleton(OrderServiceInterface::class, function () {
            return new OrderService();
        });
    }

    /**
     * register custom validators
     */
    protected function registerValidators()
    {
        foreach ($this->validators as $rule => $validator) {
            Validator::extend($rule, "{$validator}@validate");
        }
    }
}
