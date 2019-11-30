<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/24
 * Time: 17:10
 */

namespace app\Common\Service;


class Sms
{
    /**
     * 生成手机短信序列号
     * @param type $telNo 手机号
     * @param type $type  2 注册认证手机 3.找回密码
     */

    public function addTeleteCode($telNo, $type = 1)
    {
        $limit_tels = [];
        if (in_array($telNo, $limit_tels)) {
            return ['code' => 400, 'msg' => '嘿嘿你的手机被我限制了！', 'data' => [], 'url'=>''];
        }

        $types = model('SmsLog')->types;
        if (empty($telNo) || !in_array($type, array_keys($types))) {
            return ['code' => 400, 'msg' => '额！数据好像中途被吃了', 'data' => [], 'url'=>''];
        }

        //限制同一个手机号一天只能发5条
        $key_tel_day_name = $telNo . '_day_' . $type;
        $tel_day_times    = session($key_tel_day_name);

        if ($tel_day_times != false && $tel_day_times > 4) {
            return ['code' => 400, 'msg' => '短信不要钱吗？已经给你发了五条了你还想怎样', 'data' => [], 'url'=>''];
        }

        $key_rqt_code_name = $telNo . '_rqt_' . $type; //用户1分钟内曾经申请过。
        $code              = session($key_rqt_code_name);

        if ($code != false) {
            return ['code' => 400, 'msg' => '短信不要钱吗？一分钟内刚给你发过', 'data' => [], 'url'=>''];
        }

        $code = random_str(6, 'number'); //随机验证码

        $msg = "你正在进行" . $types[$type] . "操作，短信验证码为【" . $code . "】,请勿泄露。";
        //TODO 找平台发送短信
        $platform = $type > 100 && $type < 200 ? 2 : 1; // 推手跟商家的sign一致
        //测试环境不发送
        if (!config('app_debug')){
//            $sms_ret  = model('YimeiSms', 'Service')->send_sms($telNo, $msg, $platform);
        }else{
            $sms_ret = ['status'=>1,'data'=>['resJson'=>'','smsId'=>1],];
        }


        $insertSms['return_json'] = $sms_ret['data']['resJson'];
        if ($sms_ret['status'] == 1) {
            $insertSms['smsMessageSid'] = $sms_ret['data']['smsId'];
            $insertSms['statusCode']    = 'SUCCESS';
            $insertSms['status']        = 2;
            $insertSms['statusMsg']     = '成功';
            //session记录  code ，发送时间限制，发送次数
            $keyName = $telNo . '_' . $type;
            session($keyName, $code,null, 600);
            session($key_rqt_code_name, $code,null, 60);

            if ($tel_day_times != false) {
                $tel_day_times = $tel_day_times + 1;
            } else {
                $tel_day_times = 1;
            }

            session($key_tel_day_name, $tel_day_times,null, 86400);
        } else {
            $insertSms['statusCode'] = $sms_ret['data']['code'];
            $insertSms['status']     = 3;
            $insertSms['statusMsg']  = $sms_ret['info'];
        }
        $timeOut                  = 600; //超时时间
        $insertSms['phone']       = $telNo;
        $insertSms['code']        = $code;
        $insertSms['type']        = $type;
        $insertSms['created_time']= time();
        $insertSms['overdue_time']= time() + $timeOut;
        $insertSms['sms_content'] = $msg;
        $re = model('Common/SmsLog')->insert($insertSms);
        if ($sms_ret['status'] == 1) {
            $return = ['code' => 200, 'msg' => '短信验证码发送成功请注意查收', 'data' => [], 'url'=>''];
        } else {
            $return = ['code' => 400, 'msg' => '发送失败！请稍后再试', 'data' => [], 'url'=>''];
        }
        return $return;
    }

    /**
     * 验证电话号码是否正确
     * @param $telNo //手机号
     * @param $type //1 短信登入 2 注册 3找回密码
     * @param $code //验证码
     * @param bool $clear_cache
     * @return array
     */
    public function checkTeleteCode($telNo, $type, $code, $clear_cache = true)
    {

        if (empty($telNo) || empty($type) || empty($code)) {
            return ['code' => 400, 'msg' => '参数错误！', 'data' => [], 'url'=>''];
        }
        $code    = strtolower($code);
        $keyName = $telNo . '_' . $type;
        $Scode   = session($keyName);
        //如果验证码与发送的号码相同
        if ($Scode == $code) {
            //删除缓存
            if ($clear_cache) {
                session($keyName, null);
            }

            //标记短信已被验证
            $return = ['code' => 200, 'msg' => '验证通过！', 'data' => [], 'url'=>''];
        } else {
            $return = ['code' => 400, 'msg' => '短信验证码错误！', 'data' => [], 'url'=>''];
        }
        return $return;
    }

}