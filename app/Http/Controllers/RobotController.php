<?php

namespace App\Http\Controllers;
use App\Library\Common\Common;

class RobotController extends Controller
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
     * 机器人发送文本消息
     * @return mixed
     */
    public function pushText()
    {
        $webhook = "https://oapi.dingtalk.com/robot/send?access_token=411963d1598df9577494089a59d9de40e7ec92fbc5e8bec1b4cca139b15c8fc0";
        $message= "给各位同学说个凄美的爱情故事：这个故事涉及三角恋、江湖恩怨、儿女情长、宗教信仰，只不过很短，只有一句话，秃驴，竟敢和贫道抢师太！";
        $data = array ('msgtype' => 'text','text' => array ('content' => $message));
        $data_string = json_encode($data);

        $res = Common::curl($webhook, $data_string);
        return $res;
    }

    /**
     * 机器人发送链接消息
     * @return mixed
     */
    public function pushLink()
    {
        $webhook = "https://oapi.dingtalk.com/robot/send?access_token=411963d1598df9577494089a59d9de40e7ec92fbc5e8bec1b4cca139b15c8fc0";
        $message= "网逛的时候偶然发现的一个番号大全，懂的自然知道怎么用，不懂的就当图片YY去了；小心收藏，低调低调。。。";
        $title = '送给大家一个福利网站';
        $picUrl = '';
        $messageUrl = 'https://www.gaoxiaoba.com/jav/';
        $data = array ('msgtype' => 'link','link' => array ('text' => $message, 'title' => $title, 'picUrl' => $picUrl , 'messageUrl' => $messageUrl));
        $data_string = json_encode($data);

        $res = Common::curl($webhook, $data_string);
        return $res;
    }

    /**
     * 推送消息到指定的人
     * @return mixed
     */
    public function pushForUser()
    {
        $webhook = "https://oapi.dingtalk.com/robot/send?access_token=411963d1598df9577494089a59d9de40e7ec92fbc5e8bec1b4cca139b15c8fc0";
        $message= "相亲攻略快来看看把.  \r http://love.shangc.net/2018/0117/33217373.html \r @13231113766 @18502439166";
        $atMobiles = [
                        'atMobiles' =>['13231113766', '18502439166'],
                        'isAtAll' => false
                     ];
        $data = array ('msgtype' => 'text', 'text' => array ('content' => $message), 'at' => $atMobiles );
        $data_string = json_encode($data);

        $res = Common::curl($webhook, $data_string);
        return $res;

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
