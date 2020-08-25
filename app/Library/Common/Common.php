<?php

namespace App\Library\Common;

use Ramsey\Uuid\Uuid;
class Common
{

    /**
     * CURLf方法
     * @param $url
     * @param bool data
     * @param int $ispost
     * @param int $https
     * @return bool|mixed
     */
    public static function curl($url, $data = false, $ispost = 1, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }

        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($data) {
                if (is_array($data)) {
                    $data = http_build_query($data);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /**
     * 加密算法
     * @param string $user
     * @param string $pwd
     * @param integer $position
     * @return string
     */
    public static function cryptString($user, $pwd, $position = 5)
    {
        $subUser = substr(Crypt::encrypt($user), 0, $position);
        $cryptPwd = md5($pwd);
        return md5(md5($cryptPwd . $subUser));
    }

    /**
     * 加密算法
     * @author jiaxiaofei
     * @param string $pwd
     */
    public static function md($pwd)
    {
        return md5(md5($pwd . 'zuzhanggui') . 'zuzhanggui');

    }

    /**
     * 返回uuid
     * @return string
     */
    public static function getUuid()
    {
        $uuid = Uuid::uuid1();
        return $uuid->getHex();
    }

    /**
     * 返回uuid
     * @return string
     * @author sunhanghao
     */
    public static function getUuid4()
    {
        $uuid = Uuid::uuid4();
        return $uuid->getHex();
    }

    /**
     *  获取本月第一天和最后一天
     * @param $date
     * @return array
     */
    public static function getMonth($date)
    {
        $firstday = date("Y-m-01", strtotime($date));
        $lastday = date("Y-m-d", strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }


    /**
     *  获取上个月第一天和最后一天
     * @param $date
     * @return array
     */
    public static function getlastMonthDays($date)
    {
        $timestamp = strtotime($date);
        $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }


    /**
     *  获取下个月第一天和最后一天
     * @param $date
     * @return array
     */
    public static function getNextMonthDays($date)
    {
        $timestamp = strtotime($date);
        $arr = getdate($timestamp);
        if ($arr['mon'] == 12) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $firstday = $year . '-0' . $month . '-01';
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        } else {
            $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1) . '-01'));
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        }
        return array($firstday, $lastday);
    }


    /**
     * 产生cookie
     * @return string
     * @author
     */
    public static function generateCookie($key)
    {
        if (empty($key)) return false;
        $value = md5(REGISTER_SIGNATURE . $key);
        return cookie($key, $value, COOKIE_LIFETIME);
    }

    /**
     * 用户注册生成随机串
     * @param  int 生成长度
     * @return string 生成的字条串
     */
    public static function random($length)
    {
        $hash = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($chars) - 1;
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }


    /**
     * 无限分类
     *
     * @param   array $arr 待分类的数据
     * @param   int /string   $departmen_id        要找的子节点id
     * @param   int $level 节点等级
     * @return array  数组
     * @author sunhanghao
     */
    public static function getTree($arr, $id = 0, $lev = 0)
    {
        // 获取子孙树
        if (empty($arr)) {
            return false;
        }
        $tree = [];
        foreach ($arr as $v) {
            if ($v['pid'] == $id) {
                $v['level'] = $lev;
                $tree[] = $v;
                $tree = array_merge($tree, self::getTree($arr, $v['id'], $lev + 1));
            }
        }
        return $tree;
    }

    /**
     * 获取所有平台信息
     *
     * @return array
     * @author zhangyuchao
     */
    public static function platformsList()
    {
        // 初始化返回数组
        $data = [];
        // 获取配置文件平台信息
        $platformConfig = config('platformKey');
        if ($GLOBALS['database'] != 'zuzhanggui') {
            $platformConfig = config('saasPlatformKey');
        }
        // 便利平台信息
        foreach ($platformConfig as $key => $value) {
            $data[$key]['value'] = $key;
            $data[$key]['label'] = $value;
        }
        return $data;
    }


    /**
     * 时间戳
     *
     * @return array
     * @author sunchanghao
     */
    public static function getTimeStamp($param = '')
    {
        if (empty($param)) {
            return time();
        }
    }

    /**
     * 添加时，自动加入code、user、添加时间
     *
     * @param $request
     * @param $type
     * @return array|mixed
     * @author gpc
     */
    public static function addCodeUserTime($request, $type = 0)
    {
        // 获取全部请求数据
        $input = $request->all();

        // 追加code、user、添加时间
        $data = [
            'code' => $input['code'],
            'addstaff' => $input['guid'],
            'addtime'  => $_SERVER['REQUEST_TIME']
        ];

        // 判断是否需要追加更新人更新时间
        if ($type == 1) {
            $data = array_merge($data, [
                'updatestaff' => $input['guid'],
                'updatetime'  => $_SERVER['REQUEST_TIME']
            ]);
        }
        return $data;
    }

    /**
     * 修改时，自动加入code、user、修改时间
     *
     * @param $request
     * @return array|mixed
     * @author gpc
     */
    public static function updateCodeUserTime($request)
    {
        // 获取全部请求数据
        $input = $request->all();

        // 追加user、添加时间
        $data = [
            'updatestaff' => $input['guid'],
            'updatetime'  => $_SERVER['REQUEST_TIME']
        ];

        return $data;
    }


    /**
     * 修改时，自动加入code、user、修改时间
     *
     * @param $request
     * @return array|mixed
     * @author sunchanghao
     */
    public static function updateCodeUser($request)
    {
        // 获取全部请求数据
        $input = $request->all();

        // 追加user、添加时间
        $data = [
            'code' => $input['code'],
            'updatestaff' => $input['guid'],
            'updatetime'  => $_SERVER['REQUEST_TIME']
        ];

        return $data;
    }


    /**
     * CURLf方法
     * @param $url
     * @param bool $param
     * @param int $ispost
     * @param int $https
     * @return bool|mixed
     */
    public static function outCurl($host, $url, $param = false, $ispost = 1, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if (empty($host)) {
            return false;
        }
        $url = $host . $url;
        $time = time();
        $param = json_encode($param);
        $data = array(
            'param' => $param,
            'time' => $time,
        );
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($data) {
                if (is_array($data)) {
                    $data = http_build_query($data);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }


    /**
     * CURLf方法
     * @param $url
     * @param bool $data
     * @param int $ispost
     * @param int $https
     * @return bool|mixed
     */
    public static function passCurl($url, $data = false, $ispost = 1, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
//            $headers[] = 'Content-Type: application/json;charset=utf-8';
//            $headers[] = 'Content-Length:' . strlen($data);
//            // 设置header头
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($data) {
                if (is_array($data)) {
                    $data = http_build_query($data);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);
        if ($response === FALSE) {
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /**
     * 亿家请求头
     *
     * @param $method
     * @param $body
     * @return array
     * @author sunchanghao
     */
    public static function headerYFS($method, $body)
    {
        $configYES = config('configYFS');
        $secret = $configYES['secret']; // 秘钥
        if (empty($secret)) {
            app('log')->error('YFS-secret为空,请及时添加');
        }
        $appkey = $configYES['appkey']; // appkey
        $state = mt_rand(100000, 999999); // 无意义字串
        $dateline = date('Y-m-d H:i:s'); // appkey
        $sign = strtoupper(md5($secret . $appkey . $state . $dateline . $body . $method . $appkey . $secret));
        return [
            'appkey' => $appkey,
            'method' => $method,
            'dateline' => $dateline,
            'sign' => $sign,
            'state' => $state,
        ];
    }

    /**
     * 是否有个人权限标识
     *
     * @param $param
     * @param $guid
     * @param $staffName
     * @return array
     * @author sunchanghao
     */
    public static function departmentAuth($param,$guid,$staffName)
    {
        if(empty($param['departmentAuth'])){
            return ['status'=>false,'msg'=>'departmentAuth Not Found'];
        }
        // 是否是来自任务管理页面数据
        switch ($param['departmentAuth']) {
            case 'selfAuth': // 个人权限不做处理
                $param['where'][$staffName] = $guid;
                break;
            case 'authTrue': // 部门受到管控
            case 'allAuth': // 部门不受管控
                break;
            default:
                return ['status' => false, 'msg' => '前台传参缺失或错误'];
        }
        return ['status' => true, 'msg' => $param];
    }
}
