<?php

namespace Manage\Controller;

use Manage\Model\ArticleimgModel;
use Manage\Model\ArticleModel;
use Manage\Model\BannerModel;
use Think\Controller;

class ArticleController extends CommonController
{

    public function articleList()
    {

        $articleModel = new ArticleModel();

        if (!empty($_GET['keyWord'])) {
            $map = $this->Search('article', $_GET['keyWord']);
        }
        $p = $_GET['p'];
        if (empty($p)) {
            $p = 1;
        }
        $article = $articleModel->relation('geomancer')->where($map)->order('id desc')->page($p . ',10')->select();
//        print_r($user);
        $count = $articleModel->where($map)->count();
        $Page = getpage($count, 10);
        foreach ($map as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $this->assign('page', $Page->show());
        $this->assign('list', $article);
        $this->display();

    }


    public function uploadArticleVideo()
    {
        $articleModel = new ArticleModel();
        if (!empty($_FILES)) {
            $info = $articleModel::uploadArticleVideo();
            if ($info) {
                $this->ajaxReturn([
                    'code' => 200,
                    'msg' => '上传成功',
                    'data' => $info,
                ]);
            }
        } else {
            $this->ajaxReturn([
                'code' => 400,
                'msg' => '上传失败'
            ]);
        }
    }


    public function addArticleData()
    {
        $backUrl = $_GET['backUrl'];
        $table = $_GET['table'];
        $controller = $_GET['controller'];
        $id = $_POST['id'];
        $articleModel = new ArticleModel();
        $sql = D($table);
        if ($sql->create()) {
            if (empty($id)) { //添加
                $sql->id = NULL;
                $sql->article_addtime = time();
                $result = $sql->add();
                if ($_POST['hid']) {
                    $articleModel->AddArticleImg($_POST['hid'], $result);
                }
            } else {  //修改
                $result = $sql->save();
            }
            if ($result) {
                $this->success('编辑成功！', U($controller . '/' . $backUrl));
            }
        } else {
            $this->error($sql->getError(), $jumpUrl = '', $ajax = true);
        }
    }


    //文章详情
    public function getArticleInfo()
    {
        $article_id = $_GET['article_id'];
        $articleModel = new ArticleModel();
        $map['id'] = $article_id;
        $article = $articleModel->relation('geomancer')->where($map)->find();
        print_r($article);
        $this->assign('article', $article);
        $this->display();
    }


    //是否置顶
    public function is_top()
    {
        $rs = M($_POST['table'])->save(array('id' => $_POST['id'], 'is_top' => $_POST['status'], 'top_time' => time()));
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
    public function deleteArticle()
    {
        $table = $_POST['table'];
        $ids = $_POST['delID'];
        $articleImgModel = new ArticleimgModel();
        $arr = [];
        $sql = M($table);
        if (strlen($ids) > 0) {
            $ids = substr($ids, 0, strlen($ids) - 1);
        }

        $map['id'] = array('in', $ids);
        $article = $sql->where($map)->select();
        if ($article) {
            foreach ($article as $value) {
                array_push($arr, $value['id']);
                if($value['article_video']){
                    $file = ('Uploads/Manage/' . $value["article_video"]);
                    if (file_exists($file)) {
                        @unlink($file);
                    }
                }
            }
            $where['article_id'] = array('in', $arr);
            $articleImg = $articleImgModel->where($where)->select();
            if ($articleImg) {
                foreach ($articleImg as $value) {
                    $file = ('Uploads/Manage/' . $value["article_img"]);
                    if (file_exists($file)) {
                        @unlink($file);
                    }
                }
                $articleImg->delete($arr);
            }
            $sql->delete($ids);
        }


        return true;
    }

}

?>