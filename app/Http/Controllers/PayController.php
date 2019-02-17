<?php

namespace App\Http\Controllers;

use Pay;
use Cache;
use EasyWeChat;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function index()
    {
        $params = json_decode('{"channelid":"123","channelname":"muyouh5","device":"iphone","nickname":"muyou6","os":"ios","sessionid":"dd032e66695644ba80b1d3866123f193","submit":"123","timestamp":"1550302082","uin":"1","uuid":"123","version":"11.2","sign":"b232c29392c74c8d63009319562d18b3"}', true);
        dd(http_build_query($params));

        $order = [
            'out_trade_no' => time(),
            'body' => 'subject-测试',
            'total_fee'      => '1',
            'openid' => 'oG9AV1hYn-DUJNZJZSRBhvuAA7i4',
        ];

        $result = Pay::wechat()->mp($order);
        dd($result);
    }

    public function code()
    {
    }

    public function login()
    {
        $app = EasyWeChat::officialAccount();
        $oauth = $app->oauth;
        if (empty(Cache::get('wechat_user'))) {
            Cache::put('target_url', 'login', 100);

            return $oauth->redirect();
        }
        // 已经登录过
        $user = Cache::get('wechat_user');
        return $user;
    }

    public function oauthCallback()
    {
        $app = EasyWeChat::officialAccount();
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        Cache::put('wechat_user', $user->toJson(), 100);

        $targetUrl = Cache::get('target_url') ?? '/';

        header('location:'. $targetUrl); // 跳转到 user/profile
    }
}
