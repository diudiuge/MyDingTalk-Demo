<?php

namespace App\Http\Controllers;

class ProcessController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 获取单个审批实例
     * @return mixed
     */
    public function getProcess()
    {
        $id = 'bf696e79-1d1b-4ee5-a7ce-fe658638f2c5';
//        $id = '9075d34a-9ab4-4689-b9ec-19c4ea8045ac';
        $info = $this->Ding->process->get($id);
        return $info;
    }

    /**
     * 发起审批实例
     * @return mixed
     */
    public function createProcess()
    {
        // 表单数据
        $json = [
            ['name' => '入驻事由', 'value' => '非洲大石厂委托离案技术支持'],
            ['name' => '入驻人员', 'value' => ['09266460511076653']],
            ['name' => ["开始时间","结束时间"], 'value' => ['2019-06-20 08:30','2019-06-25 08:30']],
            ['name' =>'时长（小时）', 'value' => '120.00'],
            ['name'=> '备注', 'value' => '前期一个人，后续会增加人员']
        ];

        // 接口数据
        $params = [
            'process_code' => 'PROC-3FDE3EF9-4C0C-4E07-B090-626BFBADA6B1',
            'originator_user_id' => '0801342469687788',
            'dept_id' => '1',
            'approvers' => '09266460511076653,0801342469687788',
            'form_component_values' => $json
        ];

        return $this->Ding->process->create($params);
    }

    /**
     * 批量获取审批实例 ID
     * @return mixed
     */
    public function getIds()
    {
        $params = [
            'process_code' => 'PROC-3FDE3EF9-4C0C-4E07-B090-626BFBADA6B1',
            'start_time' => '1560906000000',
            'end_time' => '1561032000000',
            'size' =>20,
            'userid_list' => '0801342469687788'
        ];

        $list = $this->Ding->process->getIds($params);
        return $list;

    }

    /**
     * 获取用户待审批数量
     * @return mixed
     */
    public function getProcessCount()
    {
        $userId = '09266460511076653';
       return $this->Ding->process->count($userId);
    }

    /**
     * 获取用户可见的审批模板
     * @return mixed
     */
    public function listByUserId()
    {
        $userId = '0801342469687788';
        $list = $this->Ding->process->listByUserId($userId = null, $offset = 0, $size = 100);
        return $list;
    }

    /**
     * 管理员权限范围
     * @return mixed
     */
    public function adminScope()
    {
        $userId = '0801342469687788'; // 我的Id
        $info = $this->Ding->user->administratorScope($userId);
        return $info;
    }

    /**
     * 根据 Unionid 获取 Userid
     * @return mixed
     */
    public function getUseridByUnionid()
    {
        $unionId = 'uczlQ8263DnK4gokUcxRTwiEiE';
        $userId = $this->Ding->user->getUseridByUnionid($unionId);
        return $userId;
    }

    /**
     * 创建员工
     * @return mixed
     */
    public function createUser()
    {
        $params = [
            'userid' => 'zhangsan',
            'name' => '张三',
            'orderInDepts' => '{}', // 此处为 Json
            'department' => [1],
            'position' => '测试经理',
            'mobile' => '15913215421',
            'tel' => '010-123333',
            'workPlace' => '',
            'remark' => '',
            'email' => 'zhangsan@gzdev.com',
            'orgEmail' => 'qiye@gzdev.com',
            'jobnumber' => '111111',
            'isHide' => false,
            'isSenior' => false,
            'extattr' => [
                '爱好' => '旅游',
                '年龄' => '24',
            ],
        ];
        return $this->Ding->user->create($params);
    }

    /**
     * 修改员工信息
     * @return mixed
     */
    public function updateUser()
    {
        $userId = 'zhangsan';

        $params = [
            'name' => '张三',
            'department' => [1],
            'orderInDepts' => '{1}', // 此处为 Json
            'position' => '产品经理',
            'mobile' => '15913215421',
            'tel' => '010-123333',
            'workPlace' => '',
            'remark' => '',
            'email' => 'zhangsan@gzdev.com',
            'orgEmail' => 'qiye@gzdev.com',
            'jobnumber' => '111111',
            'isHide' => false,
            'isSenior' => false,
            'extattr' => [
                '爱好' => '旅游',
                '年龄' => '24',
            ],
        ];
        return $this->Ding->user->update($userId, $params);
    }

    /**
     * 删除用户
     * @return mixed
     */
    public function delete()
    {
        $userId = 'zhangsan';
        return $this->Ding->user->delete($userId);

    }

    /**
     * 批量修改用户角色
     * @return mixed
     */
    public function addRoles()
    {
        $users = ['zhangsan', 'lisi'];
        $roles = [123, 456];
        return $this->Ding->user->addRoles($users, $roles);
    }

//    $this->Ding->user->removeRoles($users, $roles);  批量删除角色

    /**
     * 包含未激活钉钉的人员数量
     * @return mixed
     */
    public function getCount()
    {
        return $this->Ding->user->getCount();
    }

    /**
     * 获取企业已激活的员工人数
     * @return mixed
     */
    public function getActivatedCount()
    {
        return $this->Ding->user->getActivatedCount();
    }


}
