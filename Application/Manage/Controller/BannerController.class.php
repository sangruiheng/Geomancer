<?php

namespace Manage\Controller;

use Manage\Model\BannerModel;
use Think\Controller;

class BannerController extends CommonController
{

    public function bannerList()
    {

        $bannerModel = new BannerModel();

        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('user', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $banner = $bannerModel->where($map)->order('id desc')->page($p . ',10')->select();
//        print_r($user);
        $count = $bannerModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $banner);
        $this->display();


    }


    public function addBannerData()
    {
        $backUrl = $_GET['backUrl'];
        $table = $_GET['table'];
        $controller = $_GET['controller'];
        $id = $_POST['id'];
        $sql = D($table);
        if ($sql->create()) {
            if (empty($id)) { //添加
                $sql->id = NULL;
                $sql->banner_addtime = time();
                $sql->banner_updatetime = time();
                $result = $sql->add();
            } else {  //修改
                $sql->banner_updatetime = time();
                $result = $sql->save();
            }
            if ($result) {
                $this->success('编辑成功！', U($controller . '/' . $backUrl ));
            }
        } else {
            $this->error($sql->getError(), $jumpUrl = '', $ajax = true);
        }
    }


    //删除
    public function deleteBanner()
    {
        $table = $_POST['table'];
        $ids = $_POST['delID'];
        $sql = M($table);
        if (strlen($ids) > 0) {
            $ids = substr($ids, 0, strlen($ids) - 1);
        }
        //删除图片
        $map['id'] = array('in', $ids);
        $GroupImg_list = $sql->where($map)->select();
        foreach ($GroupImg_list as $value) {
//            $file = ($_SERVER["DOCUMENT_ROOT"] . 'Uploads/Manage/' . $value["banner_img"]);
            $file = ('Uploads/Manage/' . $value["banner_img"]);
            if (file_exists($file)) {
                @unlink($file);
            }
        }
        return $Result = $sql->delete($ids);
    }

}

?>