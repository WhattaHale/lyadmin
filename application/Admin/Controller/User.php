<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/18
 * Time: 16:57
 */

namespace app\Admin\Controller;


use think\facade\Request;

class User extends Com
{
    /**
     * 登入
     */
    public function login()
    {
        $this->assign('title','登入');
        return $this->fetch();
    }

    /**
     * 注册
     * @return mixed
     */
    public function register()
    {
        $this->assign('title','注册');
        return $this->fetch();
    }

    /**
     * 忘记密码
     * @return mixed
     */
    public function forget()
    {
        $this->assign('title','忘记密码');
        $this->assign('type','forget');
        return $this->fetch('public/forget');
    }

    public function checkForget()
    {
        if ($this->request->isPost()) {
            $time           = time();
            $data           = input('post.');
            $this->assign('title','重置密码');
            $this->assign('type','resetpass');
            $this->assign('phone',$data['phone']);
            return $this->fetch('public/resetpass');
            $this->ajaxReturn(['code' => 200, 'msg' => '验证成功', 'data' => $data, 'url'=>url("/user/resetpass")]);
            $phone          = isset($data['phone']) ? trim($data['phone']) : '';
            $phone_code     = isset($data['smscode']) ? trim($data['smscode']) : '';
            if (empty($phone) || empty($phone_code)) {
                $this->ajaxReturn(['code' => 400, 'msg' => '认真填写参数', 'data' => $data, 'url'=>'']);
            }
            $ret = model('Sms', 'Service')->checkTeleteCode($phone, 3, $data['smscode']);
            if ($ret['status'] != 1) {
                $this->ajaxReturn($ret);
            }
            else{
                $this->assign('title','重置密码');
                $this->ajaxReturn(['code' => 200, 'msg' => '验证成功', 'data' => $data, 'url'=>'']);
            }
        } else {
            $this->assign('title','忘记密码');
            $this->fetch('public/forget');
        }
    }

    public function resetpass()
    {
        if ($this->request->isPost()) {
            $time           = time();
            $data           = input('post.');
            $this->assign('title','重置密码');
            $this->assign('type','resetpass');
            $this->assign('phone',$data['phone']);
            return $this->fetch('public/resetpass');
        } else {
            $this->assign('title','重置密码');
            return $this->fetch('public/resetpass');
        }
    }
}