<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{
    // 初始化DingTalk类
    protected $Ding;

    public function __construct()
    {
        $this->Ding =  app('DingTalk')->runed();

    }

    /**
     * 获取外部联系人标签列表
     * @return mixed
     */
    public function contactLabels()
    {

        $list = $this->Ding->contact->labels($offset = 0, $size = 100);
        return $list;
    }

    /**
     * 获取外部联系人列表
     * @return mixed
     */
    public function contactList()
    {
        return $this->Ding->contact->list($offset = 0, $size = 100);
    }

    /**
     * 获取企业外部联系人详情
     * @return mixed
     */
    public function getContact()
    {
        $userId = '';

        return $this->Ding->contact->get($userId);

    }

    /**
     * 添加外部联系人
     * @return mixed
     */
    public function createContact()
    {
        $params = [
            'contact' => '技术部来面试',
            'label_ids' => [451858103],
            'address' => '中国香港油麻地33-103号商铺',
            'remark' => '6月20日来面试的人员',
            'follower_user_id' => '0801342469687788',
            'name' => '王二虎',
            'state_code' => '86',
            'mobile' => '13888888880'
        ];
       return $this->Ding->contact->create($params);
    }

//    $this->Ding->contact->update($roleId, $roleName); // 修改角色
//    $this->Ding->contact->delete($roleId); // 删除角色

    /**
     * 获取通讯录权限范围
     * @return mixed
     */
    public function scopes()
    {
        return $this->Ding->contact->scopes();
    }
}
