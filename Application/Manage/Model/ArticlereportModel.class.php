<?php
namespace Manage\Model;
use Think\Model\RelationModel;
class ArticlereportModel extends RelationModel{
	protected $_link = array(
        'article' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'article',//要关联的表名
            'foreign_key' => 'article_id', //本表的字段名称
//            'as_fields' => 'typeName:typeName',  //被关联表中的字段名：要变成的字段名
            'relation_deep' => 'geomancer',   //多表关联  关联第三个模型的名称
        ),
        'user' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'user',//要关联的表名
            'foreign_key' => 'user_id', //本表的字段名称
//            'as_fields' => 'typeName:typeName',  //被关联表中的字段名：要变成的字段名
//            'relation_deep' => 'productAttr',   //多表关联  关联第三个模型的名称
        ),
        'feedback' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'feedback',//要关联的表名
            'foreign_key' => 'feedback_id', //本表的字段名称
//            'as_fields' => 'typeName:typeName',  //被关联表中的字段名：要变成的字段名
//            'relation_deep' => 'productAttr',   //多表关联  关联第三个模型的名称
        ),
	);

    protected $_validate = array(
        array('article_report_content','require','内容不能为空'),
    );

}