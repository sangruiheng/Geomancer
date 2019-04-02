<?php
/**
 * Created by 有情人好聚好散.
 * Author: ASang
 * Date: 18-8-8
 * Time: 上午11:58
 */

namespace Api\Controller;


use Api\Exception\UserException;
use Api\Model\UserModel;
use Api\Service\Token;
use Api\Service\UserToken;


class UserController extends CommonController
{

    //获取openid 并存入数据库
    public function getOpenID($code = '')
    {
        $userToken = new UserToken($code);
        $openID = $userToken->getUserOpenID();
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'openID' => $openID
        ]);
    }


    //用户登陆 返回token
    public function wxLogin()
    {
        //openID nickName avatarUrl city...
        $userToken = new UserToken();
        $wxResult = json_decode($_POST['userInfo'], true);

        $token = $userToken->wxGetUserInfo($wxResult);
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'Token' => $token
        ]);
    }

    //获取用户手机号
    public function wxPhone()
    {
        $userModel = new UserModel();
        $encryptedData = $_POST['encryptedData'];
        $iv = $_POST['iv'];
        $openID = $_POST['openID'];
        $wxPhone = $userModel->getWxPhone($encryptedData, $iv, $openID);
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $wxPhone['phoneNumber']
        ]);
    }

    //个人中心
    public function PersonalCenter()
    {
        $this->uid = Token::getCurrentUid();
        $userModel = new UserModel();
        $user = $userModel->field('nickName,avatarUrl,lastTime')->find($this->uid);
        $user['lastTime'] = date('Y-m-d H:i:s',$user['lastTime']);
        $user['nickName'] = urldecode($user['nickName']);
        if (!$user) {
            $this->ajaxReturn((new UserException())->getException());
        }
        $this->ajaxReturn([
            'code' => 200,
            'msg' => 'success',
            'data' => $user
        ]);

    }


}
