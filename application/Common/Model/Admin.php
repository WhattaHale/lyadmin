<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/26
 * Time: 14:14
 */

namespace app\Common\Model;


class Admin extends Com
{
// 设置当前模型对应的完整数据表名称
    protected $table = 'sys_admin';

    // 设置当前模型的数据库连接
    protected $connection = [
        // 数据库表前缀
        'prefix'      => 'sys_',
    ];

    /**
     * 缓存名称
     * @var type
     */
    public $mcName = 'sys_admin';

    /**
     * 缓存开关
     * @var type
     */
    public $mcOpen = true;

    /**
     * 由手机获取用户
     * @param $phone
     * @return array|bool|null|PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function infoByPhone($phone)
    {
        if (empty($phone)){
            return false;
        }
        $where['phone'] = $phone;
        $rs = Admin::where($where)->find();
        return $rs;
    }
}