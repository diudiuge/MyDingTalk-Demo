<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 获取用户日志数据
     * @return mixed
     */
    public function reportList()
    {
        $params = [
            'start_time' => '1561219200000',
            'end_time' => '1561392000000',
            'userid' => '0801342469687788',
            'cursor' => '0',
            'size' => '10'
        ];

        $list = $this->Ding->report->list($params);
        return $list;
    }

    /**
     * 获取用户可见的日志模板
     * @return mixed
     */
    public function templates()
    {
        $userId = '0801342469687788';
        $offset = '0';
        $size = '100';

        $list = $this->Ding->report->templates($userId, $offset, $size);
        return $list;
    }

    /**
     * 获取用户日志未读数
     * @return mixed
     */
    public function unreadCount()
    {
        $userId = '0801342469687788';

        $list = $this->Ding->report->unreadCount($userId);
        return $list;
    }

    /**
     * 获取用户公告数据
     * @return mixed
     */
    public function blackboard()
    {
        $userId = '0801342469687788';

        $list = $this->Ding->blackboard->list($userId);
        return $list;
    }
}
