<?php

namespace App\Http\Controllers;

use Log;
use Cache;
use EasyWeChat;
use Illuminate\Http\Request;

class MiniProgramController extends Controller
{
    public function login(Request $request)
    {
        $code = $request->code ?? '';
        if (empty($code)) {
            return ['status' => 'false', 'msg' => 'code is null'];
        }
        $app = EasyWeChat::miniProgram();
        $auth = $app->auth->session($request->code);
        Log::debug('miniProgram_user', $auth);

        if ($auth['errcode'] != 0) {
            Cache::put('miniProgram_user_error', $auth, 100);
            Log::error('miniProgram_user', $auth);
            return ['status' => 'false', 'msg' => '登录异常'];
        }
        Cache::put('miniProgram_user', $auth, 100);
        return ['status' => 'true', 'userid' => 1, 'token' => md5(1)];
    }
}
