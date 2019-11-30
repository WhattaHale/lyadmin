<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/27
 * Time: 14:44
 */

namespace app\Api\Controller;


class Test extends Com
{
    public function index()
    {
        dump('api/test');
    }
    public function testsession ()
    {
        $data = $this->request->param();

    }

    public function hass()
    {
        $data = $this->request->param();
        $rs = session('?'.$data['name']);
        dump($rs);
        exit;
    }

    public function sets()
    {
        $dataa = 93865432454328;
        $datab = 938328;
        $rs = session('name',$dataa,20);
        $rs = session('user',$datab,60);
        dump($rs);
    }

    public function gets()
    {
        $data = $this->request->param();
        $rs = session($data['name']);
        dump($rs);
    }

    public function clearSession()
    {
        $data = $this->request->param();
        $n = isset($data['name'])? $data['name']:null;
        $p = isset($data['prefix'])? $data['prefix']:null;
        $rs = session($n,null,$p);
    }

    public function sus()
    {
        $this->success('注册成功', url("User/Login"), 3);
    }
}