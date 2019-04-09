<?php

namespace Manage\Controller;

use Manage\Model\AuthlevelModel;
use Think\Controller;

class AuthlevelController extends CommonController
{

    public function authLevelList()
    {

        $authLevelModel = new AuthlevelModel();

        $authLevel = $authLevelModel->select();

        $this->assign('list', $authLevel);
        $this->display();


    }


    public function addAuthLevelData()
    {
        $table = $_GET['table'];
        $backUrl = $_GET['backUrl'];
        $controller = $_GET['controller'];
        $id = $_POST['id'];
        $sql = D($table);
        $authlevel_id = $_POST['authlevel_id'];
        $authlevel_level = $_POST['authlevel_level'];
        $authLevelModel = new AuthlevelModel();
        foreach ($authlevel_id as $key => $value) {
            $authLevelModel->authlevel_level = $authlevel_level[$key];
            $result = $authLevelModel->where("id=$value")->save();
        }
        $this->success('编辑成功！', U($controller . '/' . $backUrl));

    }


}

?>