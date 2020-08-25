<?php

namespace App\Library\ApiSecurity;


use App\Library\Common\Common;
use App\Library\Redis\RedisTool;

class Verify
{
    protected $redis;

    public function __construct(RedisTool $redisTool)
    {
        $this->redis = $redisTool;
    }
    /**
     * 通用接口
     * @param $request
     * @return bool|string
     */
    public function common($request)
    {
        if ($request->all()) {
            $data = $request->all();
            $ckTime = $this->checkTime($data['time']);
            if (!$ckTime) return 'SN002';
            if (!isset($data['guid'])) return 'SN004';
            // 根据版本设计不同的验证
            switch ($data['version']) {
                case 1:
                    $temp = $this->checkCommon_v1($request);
                    break;
                default:
                    $temp = $this->checkCommon_v1($request);
                    break;
            }
            if ($temp) {
                return "SN200";
            }
            return "SN005";
        }
        return false;
    }


    /**
     * 非通用接口
     * @param $request
     * @return bool|string
     */
    public function proprietary($request)
    {
        if ($request->all()) {
            $data = $request->all();
            $ckTime = $this->checkTime($data['time']);
            if (!$ckTime) return 'SN002';
            if (!isset($data['guid'])) return "SN004";
            // 根据版本设计不同的验证
            switch ($data['version']) {
                case 1:
                    $temp = $this->checkProprietary_v1($request);
                    break;
                default:
                    $temp = $this->checkProprietary_v1($request);
                    break;
            }
            if ($temp) {

                switch ($temp) {
                    case 'SN007':
                        return 'SN007';
                        break;
                    case 'SN008':
                        return 'SN008';
                        break;
                    case 'SN009':
                        return 'SN009';
                        break;
                    default:
                        return 'SN200';
                        break;
                }
            }
            return "SN005";
        }
        // No access! 没有添加签名验证
        return false;
    }

    /**
     * 时间验证
     * @param $time
     * @return bool|string
     */
    public function checkTime($time)
    {
        $Time_difference = abs(time() - $time);
        if ($Time_difference > 30) {
            return false;
        }
        return true;
    }


