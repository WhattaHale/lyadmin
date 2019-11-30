<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/18
 * Time: 11:39
 */

namespace app\Admin\Controller;


class Login extends Com
{
    /**
     * 登入页
     */
    public function index()
    {
        $this->assign('title','登入');
        return $this->fetch();
    }
    /**
     * 登入
     */
    public function login()
    {
        $data = $this->request->param();
        if($data['username'] == 'admin' and $data['password'] == 'admin') {
            $this->ajaxReturn(['code' => 200, 'status' =>'1', 'type' =>'success', 'msg' => '恭喜麻辣个鸡', 'data' => $data]);
        }
        $this->ajaxReturn(['code' => 400, 'status' =>'0', 'type' =>'error', 'msg' => '辣鸡账号密码都能输错', 'data' => $data]);
    }

    public function logout()
    {
        $this->ajaxReturn(['code' => 200, 'status' =>'1', 'type' =>'success', 'msg' => '再见你个辣鸡', 'data' => []]);
    }
}