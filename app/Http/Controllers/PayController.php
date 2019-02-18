<?php

namespace App\Http\Controllers;

use Pay;
use Log;
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

    public function code(Request $request)
    {
        $code = $request->code ?? '';
        if (empty($code)) {
            return ['status' => 'false', 'msg' => 'code is null'];
        }
        $app = EasyWeChat::miniProgram();
        $oauth = $app->oauth->session($request->code);
        Log::debug('miniProgram_user' . $oauth->toJson());

        if ($oauth->errcode != 0) {
            Log::error('miniProgram_user' . $oauth->toJson());
            return ['status' => 'false', 'msg' => '登录异常'];
        }
        Cache::put('miniProgram_user', $oauth->toJson(), 100);
        return ['status' => 'true', 'userid' => 1, 'token' => md5(1)];
    }

    public function login()
    {
        /*$user = '{
            "id": "oG9AV1hYn-DUJNZJZSRBhvuAA7i4",
            "name": "任中兴",
            "nickname": "任中兴",
            "avatar": "http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTLeVGibjfRekLv2WiaLwSmjnE4wUyRUaYBhCPp1XJzBJxPPd4lgqh6NlqOhzL1KFQhcH1tzm7YKibmdg\/132",
            "email": null,
            "original": {
                "openid": "oG9AV1hYn-DUJNZJZSRBhvuAA7i4",
                "nickname": "任中兴",
                "sex": 1,
                "language": "zh_CN",
                "city": "",
                "province": "北京",
                "country": "中国",
                "headimgurl": "http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTLeVGibjfRekLv2WiaLwSmjnE4wUyRUaYBhCPp1XJzBJxPPd4lgqh6NlqOhzL1KFQhcH1tzm7YKibmdg\/132",
                "privilege": [],
                "unionid": "oiBRlwIM_-iUSnPhy8sSapyxaY3M"
            },
            "token": "18_sS-kvhHRBnZq51Z6Psfbew3qEJtm-3p7KZNkpJBBWqSqi3bfsO7vpv1GLwaWfg_BkR8wPBzDoGPITx94eVdhAQ",
            "provider": "WeChat"
        }';*/
        $app = EasyWeChat::officialAccount();
        $oauth = $app->oauth;
        if (empty(Cache::get('wechat_user'))) {
            Cache::put('target_url', 'login', 100);

            return $oauth->redirect();
        }
        // 已经登录过
        $user = Cache::get('wechat_user');
        Log::debug('wechat_user' . $user);
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
