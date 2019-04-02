<?php
/**
 * Created by 有情人好聚好散.
 * Author: ASang
 * Date: 18-8-9
 * Time: 上午9:50
 */

namespace Api\Model;


use Api\Exception\UserException;
use Think\Model\RelationModel;

Vendor('Wxphone.wxBizDataCrypt');

class UserModel extends RelationModel
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


    public function getByOpenID($openid)
    {
        $result = M('user')->where("openid='$openid'")->find();
        return $result;
    }


    public function getWxPhone($encryptedData, $iv, $openID)
    {
        if (!$encryptedData) {
            $result = (new UserException([
                'code' => 70006,
                'msg' => 'encryptedData为空'
            ]))->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die;
        }
        if (!$iv) {
            $result = (new UserException([
                'code' => 70005,
                'msg' => 'iv为空'
            ]))->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die;
        }
        if (!$openID) {
            $result = (new UserException([
                'code' => 70003,
                'msg' => 'openid为空'
            ]))->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die;
        }

        $appid = C('WX.app_id');
        $sessionKey = self::getSession_key($openID);

        $pc = new \WXBizDataCrypt($appid, $sessionKey);

        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ($errCode == 0) {
            $data = json_decode($data, true);
            $this->saveUserPhone($openID, $data['phoneNumber']);
            return $data;
        } else {
            return $errCode;
        }
    }

    //将手机号存入user
    private function saveUserPhone($openID, $wxPhone)
    {
        $userModel = new UserModel();
        $userModel->wx_phone = $wxPhone;
        $map['openid'] = $openID;
        $userModel->where($map)->save();
        return true;
    }

    //获取session_key
    private static function getSession_key($openID)
    {
        $where['openid'] = $openID;
        $userModel = new UserModel();
        $user = $userModel->where($where)->find();
        if (!$user) {

            echo json_encode((new UserException([
                'code' => 70007,
                'msg' => 'openid所对应的用户不存在'
            ]))->getException(), JSON_UNESCAPED_UNICODE);
            die;
        }
        return $user['session_key'];

    }

}
