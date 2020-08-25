<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/**
 * 用户模块
 */
$router->get('/getToken', 'UserController@getToken'); // 获取Token

$router->get('/userList', 'UserController@userList'); // 用户列表

$router->get('/userInfo', 'UserController@userInfo'); // 用户详情

$router->get('/getUsers', 'UserController@getUsers'); // 部门用户

$router->get('/usersDetail', 'UserController@usersDetail'); // 部门用户详情

$router->get('/getAdmin', 'UserController@getAdmin'); // 管理员列表

$router->get('/adminScope', 'UserController@adminScope'); // 获取管理员权限

$router->get('/getUseridByUnionid','UserController@getUseridByUnionid');  // 根据 Unionid 获取 Userid

$router->get('/createUser', 'UserController@createUser'); // 获取管理员权限

$router->get('/addRoles', 'UserController@addRoles'); // 批量增加员工角色

$router->get('/getCount', 'UserController@getCount'); // 包含未激活钉钉的人员数量

$router->get('/getActivatedCount', 'UserController@getActivatedCount'); // 获取企业已激活的员工人数


/**
 * 部门模块
 */
$router->get('/departmentList', 'DepartmentController@departmentList'); // 部门列表

$router->get('/getSubDepartmentIds', 'DepartmentController@getSubDepartmentIds'); // 获取子部门 ID 列表

$router->get('/getDepartmentInfo', 'DepartmentController@getDepartmentInfo'); // 获取部门详情

$router->get('/getParentsById', 'DepartmentController@getParentsById'); // 查询部门的所有上级父部门路径

$router->get('/getParentsByUserId', 'DepartmentController@getParentsByUserId'); // 查询指定用户的所有上级父部门路径

$router->get('/createDepartment', 'DepartmentController@createDepartment'); // 创建部门


/**
 * 审批模块
 */
$router->get('/getProcessCount','ProcessController@getProcessCount');  // 获取用户的待审批数量

$router->get('/getProcess','ProcessController@getProcess');  // 获取用户的待审批数量

$router->get('/createProcess', 'ProcessController@createProcess');  // 创建审批实例

$router->get('/getIds', 'ProcessController@getIds');  // 批量获取审批实例 ID

$router->get('/getProcessCount', 'ProcessController@getProcessCount');  // 获取用户待审批数量

$router->get('/listByUserId', 'ProcessController@listByUserId');  // 获取用户可见的审批模板

/**
 * 角色模块
 */
$router->get('/getRoles','RoleController@getRoles');  // 获取角色列表

$router->get('/getRoleUsers','RoleController@getRoleUsers');  // 获取角色下的员工列表

$router->get('/createGroup','RoleController@createGroup');  // 创建角色组

$router->get('/getGroup','RoleController@getGroup');  // 创建角色组

$router->get('/getRoleInfo','RoleController@getRoleInfo');  // 获取角色详情

$router->get('/createRole','RoleController@createRole');  // 创建角色

/**
 * 企业外部联系人
 */
$router->get('/contactLabels','ContactController@contactLabels');  // 获取外部联系人标签列表

$router->get('/contactList','ContactController@contactList');  // 获取外部联系人列表

$router->get('/getContact','ContactController@getContact');  // 获取企业外部联系人详情

$router->get('/createContact','ContactController@createContact');  // 添加外部联系人

$router->get('/scopes','ContactController@scopes');  // 获取通讯录权限范围

/**
 * 创建日程
 */
$router->get('/createCalendar','CalendarController@createCalendar');  // 获取通讯录权限范围

/**
 * 考勤管理
 */
$router->get('/schedules', 'AttendanceController@schedules'); // 企业考勤排班详情

$router->get('/groups', 'AttendanceController@groups'); // 企业考勤排班详情

$router->get('/userGroup', 'AttendanceController@userGroup'); // 获取用户考勤组

$router->get('/recodes', 'AttendanceController@recodes'); // 获取打卡详情

$router->get('/duration', 'AttendanceController@duration'); // 获取请假时长

$router->get('/status', 'AttendanceController@status'); // 查询请假状态

/**
 * 签到管理
 */
$router->get('/checkinRecords', 'CheckinController@checkinRecords'); // 获取部门用户签到记录

$router->get('/getCheckin', 'CheckinController@getCheckin'); // 获取用户签到记录

/**
 * 日志管理
 */
$router->get('/reportList', 'ReportController@reportList'); // 获取用户日志数据

$router->get('/templates', 'ReportController@templates'); // 获取用户可见的日志模板

$router->get('/unreadCount', 'ReportController@unreadCount'); // 获取用户日志未读数

$router->get('/blackboard','ReportController@blackboard');  // 获取用户公告数据

/**
 * 机器人
 */

$router->get('/pushText', 'RobotController@pushText'); //发送Text消息

$router->get('/pushLink', 'RobotController@pushLink'); //发送Link消息

$router->get('/pushForUser', 'RobotController@pushForUser'); //发送消息给指定的人

/**
 * 系统
 */
$router->post('/user/login','LoginController@login');  // 后台登录
$router->post('/user/logout','LoginController@logout');  // 退出登录
$router->post('/user/getInfo','LoginController@getInfo');  // 后台登录
