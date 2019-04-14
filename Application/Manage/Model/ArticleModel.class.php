<?php
namespace Manage\Model;
use Think\Model\RelationModel;
class ArticleModel extends RelationModel{
	protected $_link = array(
        'geomancer' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'geomancer',//要关联的表名
            'foreign_key' => 'geomancer_id', //本表的字段名称
//            'as_fields' => 'typeName:typeName',  //被关联表中的字段名：要变成的字段名
//            'relation_deep' => 'productAttr',   //多表关联  关联第三个模型的名称
        ),
	);

    protected $_validate = array(

    );


    //上传视频
    public static function uploadArticleVideo()
    {
        $config = array(
            'mimes' => array(), //允许上传的文件MiMe类型
            'maxSize' => 0, //上传的文件大小限制 (0-不做限制)
            'exts' => array('mp4', 'mp3', 'wma', 'wav'), //允许上传的文件后缀
            'autoSub' => true, //自动子目录保存文件
            'subName' => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => './Uploads/Manage/', //保存根路径
            'savePath' => '',//保存路径
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info = $upload->upload();
        if (!$info) {
            return $upload->getError();
        } else {
            foreach ($info as $file) {
                $data['url'] = $file['savepath'] . $file['savename'];
            }
            return $data;
        }
    }


    //添加文章图片
    public function AddArticleImg($aritcleImgIDS, $article_id)
    {
        $articleimgModel = new ArticleimgModel();
        for ($i = 0; $i < count($aritcleImgIDS); $i++) {
            $articleimgModel->article_id = $article_id;
            $articleimgModel->article_img = substr($aritcleImgIDS[$i], 16);
            $result = $articleimgModel->add();
        }
        return $result;
    }

}