<?php

namespace App\Http\Controllers;

class RoleController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 获取角色列表
     * @return mixed
     */
    public function getRoles()
    {

        $list = $this->Ding->role->list($offset = null, $size = null);
        return $list;
    }

    /**
     * 发起审批实例
     * @return mixed
     */
    public function getRoleUsers()
    {
        $roleId = '449827945'; // 子管理员
        return $this->Ding->role->getUsers($roleId, $offset = null, $size = null);
    }

    /**
     * 创建角色组
     * @return mixed
     */
    public function createGroup()
    {
        $name = '测试部';

        return $this->Ding->role->createGroup($name);

    }

    /**
     * 获取角色组
     * @return mixed
     */
    public function getGroup()
    {
        $groupId = '461246699'; // 测试组ID
       return $this->Ding->role->getGroups($groupId);
    }

    /**
     * 获取角色详情
     * @return mixed
     */
    public function getRoleInfo()
    {
        $roleId = '449827945'; //子管理员
        $list = $this->Ding->role->get($roleId);
        return $list;
    }

    /**
     * 管理员权限范围
     * @return mixed
     */
    public function createRole()
    {
        $groupId = '461246699'; // 测试组
        $name = '测试组长';
        $info = $this->Ding->role->create($groupId, $name);
        return $info;
    }
//    $this->Ding->role->update($roleId, $roleName); // 修改角色
//    $this->Ding->role->delete($roleId); // 删除角色

}
