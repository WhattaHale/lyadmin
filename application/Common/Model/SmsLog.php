<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/25
 * Time: 11:54
 */

namespace app\Common\Model;


class SmsLog extends Com
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'sys_sms_log';

    // 设置当前模型的数据库连接
    protected $connection = [
        // 数据库表前缀
        'prefix'      => 'sys_',
    ];

    /**
     * 缓存名称
     * @var type
     */
    public $mcName = 'sys_sms_log';

    /**
     * 缓存开关
     * @var type
     */
    public $mcOpen = true;

    public $types = [
        1 => '登入',
        2 => '注册',
        3 => '找回密码',
        5 => '申诉处理通知商户',
        90 => '异常通知技术',
        101 => '买手注册',
        102 => '买手找回密码',
        103 => '申诉处理通知买手',
        104 => '提现驳回通知买手',
        105 => '订单驳回通知买手',
        106 => '邀请评价通知买手',
        107 => '订单强制撤销通知买手',
        108 => '商家验号通知买手',
        109 => '手动派单通知买手',
        199 => '系统通知',
        201 => '推手注册',
        202 => '推手找回密码',
        301 => '商家充值审核提醒',
        302 => '商家店铺绑定提醒',
        303 => '商家任务审核提醒',
        601 => '订单商家推广佣金发放异常',
        602 => '订单买手推广佣金发放异常',
    ];

    public $status_options = [
        1 => '待发送',
        2 => '成功',
        3 => '失败',
    ];
}