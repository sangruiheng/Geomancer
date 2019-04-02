<?php

namespace Manage\Controller;

use Think\Controller;

class IndexController extends CommonController
{
    public function index()
    {
        $this->display();
    }

    //欢迎页
    public function welcome()
    {
        $this->display();
    }

}

?>