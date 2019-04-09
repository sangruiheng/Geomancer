<?php
namespace Manage\Model;
use Think\Model\RelationModel;
class FeedbackModel extends RelationModel{
	protected $_link = array(

	);

    protected $_validate = array(
        array('feedback_title','require','标题不能为空'),
    );

}