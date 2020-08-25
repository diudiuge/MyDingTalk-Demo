<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 用户列表
     * @return mixed
     */
    public function getToken()
    {
        return $this->Ding->access_token->getToken();
    }

    /**
     * 用户列表
     * @return mixed
     */
    public function userList()
    {
        $departmentId = '118892500';
        $list = $this->Ding->user->getUserIds($departmentId);
        return $list;
    }

    /**
     * 用户详情
     * @return mixed
     */
    public function userInfo()
    {
        $userId = '0801342469687788'; // 我的Id
        $info = $this->Ding->user->get($userId);
        return $info;
    }

    /**
     * 获取部门用户
     * @return mixed
     */
    public function getUsers()
    {
        $departmentId = '118903488';

        $list = $this->Ding->user->getUsers($departmentId, $offset = 0, $size = 20, $order = null, $lang = null);
        return $list;

    }

    /**
     * 获取部门用户详细
     * @return mixed
     */
    public function usersDetail()
    {
        $departmentId = '118892500';

        $list = $this->Ding->user->getDetailedUsers($departmentId, $offset = 0, $size = 20, $order = null, $lang = null);
        return $list;
    }

    /**
     * 获取管理员列表
     * @return mixed
     */
    public function getAdmin()
    {
        $list = $this->Ding->user->administrators();
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
