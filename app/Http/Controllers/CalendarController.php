<?php

namespace App\Http\Controllers;

class CalendarController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 创建日程
     * @return mixed
     */
    public function createCalendar()
    {
        $params = [
            'create_vo' => '',
            'summary' => '办理进京证',
            'location' => '中国香港特别行政区九龙湾',
            'receiver_userids' => ['0801342469687788'],
            'end_time' => '2019-06-22 10:00',
            'unix_timestamp' => '1561168800000',
            'start_time' => '2019-06-20 14:00',
            'unix_timestamp' => '1561010400000',
            'source' => '',
            'url' => '',
            'creator_userid' => '0801342469687788',
            'uuid' => '1233210',
            'biz_id' => 'test1233210'
        ];
        $list = $this->Ding->calendar->create($params);
        return $list;
    }


}
