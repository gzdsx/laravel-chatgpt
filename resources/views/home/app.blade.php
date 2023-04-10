<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>下载APP</title>
    <meta name="render" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link href="{{asset('images/common/favicon.png')}}" rel="icon">
    <script src="{{asset('lib/clipboard.min.js')}}"></script>
    <script src="{{asset('lib/jquery.min.js')}}"></script>
    <style type="text/css">
        html, body {
            background-color: #1690C9;
            padding: 30px 0;
            margin: 0;
            color: #fff;
        }

        .icon-container {
            display: block;
            align-self: center;
            align-items: center;
            text-align: center;
        }

        .icon-container .icon {
            width: 80px;
            height: 80px;
            display: inline-block;
            border-radius: 10px;
        }

        .version {
            font-size: 14px;
            text-align: center;
            padding: 10px;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            font-size: 16px;
            text-align: center;
            color: #fff;
            border-radius: 10px;
            background-color: #00C858;
            text-decoration: none;
            cursor: pointer;
        }

        .button-container {
            display: block;
            margin: 30px 20px;
            text-align: center;
        }

        .title {
            font-size: 20px;
            padding: 30px 0;
            text-align: center;
        }

        .section-title {
            font-size: 16px;
            text-align: center;
        }

        .section {
            text-align: center;
            margin: 10px 20px;
        }

        .section img {
            display: block;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="icon-container">
    <img src="{{asset('images/60.png')}}" class="icon" alt="">
</div>
<div class="version">Chat助手 v1.3.0</div>
@if (is_wechat())
    <div class="title">下载方法</div>
    <div class="section-title">由于微信的限制，请在浏览器中打开此链接或者直接在AppStore搜索Chat助手</div>
    <div class="section"><img src="{{asset('images/app/demo1.jpg')}}" alt=""></div>
@else
    @if(is_ios())
        <div class="button-container">
            <a class="button" href="itms-apps://itunes.apple.com/app/id6446398690?l=zh&mt=8">去App Store下载</a>
        </div>
    @else
        <div class="button-container">
            <a class="button" href="https://apps.gzdsx.cn/apk/chatapp.apk">立即下载</a>
        </div>
    @endif
@endif

<script>
    function openAppsore() {
        window.open('https://apps.apple.com/cn/app/id6446398690?l=zh&mt=8');
    }

    $("#appstore").on('click', function () {
        window.open('itms-apps://itunes.apple.com/app/id6446398690');
    });
</script>
</body>
</html>
