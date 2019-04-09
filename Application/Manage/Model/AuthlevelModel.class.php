<?php
namespace Manage\Model;
use Think\Model\RelationModel;
class AuthlevelModel extends RelationModel{
	protected $_link = array(

	);

    protected $_validate = array(
        array('authlevel_title','require','标题不能为空'),
        array('authlevel_level','require','等级不能为空'),
    );

}