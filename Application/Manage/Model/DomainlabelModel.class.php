<?php
namespace Manage\Model;
use Think\Model\RelationModel;
class DomainlabelModel extends RelationModel{
	protected $_link = array(

	);

    protected $_validate = array(
        array('domainlabel_title','require','标题不能为空'),
        array('domainlabel_img','require','轮播图不能为空'),
    );

}