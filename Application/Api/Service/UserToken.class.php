<?php
/**
 * Created by 有情人好聚好散.
 * Author: ASang
 * Date: 18-8-20
 * Time: 下午3:50
 */

namespace Api\Service;


use Api\Exception\CacheException;
use Api\Exception\UserException;
use Api\Model\TokenModel;
use Api\Model\UserModel;
use Api\Controller\CommonController;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods:POST,GET");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

class UserToken extends Token
{


    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = C('WX.app_id');
        $this->wxAppSecret = C('WX.app_secret');
        $this->wxLoginUrl = sprintf(
            C('WX.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    //获取用户信息
    public function getUserOpenID()
    {
//        {
//            "session_key": "NxfPakwhskkq5KEYo450Fg==",
//            "openid": "oLOeZ5T6QdV9nKPFnn1pe9Bl8JYY"
//        }
        if (!$this->code) {
            $result = (new UserException([
                'code' => 70004,
                'msg' => 'code为空'
            ]))->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die; //抛出异常
        }

        $result = curl_get($this->wxLoginUrl);

        $wxResult = json_decode($result, true);

        if ($wxResult['errcode']) {
            $result = (new UserException([
                'code' => 70001,
                'msg' => $wxResult
            ]))->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die; //抛出异常
        }

        $this->checkOpenID($wxResult);

        return $wxResult['openid'];
    }

    //判断数据库中是否存在该openid
    private function checkOpenID($wxResult)
    {
        $userModel = new UserModel();
        $map['openid'] = $wxResult['openid'];
        $user = $userModel->where($map)->find();
        if ($user) {
            $userModel->session_key = $wxResult['session_key'];
            $where['openid'] = $wxResult['openid'];
            $userModel->where($where)->save();
        } else {
            $userModel->openid = $wxResult['openid'];
            $userModel->session_key = $wxResult['session_key'];
            $userModel->add();
        }
        return true;
    }


    //微信登陆 准备数据
    public function wxGetUserInfo($wxResult)
    {

        $this->checkParameter($wxResult);

        $user = (new UserModel())->getByOpenID($wxResult['openID']);

        if ($user['addTime']) { //如果存在 更新用户 取出uid
            $uid = $this->wxUpdateUser($wxResult, $user['id'],1);
        } else { //不存在  插入新用户
            $uid = $this->wxUpdateUser($wxResult, $user['id'],2);
        }
        //生成token  写入数据库
        $token = $this->saveToken($uid);
        //令牌返回
        return $token;

    }

    //验证参数
    private function checkParameter($wxResult)
    {
        if (!is_array($wxResult)) {
            $result = (new UserException([
                'code' => 70002,
                'msg' => '用户信息不是数组'
            ]))->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die;
        }
        if (!$wxResult['openID']) {
            $result = (new UserException([
                'code' => 70003,
                'msg' => 'openid为空'
            ]))->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die;
        }
        return true;
    }

    //写入数据库
    private function saveToken($uid)
    {
        $key = self::generateToken();  //令牌 key
        $TokenModel = new TokenModel();
        $token = $TokenModel->where("uid=$uid")->find();
        if ($token) {
            $TokenModel->token = $key;
            $TokenModel->token_time = time();
            $result = $TokenModel->where("uid=$uid")->save();
        } else {
            $TokenModel->token = $key;
            $TokenModel->uid = $uid;
            $TokenModel->token_time = time();
            $result = $TokenModel->add();
        }

        if (!$result) {
            $result = (new UserException())->getException();
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die; //抛出异常
        }

        return $key;
    }

    //更新用户  微信
    private function wxUpdateUser($wxResult, $uid, $type)
    {
        $User = M('user');
        $data['openid'] = $wxResult['openID'];
        $data['nickName'] = encodeNickName($wxResult['nickName']);
        $data['avatarUrl'] = $wxResult['avatarUrl'];
        $data['city'] = $wxResult['city'];
        $data['province'] = $wxResult['province'];
        $data['country'] = $wxResult['country'];
        $data['gender'] = $wxResult['gender'];
        if($type == 2){
            $data['addTime'] = time();
        }
        $data['lastTime'] = time();
        $map['id'] = $uid;
        $User->where($map)->save($data);
        return $uid;
    }


}