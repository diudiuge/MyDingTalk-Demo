<?php

namespace App\Http\Controllers;
use Cache;

class AttendanceController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 企业考勤排班详情
     * @return mixed
     */
    public function schedules()
    {
        $date = '2019-06-24';
        $res = $this->Ding->attendance->schedules($date, $offset = null, $size = null);
        return $res;
    }

    /**
     * 企业考勤排班详情
     * @return mixed
     */
    public function groups()
    {
        $res = $this->Ding->attendance->groups($offset = null, $size = null);
        return $res;
    }

    /**
     * 获取用户考勤组
     * @return mixed
     */
    public function userGroup()
    {
        $userId = '0801342469687788'; // 我的Id
        $res = $this->Ding->attendance->userGroup($userId);
        return $res;
    }

    /**
     * 获取用户考勤组
     * @return mixed
     */
    public function recodes()
    {
        $userId = '0801342469687788'; // 我的Id
        $params = [
            'userIds' => [$userId],
            'checkDateFrom' => '2019-06-23 00:00:00',
            'checkDateTo' => '2019-06-24 22:00:00',
            'isI18n' => false
        ];
        $res = $this->Ding->attendance->records($params); // userCheckTime 打卡时间
        return $res;
    }

    /**
     * 获取用户考勤组
     * @return mixed
     */
    public function results()
    {
        $userId = '0801342469687788'; // 我的Id
        $params = [
            'workDateFrom' => '2019-06-24 00:00:00',
            'workDateTo' => '2019-06-25 00:00:00',
            'userIdList' => [$userId],    // 必填，与offset和limit配合使用
            'offset' => 0,    // 必填，第一次传0，如果还有多余数据，下次传之前的offset加上limit的值
            'limit' => 10,     // 必填，表示数据条数，最大不能超过50条
        ];

        $res = $this->Ding->attendance->results($params);
        return $res;
    }

    /**
     * 获取请假时长 (鸡肋)
     * @return mixed
     */
    public function duration()
    {
        $userId = '0801342469687788'; // 我的Id
        $from = '2019-06-24 09:00:00';
        $to = '2019-06-25 09:00:00';

        $res = $this->Ding->attendance->duration($userId, $from, $to);
        return $res;
    }

    /**
     * 查询请假状态
     * @return mixed
     */
    public function status()
    {
        $params = [
            'userid_list' => '0801342469687788',
            'start_time' => 1560873600000,
            'end_time' => 1560960000000,
            'offset' => 0,
            'size' => 10
        ];
        $res = $this->Ding->attendance->status($params);
        return $res;
    }
}
