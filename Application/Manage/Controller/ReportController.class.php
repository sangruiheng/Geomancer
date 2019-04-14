<?php

namespace Manage\Controller;

use Manage\Model\ArticlereportModel;
use Think\Controller;

class ReportController extends CommonController
{

    public function reportList()
    {

        $articleReportModel = new ArticlereportModel();

        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('articlereport', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $articleReport = $articleReportModel->relation(array('article', 'user', 'feedback'))->where($map)->order('id desc')->page($p . ',10')->select();
        foreach ($articleReport as &$value) {
            $value['status_name'] = $value['status'] == 0 ? '待处理' : '已处理';
        }
//        print_r($articleReport);
        $count = $articleReportModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $articleReport);
        $this->display();


    }


    //举报信息详情
    public function getReportInfo()
    {
        $report_id = $_GET['report_id'];
        $articleReportModel = new ArticlereportModel();
        $map['id'] = $report_id;
        $articleReport = $articleReportModel->relation(array('article', 'user', 'feedback'))->where($map)->find();
        $this->assign('articleReport', $articleReport);
        $this->display();

    }

    //修改举报信息状态
    public function editReportStatus()
    {
        $report_id = $_POST['report_id'];
        $articleReportModel = new ArticlereportModel();
        $map['id'] = $report_id;
        $articleReport = $articleReportModel->field('id,status')->where($map)->find();
        if($articleReport['status'] != 0){
                $this->ajaxReturn([
                    'code' => 400,
                    'msg' => '当前状态已经是已处理',
                ]);

        }
        $articleReportModel->status = 1;
        $articleReport = $articleReportModel->where($map)->save();
        if($articleReport){
            $this->ajaxReturn([
                'code' => 200,
                'msg' => 'success',
                'data' => $articleReport
            ]);
        }
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
                $this->success('编辑成功！', U($controller . '/' . $backUrl));
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