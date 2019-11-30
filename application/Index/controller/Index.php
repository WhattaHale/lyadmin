<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        dump($_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        dump($_SERVER);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
