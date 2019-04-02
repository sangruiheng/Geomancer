<?php
/**
 * Created by 有情人好聚好散.
 * Author: ASang
 * Date: 18-8-9
 * Time: 上午9:50
 */

namespace Api\Model;


use Api\Exception\UserException;
use Api\Service\Token;
use Think\Model\RelationModel;

Vendor('Wxphone.wxBizDataCrypt');

class IndexModel extends RelationModel
{
    protected $_link = array(
        'memberdetail' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'memberdetail',//要关联的表名
            'foreign_key' => 'user_id', //外键的字段名称
            'mapping_fields' => 'mambercard_id,membercard_endtime',  //被关联表中的字段名：要变成的字段名
//            'relation_deep'    =>    'productattr',   //多表关联  关联第三个表的名称
        ),
    );


    //收集formID
    public function addFormID($formID)
    {
        $user_id = Token::getCurrentUid();
        $userFormidModel = new UserformidModel();
        $sevenDay = time() + 3600 * 24 * 7;
        $userFormidModel->user_id = $user_id;
        $userFormidModel->form_id = $formID;
        $userFormidModel->expiration_date = $sevenDay;
        $userFormidModel->add();
        return true;
    }

    //删除过期的formID和使用过的formID
    public function delExpirationFormID($formID)
    {
        $userFormidModel = new UserformidModel();
        $map['expiration_date'] = array('ELT', time());
        $userFormidModel->where($map)->delete();
        if ($formID) {
            $where['form_id'] = $formID;
            $userFormidModel->where($where)->delete();
        }
        return true;
    }

    //获取formID
    public function getFormID($openID)
    {
        $userFromidModel = new UserformidModel();
        $user = $this->getUser($openID);
        $map['expiration_date'] = array('GT', time());
        $map['user_id'] = $user['id'];
        $userFormid = $userFromidModel->where($map)->find();
        return $userFormid['form_id'];
    }

    //根据openID获取user
    public function getUser($openID)
    {
        $userModel = new UserModel();
        $map['openid'] = $openID;
        $user = $userModel->where($map)->find();
        return $user;
    }

    //根据userid获取openid
    public function getOpenID($user_id){
        $userModel = new UserModel();
        $user = $userModel->find($user_id);
        return $user;
    }
}
