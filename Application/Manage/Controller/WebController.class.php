<?php

namespace Manage\Controller;

use Manage\Model\BannerModel;
use Manage\Model\WebModel;
use Think\Controller;

class WebController extends CommonController
{

    //客服电话
    public function webCustomerTel()
    {
        $webModel = new WebModel();
        $webList = $webModel->find(1);
        $this->assign('webList', $webList);
        $this->display();
    }


    //编辑网站
    public function addWeb()
    {
        $backUrl = $_GET['backUrl'];
        $table = $_GET['table'];
        $controller = $_GET['controller'];
        $id = $_POST['id'];
        $sql = D($table);
        if ($sql->create()) {
            if (empty($id)) {  //添加
                $sql->id = NULL;
                $result = $sql->add();
            } else {     //修改
                $result = $sql->save();
            }
            if ($result) {
                $this->success('编辑成功！', U($controller . '/' . $backUrl));
            }
        } else {
            $this->error($sql->getError(), $jumpUrl = '', $ajax = true);
        }
    }

    //风水师承诺书
    public function webGeomancerPromise()
    {
        $webModel = new WebModel();
        $webList = $webModel->find(1);
        $this->assign('webList', $webList);
        $this->display();
    }


    //风水师协议书
    public function webGeomancerAgreement()
    {
        $webModel = new WebModel();
        $webList = $webModel->find(1);
        $this->assign('webList', $webList);
        $this->display();
    }

    //注册协议
    public function webRegisterAgreement()
    {
        $webModel = new WebModel();
        $webList = $webModel->find(1);
        $this->assign('webList', $webList);
        $this->display();
    }


    //提现须知
    public function webCashNotice()
    {
        $webModel = new WebModel();
        $webList = $webModel->find(1);
        $this->assign('webList', $webList);
        $this->display();
    }




}

?>