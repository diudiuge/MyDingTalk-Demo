<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{


    /**
     * 获取用户可见的日志模板
     * @return mixed
     */
    public function login(Request $request)
    {
        $data = $request->all();
        $token = md5(time());

        $res = [
            'code' => 0,
            'token' => $token,
            'data' => ['user_id' => '123123', 'name' => 'Leal']
        ];
        return response()->json($res);
    }

    /**
     * 获取用户日志未读数
     * @return mixed
     */
    public function logout()
    {
        // clear Token

        $res = [
            'code' => 0,
            'data' => []
        ];
        return response()->json($res);
    }

    /**
     * 获取用户公告数据
     * @return mixed
     */
    public function getInfo()
    {
        $res = [
            'code' => 0,
            'data' => ['user_id' => '123123', 'name' => 'Leal']
        ];
        return response()->json($res);
    }
}