    /**
     * 通用接口验证
     * @param $request
     * @return bool
     */
    private function checkCommon_v1($request)
    {
        $data = $request->all();
        $path = '/'.$request->path();
        $time = $data['time'];
        $guid = '1';
        $param = $data['param'];
        $cryptToken = "HelloWorld";
        $signature = md5($path . $time . $guid . $param . $cryptToken);
        if ($signature != $data['signatures']) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 非通用接口验证
     * @param $request
     * @return bool
     */
    private function checkProprietary_v1($request)
    {

        $data = $request->all();

        $path = '/'.$request->path();
        // 获取参数
        $param = $data['param'];
        // 获取用户ID
        $guid = $data['guid'];
        // 获取签名
        $signature = $data['signatures'];
        // 获取提交时间
        $time = $data['time'];
        // 操作平台
        $platform = $data['platform'] ?? '1';
        // 默认平台走 erp
        $platformName = '';
        if ($platform == 8) {
            $platformName = 'app';
        }

        // 获取用户信息
        $user = $this->user($guid, $platformName);

        if (!$user) return 'SN007';  // 用户不存在
        // TOKEN 过期
        if ($platform != 8) {
            if (time() > $user['token_time']) return 'SN009';
        } else {
            if (time() > $user['app_tokentime']) return 'SN009';
        }

        if($platform > 2 && $platform < 8){
            $token = $user['xcx_token'];
        } elseif ($platform == 8) {
            $token = $user['app_token'];
        } else{
            $token = $user['token'];
        }
        $hashs = [
            [0, 4, 1, 17, 22, 29],
            [2, 8, 19, 23, 30, 31],
            [4, 12, 31, 1, 5, 10],
            [6, 16, 31, 10, 12, 18],
            [8, 20, 12, 18, 25, 20],
            [10, 24, 17, 27, 1, 22],
            [12, 28, 13, 19, 20, 21],
            [14, 0, 20, 29, 18, 20]
        ];
        $strs = substr($token, 2, 1);
        $strs .= substr($token, 5, 1);
        $strs .= substr($token, 8, 1);
        $code = hexdec($strs);
        $str1 = $code % 8;
        $arr = $hashs["$str1"];
        $m = null;
        foreach ($arr as $v) {
            $m .= substr($token, $v, 1);
        }

        $str = md5($path . $time . $guid . $param . $m);
        if ($signature == $str) {
            return 'SN200';
        } else {
            return false;
        }
    }

    /**
     * 根据guid 获取token
     *
     * @param $guid
     * @param $platform
     * @return bool|int
     * @author zhangyuchao
     */
    public function user($guid, $platform = '')
    {
        // 拼接获取token的key
        $redisKey = config('datarediskey')['tokenInfo'].$guid;
        if ($platform == 'app') {
            $redisKey = config('datarediskey.app_token_prefix').$guid;
        }
        // 获取缓存里的token
        $data = $this->redis->hashGetAll($redisKey);
        // 判断是否获取
        if($data){
            $this->redis->expire($redisKey, 3600*24);
            return $data;
        }
        // 没有获取到重新获取
        $resultJson = Common::curl('/erp/getToken',['user_id' => $guid]);
        if ($resultJson) {
            $result = json_decode($resultJson,1);
            if($result['ServerNo'] == 200) {
                // 重新存入的redis中
                $this->redis->hashMSet($redisKey,$result['ResultData']);
                $this->redis->expire($redisKey, 3600*24);
                // 返回
                return $result['ResultData'];
            }
        }
        // 返回错误
        return false;
    }

    /**
     * 开放接口签名验证
     *
     * @param $request
     * @return string
     * @author cailiang
     */
    public function openAuthorizeCheck($request)
    {
        // 必要参数验证
        if (!$request->header('Authorization') || strlen($request->header('Authorization')) != 60) {
            return 'SN005';  // 权限验证失败
        }

        $authorization = $request->header('Authorization');

        // 获取参数
        $input = $request->all();
        $partnerId = substr($authorization, 0, 16); // partner_id
        $time = substr($authorization, 16, 10);  // 时间戳
        $version = substr($authorization, 26, 2); // version
        $sign = substr($authorization, 28, 32); // token

        if ($version != '01') {
            return 'SN003';  // 版本号错误
        }

        // 1. 时效验证
        $ckTime = self::checkTime($time);
        if (!$ckTime) return 'SN002';  // 请求超时

        // 2. 用户权限验证
        $ckPartner = self::checkPartner($partnerId, $request->path());
        if (!$ckPartner) return 'SN010'; // 合作伙伴 权限不足

        // 3. token 验证
        // {php转json的时候中文会变成unicode， php不会， 会导致加密的秘钥对不上.  JSON_UNESCAPED_UNICODE 解决这个问题，但是对接的时候要注意}
        // 空参数的转json  js 和php 不对应
        if (!empty($input)) {
            ksort($input);
            $key = md5(json_encode($input, JSON_UNESCAPED_UNICODE) . $ckPartner['partner_secret'] . $time);
        } else {
            $key = md5('{}' . $ckPartner['partner_secret'] . $time);
        }

        if ($key != $sign) return 'SN005'; // // 权限验证失败

        // 4. 将 $partnerId 塞入 参数
        $request->merge(['partner_id' => $ckPartner['partner_id']]);

        return 'SN200'; // 请求成功
    }

    /**
     * 合作伙伴权限验证
     *
     * @param $partnerId
     * @param $path
     * @return bool|static
     * @author cailiang
     */
    public function checkPartner($partnerId, $path)
    {
        // TODO 这边数据请求 数据库/redis 获取
        $resultJson = Common::curl('/openApi/getPartner');
        $result = json_decode($resultJson,true);
        if (empty($result) || $result['ServerNo'] != 200) {
            return false;
        }

        // 合作伙伴 授权数据
        $partnerAuth = $result['ResultData']['partnerAuth'];
        // 合作伙伴 权限数据
        $auth = $result['ResultData']['auth'];

        $partner = collect($partnerAuth)
                        ->where('partner_id', '=', $partnerId)
                        ->first();
        if (empty($partner)) {
            return false;
        }

        if (empty($auth[$partnerId]) || !in_array($path, $auth[$partnerId])) {
            return false;
        }

        return $partner;
    }

    /**
     * 开放接口签名验证
     *
     * @param $request
     * @return string
     * @author sunchanghao
     */
    public function openThirdSaleAfterCheck($request)
    {
        // 必要参数验证
        if (!$request->header('Authorization') || strlen($request->header('Authorization')) != 76) {
            return 'SN005';  // 权限验证失败
        }

        $authorization = $request->header('Authorization');

        // 获取参数
        $input = $request->all();
        $partnerId = substr($authorization, 0, 32); // partner_id
        $time = substr($authorization, 32, 10);  // 时间戳
        $version = substr($authorization, 42, 2); // version
        $sign = substr($authorization, 44, 32); // token

        if ($version != '02') {
            return 'SN003';  // 版本号错误
        }

        // 1. 时效验证
        $ckTime = self::checkTime($time);
        if (!$ckTime) return 'SN002';  // 请求超时

        // 2. 用户权限验证
        $ckPartner = self::checkThird($partnerId);

        if (!$ckPartner) return 'SN010'; // 合作伙伴 权限不足
        // 3. token 验证
        // {php转json的时候中文会变成unicode， php不会， 会导致加密的秘钥对不上.  JSON_UNESCAPED_UNICODE 解决这个问题，但是对接的时候要注意}
        // 空参数的转json  js 和php 不对应
        if (!empty($input)) {
            ksort($input);
            $key = md5(json_encode($input, JSON_UNESCAPED_UNICODE) . $ckPartner['secret'] . $time);
        } else {
            $key = md5('{}' . $ckPartner['secret'] . $time);
        }
        if ($key != $sign) return 'SN005'; // // 权限验证失败

        // 4. 将 $partnerId 塞入 参数
        $request->merge(['third_id' => $ckPartner['third_id']]);

        return 'SN200'; // 请求成功
    }

    /**
     * 合作伙伴权限验证
     *
     * @param $thirdId
     * @return bool|static
     * @author sunchanghao
     */
    public function checkThird($thirdId)
    {

        // TODO 这边数据请求 数据库/redis 获取
        $resultJson = Common::curl('/openApi/third/getThirdAfterSale');
        $result = json_decode($resultJson,true);
        if (empty($result) || $result['ServerNo'] != 200) {
            return false;
        }

        // 合作伙伴 授权数据
        $partnerAuth = $result['ResultData']['partnerAuth'];
        $partner = collect($partnerAuth)
            ->where('third_id', '=', $thirdId)
            ->first();
        if (empty($partner)) {
            return false;
        }


        return $partner;
    }
}
