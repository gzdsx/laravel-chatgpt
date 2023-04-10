<?php/*|--------------------------------------------------------------------------| API Routes|--------------------------------------------------------------------------|| Here is where you can register API routes for your application. These| routes are loaded by the RouteServiceProvider within a group which| is assigned the "api" middleware group. Enjoy building your API!|*///use Illuminate\Support\Facades\Route;Route::group(['namespace' => 'Api'], function () {    //用户认证    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {        Route::post('numberauth.verify', 'NumberController@login');        Route::post('number.login', 'NumberController@login');        Route::post('sms.send', 'SmsController@send');        Route::post('sms.login', 'SmsController@login');        Route::post('apple.login', 'AppleController@login');    });    //用户    Route::group(['namespace' => 'User', 'prefix' => 'user'], function () {        //微信        Route::post('miniprogram.session', 'MiniProgramController@session');        Route::post('miniprogram.login', 'MiniProgramController@login');        Route::post('miniprogram.register', 'MiniProgramController@register');        Route::post('miniprogram.decrypt', 'MiniProgramController@decrypt');        Route::post('miniprogram.phonenumber', 'MiniProgramController@getPhoneNumber');        Route::post('miniprogram.confirm_login', 'MiniProgramController@confirmLogin')->middleware('auth:api');        Route::post('officialaccount.login', 'OfficialAccountController@login');        Route::any('apple.login', 'AppleController@login');        //邀请码        Route::get('invite.getCode', 'CodeController@getCode')->middleware('auth:api');        Route::get('code.getCode', 'CodeController@getCode')->middleware('auth:api');        Route::get('code.getInfo', 'CodeController@getInfo');        Route::group(['middleware' => 'auth:api',], function () {            //用户            Route::get('user.getInfo', 'UserController@getInfo');            Route::get('user.getWebSocketToken', 'UserController@getWebsocketToken');            Route::get('info.getInfo', 'UserInfoController@getInfo');            Route::post('info.updateAvatar', 'UserInfoController@updateAvatar');            Route::post('info.updateName', 'UserInfoController@updateName');            Route::post('password.reset', 'PasswordController@reset');            Route::post('phone.bind', 'PhoneController@bind');            Route::post('email.bind', 'EmailController@bind');            Route::post('feature.updateNickname', 'FeatureController@updateNickname');            Route::post('feature.updateAvatar', 'FeatureController@updateAvatar');            Route::get('feature.getCodeImage', 'FeatureController@getCodeImage');            Route::post('feature.deleteAccount', 'FeatureController@deleteAccount');            Route::get('feature.getProfile', 'FeatureController@getProfile');            Route::post('feature.updateProfile', 'FeatureController@updateProfile');            //资料            Route::get('profile', 'ProfileController@profile');            Route::post('profile.update', 'ProfileController@update');            //账户            Route::get('account', 'AccountController@getAccount');            Route::post('account.resetpassword', 'AccountController@resetPassword');            Route::post('account.transfer', 'AccountController@transfer');            //账单            Route::get('bill.getInfo', 'BillController@getInfo');            Route::get('bill.getList', 'BillController@getList');            //地址            Route::get('address.getInfo', 'AddressController@getInfo');            Route::get('address.getList', 'AddressController@getList');            Route::post('address.create', 'AddressController@create');            Route::post('address.update', 'AddressController@update');            Route::post('address.delete', 'AddressController@delete');            Route::post('address.setDefault', 'AddressController@setDefault');            //通知            Route::get('notification', 'NotificationController@notification');            Route::get('readnotification', 'NotificationController@readNotifications');            Route::get('unreadnotification', 'NotificationController@unreadNotification');            Route::get('notification.getInfo', 'NotificationController@getInfo');            Route::post('notification.delete', 'NotificationController@delete');            //转账            Route::get('transfer.commonly', 'TransferController@commonly');            Route::get('transfer.search', 'TransferController@search');            Route::get('transfer.find', 'TransferController@find');            //提现            Route::get('withdrawal.getList', 'WithdrawalController@getList');            Route::post('withdrawal.apply', 'WithdrawalController@apply');        });    });    //通用    Route::group(['namespace' => 'Common', 'prefix' => 'common'], function () {        //区位        Route::get('district.getInfo', 'DistrcitController@getInfo');        Route::get('district.getList', 'DistrcitController@getList');        Route::get('district.getCityList', 'DistrcitController@getCityList');        //内容模块        Route::get('block.getInfo', 'BlockController@getInfo');        Route::get('block.getList', 'BlockController@getList');        Route::get('block.item.getList', 'BlockItemController@getList');        //素材        Route::get('material.getInfo', 'MaterialController@getInfo')->middleware('auth:api');        Route::get('material.getList', 'MaterialController@getList')->middleware('auth:api');        Route::post('material.upload', 'MaterialController@upload')->middleware('auth:api');        //快递        Route::get('express.getInfo', 'ExpressController@getInfo')->middleware('auth:api');        Route::get('express.getList', 'ExpressController@getList')->middleware('auth:api');        //菜单        Route::get('menu.getInfo', 'MenuController@getInfo');        //客服        Route::get('kefu.getInfo', 'KefuController@getInfo');        Route::get('kefu.getList', 'KefuController@getList');        //链接管理        Route::get('link.getInfo', 'LinkController@getInfo');        Route::get('link.getList', 'LinkController@getList');        //广告管理        Route::get('ad.getInfo', 'AdController@getInfo');        Route::get('ad.getList', 'AdController@getList');        //apns        Route::post('apns/jpush', 'ApnsController@jpush');        //feedback        Route::post('feedback.create', 'FeedbackController@create')->middleware('auth:api');        Route::post('jpush.register', 'JPushController@register')->middleware('auth.wb:api');    });    //页面    Route::group(['namespace' => 'Page', 'prefix' => 'page'], function () {        Route::get('page.getInfo', 'PageController@getInfo');        Route::get('page.getList', 'PageController@getList');        Route::get('category.getList', 'CategoryController@getList');    });    //文章    Route::group(['namespace' => 'Post', 'prefix' => 'post'], function () {        Route::get('item.getInfo', 'ItemController@getInfo');        Route::get('item.getList', 'ItemController@getList');        Route::get('content.getInfo', 'ContentController@getInfo');        Route::get('category.getInfo', 'CategoryController@getInfo');        Route::get('category.getList', 'CategoryController@getList');        Route::get('subscribe.getList', 'SubscribeController@getList')->middleware('auth:api');        Route::post('subscribe.toggle', 'SubscribeController@toggle')->middleware('auth:api');        Route::post('subscribe.delete', 'SubscribeController@delete')->middleware('auth:api');        Route::post('subscribe.query', 'SubscribeController@query')->middleware('auth:api');    });    Route::group(['namespace' => 'Lbs', 'prefix' => 'lbs'], function () {        Route::get('geocode.geo', 'GeoCodeController@geo');        Route::get('geocode.regeo', 'GeoCodeController@regeo');    });    Route::group(['namespace' => 'OpenAi', 'prefix' => 'openai'], function () {        Route::post('completions', 'ChatGptController@completions')->middleware('auth:api');        Route::post('chatgpt.chat', 'ChatGptController@chat')->middleware('auth:api');        Route::post('chatgpt.completions', 'ChatGptController@completions')->middleware('auth:api');        Route::post('chatgpt.edits', 'ChatGptController@edits')->middleware('auth:api');        Route::post('chatgpt.imageEdits', 'ChatGptController@imageEdits')->middleware('auth:api');        Route::post('chatgpt.imageGenerations', 'ChatGptController@imageGenerations')->middleware('auth:api');        Route::post('chatgpt.audioTranscription', 'IndexController@audioTranscription')->middleware('auth:api');        Route::get('paymentplan.getList', 'PaymentPlanController@getList');        Route::get('paymentplan.getDetail', 'PaymentPlanController@getDetail')->middleware('auth:api');        Route::get('paymentplan.getFreeTimes', 'PaymentPlanController@getFreeTimes')->middleware('auth.wb:api');        Route::post('paymentplan.createAlipayStr', 'PaymentPlanController@createAlipayStr')->middleware('auth:api');        Route::post('paymentplan.iapPaid', 'PaymentPlanController@iapPaid')->middleware('auth:api');        Route::post('agent.check', 'AgentController@check')->middleware('auth:api');        Route::post('agent.grant', 'AgentController@grant')->middleware('auth:api');        Route::post('agent.bind', 'AgentController@bind')->middleware('auth:api');        Route::get('agent.getSubUsers', 'AgentController@getSubUsers')->middleware('auth:api');        Route::get('agent.getCommissionList', 'AgentController@getCommissionList')->middleware('auth:api');        Route::get('quickly.getList', 'QuicklyController@getList');        Route::get('quickly.getInfo', 'QuicklyController@getInfo');        Route::get('settings.getSettings', 'SettingsController@getSettings');    });    //获取APP版本    Route::get('app/getversion', function () {        $userAgent = 'time:' . time() . ',' . $_SERVER['HTTP_USER_AGENT'];        if (strpos($userAgent, 'Android') || strpos($userAgent, 'okhttp')) {            return jsonSuccess(['version' => 1.0, 'userAgent' => $userAgent]);        } else {            return jsonSuccess(['version' => 1.0, 'userAgent' => $userAgent]);        }    });    Route::any('reachable', function () {        return jsonSuccess();    });});