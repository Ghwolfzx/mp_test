<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Cache;
use EasyWeChat;
use App\Models\WeixinUser;
use Illuminate\Http\Request;

class MiniProgramController extends Controller
{
    public function login(Request $request)
    {
        $code = $request->code ?? '';
        if (empty($code)) {
            return ['status' => 'false', 'msg' => 'code is null'];
        }
        // $app = EasyWeChat::miniProgram();
        // $auth = $app->auth->session($request->code);
        $auth = json_decode('{"session_key":"0+TrofTZ0w3/1aVVwv9EoA==","openid":"oKV615OrSQTAh8H5vqtFWGm1Ob_4","unionid":"oiBRlwIP_wv8il7e23pF7cGceo6Y"} ', true);
        Log::debug('miniProgram_user', $auth);

        if (isset($auth['errcode']) && $auth['errcode'] != 0) {
            // Cache::put('miniProgram_user_error', $auth, 100);
            Log::error('miniProgram_user', $auth);
            return ['status' => 'false', 'msg' => '登录异常'];
        }
        // 创建或登录账号
        $user = WeixinUser::firstOrCreate([
            'unionid' => $auth['unionid']
        ]);

        if ($user->session_key != $auth['session_key']) {
            $user->mini_openid = $auth['openid'];
            $user->session_key = $auth['session_key'];
            $user->save();
        }

        // Cache::put('miniProgram_user', $auth, 100);
        return ['status' => 'true', 'userid' => $user->id, 'token' => md5($user->session_key . 'muyou_mini_program')];
    }

    public function checkLogin(Request $request)
    {
        $userid = $request->input('userid');
        $token = $request->input('token');

        if (!$userid || !$token) {
            return ['status' => 'false', 'msg' => '参数错误'];
        }

        $user = WeixinUser::find($userid);

        if (!$user) {
            return ['status' => 'false', 'msg' => '用户不存在'];
        }

        if (md5($user->session_key . 'muyou_mini_program') == $token) {
            return ['status' => 'true'];
        }

        return ['status' => 'false', 'msg' => '异常'];
    }
}
