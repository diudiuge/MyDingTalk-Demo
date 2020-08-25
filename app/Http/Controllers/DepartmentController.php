<?php

namespace App\Http\Controllers;

class DepartmentController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }
    /**
     * 部门列表
     * @return mixed
     */
    public function departmentList()
    {
       $list = $this->Ding->department->list($id = null, $isFetchChild = false, $lang = null);
       return $list;
    }

    /**
     * 获取子部门 ID 列表
     * @return mixed
     */
    public function getSubDepartmentIds()
    {
        $id = '118892500';
        $list = $this->Ding->department->getSubDepartmentIds($id);
        return $list;
    }

    /**
     * 获取部门详情
     * @return mixed
     */
    public function getDepartmentInfo()
    {
        $id = '1';
        return $this->Ding->department->get($id, $lang = null);
    }

    /**
     * 查询部门的所有上级父部门路径
     * @return mixed
     */
    public function getParentsById()
    {
        $id = '118837720';
        return $this->Ding->department->getParentsById($id);
    }

    /**
     * 查询指定用户的所有上级父部门路径
     * @return mixed
     */
    public function getParentsByUserId()
    {
        $userId = '0557276015699662';
        return $this->Ding->department->getParentsByUserId($userId);
    }

    /**
     * 创建部门
     * @return mixed
     */
    public function createDepartment()
    {
        $params = [
            'parentid' => '1',  //父级部门ID
            'name' => '减肥避暑群',
            'id' => '119992501'
        ];
        return $this->Ding->department->create($params);
    }

    // $this->Ding->department->update($params);        修改部门
    // $this->Ding->department->delete($id);            删除部门
}
