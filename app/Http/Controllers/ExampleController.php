<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{

    /**
     * 部门列表
     * @return mixed
     */
    public function departmentList()
    {
       $Ding =  app('DingTalk')->runed();
       $list = $Ding->department->list($id = null, $isFetchChild = false, $lang = null);
       return $list;
    }

    /**
     * 用户列表
     * @return mixed
     */
    public function userList()
    {
        $Ding =  app('DingTalk')->runed();
        $departmentId = '118892500';
        $list = $Ding->user->getUserIds($departmentId);
        return $list;
    }

    /**
     *
     * @return mixed
     */
    public function userInfo()
    {
        $userId = '0557276015699662';
        $Ding =  app('DingTalk')->runed();
        $info = $Ding->user->get($userId);
        return $info;
    }
}
