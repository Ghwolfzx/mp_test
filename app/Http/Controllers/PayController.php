<?php

namespace App\Http\Controllers;

use Pay;
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

    public function login()
    {
        $app = EasyWeChat::officialAccount();
        $response = $app->oauth->scopes(['snsapi_userinfo'])
                          ->redirect();
        dd($response);
        return $response;
    }
}
