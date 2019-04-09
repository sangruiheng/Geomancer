<?php

namespace Manage\Controller;

use Manage\Model\DomainlabelModel;
use Think\Controller;

class DomainlabelController extends CommonController
{

    public function domainLabelList()
    {

        $domainLabelModel = new DomainlabelModel();

        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('user', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $domainLabel = $domainLabelModel->where($map)->order('id desc')->page($p . ',10')->select();
//        print_r($user);
        $count = $domainLabelModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $domainLabel);
        $this->display();


    }


    public function addDomainLabelData()
    {
        $backUrl = $_GET['backUrl'];
        $table = $_GET['table'];
        $controller = $_GET['controller'];
        $id = $_POST['id'];
        $sql = D($table);
        if ($sql->create()) {
            if (empty($id)) { //添加
                $sql->id = NULL;
                $sql->domainlabel_addtime = time();
                $sql->domainlabel_updatetime = time();
                $result = $sql->add();
            } else {  //修改
                $sql->domainlabel_updatetime = time();
                $result = $sql->save();
            }
            if ($result) {
                $this->success('编辑成功！', U($controller . '/' . $backUrl . '/scategory_id/' . $_POST['scategory_id']));
            }
        } else {
            $this->error($sql->getError(), $jumpUrl = '', $ajax = true);
        }
    }

    //是否推荐
    public function is_recommend()
    {
        $rs = M($_POST['table'])->save(array('id' => $_POST['id'], 'is_recommend' => $_POST['status']));
        if ($rs) {
            $returnData['success'] = $rs;
            $returnData['status'] = $_POST['status'];
            $returnData['id'] = $_POST['id'];
            $returnData['table'] = $_POST['table'];
        } else {
            $returnData['success'] = $rs;
            $returnData['info'] = '修改状态失败，请及时联系管理员';
        }
        $this->ajaxReturn($returnData);
    }


    //删除
    public function deleteDomainLabel()
    {
        $table = $_POST['table'];
        $ids = $_POST['delID'];
        $sql = M($table);
        if (strlen($ids) > 0) {
            $ids = substr($ids, 0, strlen($ids) - 1);
        }
        //删除图片
        $map['id'] = array('in', $ids);
        $GroupImg_list = M('domainlabel')->where($map)->select();
        foreach ($GroupImg_list as $value) {
//            $file = ($_SERVER["DOCUMENT_ROOT"] . 'Uploads/Manage/' . $value["banner_img"]);
            $file = ('Uploads/Manage/' . $value["domainlabel_img"]);
            if (file_exists($file)) {
                @unlink($file);
            }
        }
        return $Result = $sql->delete($ids);
    }

}

?>