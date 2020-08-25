<?php

namespace App\Http\Controllers;

class CheckinController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 获取部门用户签到记录
     * @return mixed
     */
    public function checkinRecords()
    {
        $token = $this->Ding->access_token->getToken();
        $params = [
            'access_token' => $token,
            'department_id' => '1',
            'start_time' => '1561219200000',
            'end_time' => '1561392000000',
            'offset' => '0',
            'size' => '10',
            'order' => 'asc'
        ];

        $list = $this->Ding->checkin->records($params);
        return $list;
    }

    /**
     * 获取用户签到记录
     * @return mixed
     */
    public function getCheckin()
    {
        $params = [
            'userid_list' => '0801342469687788',
            'start_time' => '1561219200000',
            'end_time' => '1561392000000',
            'cursor' => '0',
            'size' => '10'
        ];

        $list = $this->Ding->checkin->get($params);
        return $list;
    }

}
