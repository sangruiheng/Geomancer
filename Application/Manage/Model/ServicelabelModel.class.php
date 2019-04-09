<?php
namespace Manage\Model;
use Think\Model\RelationModel;
class ServicelabelModel extends RelationModel{
	protected $_link = array(

	);

    protected $_validate = array(
        array('servicelabel_title','require','标题不能为空'),
        array('servicelabel_img','require','图片不能为空'),
    );

}