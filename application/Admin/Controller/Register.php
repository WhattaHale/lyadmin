<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/20
 * Time: 13:51
 */

namespace app\Admin\Controller;



class Register extends Com
{
    /**
     * 注册
     */
    public function register()
    {
        if ($this->request->isPost()) {
            $time           = time();
            $data           = input('post.');
            $salt           = random_str(6);
            $phone          = isset($data['phone']) ? trim($data['phone']) : '';
            $phone_code     = isset($data['smscode']) ? trim($data['smscode']) : '';
            $password       = isset($data['password']) ? trim($data['password']) : '';
            $username       = isset($data['username']) ? trim($data['username']) : '';
            $agreement      = isset($data['agreement']) ? trim($data['agreement']) : '';
            $invite_code    = !empty($data['invite_code']) ? trim($data['invite_code']) : '';
            if($agreement != 'on'){
                $this->ajaxReturn(['code' => 400, 'msg' => '请同意用户协议', 'data' => $data, 'url'=>'']);
            }
            if (empty($phone) || empty($phone_code) || empty($password)) {
                $this->ajaxReturn(['code' => 400, 'msg' => '认真填写参数', 'data' => $data, 'url'=>'']);
            }
            if ($data['password'] != $data['repass']) {
                $this->ajaxReturn(['code' => 400, 'msg' => '两次密码输入不一致', 'data' => $data, 'url'=>'']);
            }
            $info = model('User')->infoByPhone($phone);
            if($info){
                $this->ajaxReturn(['code' => 400, 'msg' => '该手机号已经被注册过啦！', 'data' => $data, 'url'=>'' ]);
            }
            $ret = model('Sms', 'Service')->checkTeleteCode($phone, 2, $data['smscode']);
            if ($ret['status'] != 1) {
                $this->ajaxReturn($ret);
            }
            $insert = [
                'username'     => $username,
                'phone'        => $data['phone'],
                'salt'         => $salt,
                'password'     => $this->encryption_pwd($password, $salt),
                'invite_code'  => $invite_code,
                'created_time' => $time,
                'updated_time' => $time,
            ];
            $ret = model('Common/User')->insert($insert);
            if ($ret) {
                //邀请码
                if(!empty($invite_no)){
//                    D('SpreadLog','Service')->add_spread('customer',$invite_no,$ret,1);
                }

                $this->success('注册成功', url("/User/Login"), 3);

            } else {
                $this->error('注册失败');
            }

        } else {
            $this->fetch('User/Register');;
        }
    }

    /**
     * 密码加密
     * @param $pwd // 密码
     * @param $salt // 密码盐
     * @return string
     */
    protected function encryption_pwd($pwd, $salt)
    {
        return sha1(md5($salt . $pwd));
    }

}