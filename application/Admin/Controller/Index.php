<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/12
 * Time: 17:28
 */

namespace app\Admin\Controller;


class Index extends Com
{
    /**
     * 首页
     * @return mixed
     */
   public function index(){
       $this->assign('title','首页');
       return $this->fetch();
   }

    public function home($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}