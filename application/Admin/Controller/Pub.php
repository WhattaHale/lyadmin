<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/20
 * Time: 14:03
 */

namespace app\Admin\Controller;


use think\captcha\Captcha;
use think\Db;

class Pub extends Com
{
    /**
     * 刷新图片验证码
     * type
     * @return [type] [description]
     */
    public function verify(){
        $data = $this->request->param();
        $type   = empty($data['type']) ? 1 : $data['type'];
        $code_key   = empty($data['code_key']) ? time().rand(100000, 999999) : $data['code_key'];
        $config = array(
            'fontSize' => 14, // 验证码字体大小
            'length'   => 4, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'imageW'   => 100,
            'imageH'   => 30,
            'code_key' => md5($code_key),
        );
        $verify = new Captcha($config);
        ob_clean();
        return $verify->entry($type);
    }
    /**
     * 获取短信验证码
     * type 2:注册 3:找回密码
     */
    public function getSmsCode()
    {
        $data  = $this->request->param();
        $phone = trim($data['phone']);
        $type = empty($data['type']) ? '2' : $data['type'];
        $code_key = trim($data['code_key']);
        if (!is_mobile($phone)) {
            $this->ajaxReturn(['code' => 400, 'msg' => '手机格式都能输错', 'data' => $data, 'url'=>'']);
        }
        if(!in_array($type, [1,2,3,'login','register','forget'])){
            $this->ajaxReturn(['code' => 400, 'msg' => '不用乱来哈！', 'data' => $data, 'url'=>'']);
        }
        //验证code
        $code = isset($data['vercode']) ? trim($data['vercode']) : '';
        $verify_type = $type;
        if (!captcha_check($code, $verify_type)) {
            $this->ajaxReturn(['code' => 400, 'msg' => '你在乱输试试！', 'data' => $data, 'url'=>'']);
        }
        //注册
        if($type ==2 or $type =='register'){
            $info = model('Common/User')->infoByPhone($phone);
            if($info){
                $this->ajaxReturn(['code' => 400, 'msg' => '这个手机号已经注册过了兄弟！', 'data' => $data, 'url'=>'']);
            }
            //找回密码
        }elseif($type == 3 or $type =='forget'){
            $info = model('Common/User')->infoByPhone($phone);
            if(empty($info)){
                $this->ajaxReturn(['code' => 400, 'msg' => '这个手机号根没注册过呀！兄弟！', 'data' => $data, 'url'=>'']);
            }
        }
        $rs = model('Sms', 'Service')->addTeleteCode($phone, $type);
        $this->ajaxReturn($rs);
    }
}