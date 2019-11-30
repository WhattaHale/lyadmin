<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/12
 * Time: 17:29
 */

namespace app\Admin\Controller;



use think\Controller;
use think\Request;



class Com extends Controller
{
    public $USER;

    /**
     * @var \think\Request Request实例
     */
    protected $request;

    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function _construct(Request $request)
    {
        $this->request = $request;
    }

    public function _initialize()
    {


    }
}