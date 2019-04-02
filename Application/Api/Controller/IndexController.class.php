<?php

namespace Api\Controller;

use Api\Exception\SuccessException;
use Api\Model\IndexModel;
use Api\Service\Token;
use Think\Controller;

class IndexController extends CommonController
{
    //重定向
	public function index(){
		 header("location:".C('HOST')."/manage.php");
	}


    //收集formid
    public function collectFormID()
    {
        $formID = $_POST['formID'];
        $indexModel = new IndexModel();
        $result = $indexModel->addFormID($formID);
        if ($result) {
            $this->ajaxReturn(
                (new SuccessException([
                    'msg' => '收集formID成功'
                ]))->getException()
            );
        }
    }


    //微信模板消息
    public function checkSignature()
    {

        if (isset($_GET['echostr'])) {  //当有值时 校验服务器地址URL
            $this->valid();
        } else {
            $this->smallWXmessage();
        }

    }

    //校验服务器地址URL
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'sang123456';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            echo 'success';
        } else {
            return false;
        }
    }


    //推送模板消息
    public function smallWXmessage()
    {
        $indexModel = new IndexModel();
        $user_id = Token::getCurrentUid();

        //openid
        $openID = $this->$indexModel($user_id);

        //获取access_token
        $access_token = $this->getAccessToken();

        //formID
        $formID = $indexModel->getFormID($openID);

        $data = <<<END
            {
              "touser": "$openID",
              "template_id": "2hVO8exTyQM-FNeXCcDPBN6IXppEFwfeD6fynYkQgpU",
              "page": "index?foo=bar",
              "form_id": "$formID",
              "data": {
                  "keyword1": {
                      "value": "339208499"
                  },
                  "keyword2": {
                      "value": "2015年01月05日 12:30"
                  },
                  "keyword3": {
                      "value": "粤海喜来登酒店"
                  } ,
                  "keyword4": {
                      "value": "广州市天河区天河路208号"
                  }
              },
              "emphasis_keyword": "keyword1.DATA"
            }
END;
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $access_token;
        //post请求url
        $data = $this->getHttpArray($url, $data);

        $indexModel->delExpirationFormID($formID);

        return $data;
    }


    private function getHttpArray($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   //没有这个会自动输出，不用print_r();也会在后面多个1
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $out = json_decode($output);
        return $out;
    }


    //响应消息
//    public function responseMsg($get)
//    {
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
//        if (!empty($postStr) && is_string($postStr)) {
//
//            $postArr = json_decode($postStr, true);
////            $this->printLog($postArr);
//
//            if (!empty($postArr['MsgType']) && $postArr['MsgType'] == 'miniprogrampage') {
//                //获取access_token
//                $access_token = $this->getAccessToken();
//
//                //openid
//                $openID = $get['openid'];
//
//                //发送消息
//                $messageUrl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
//                $ThumbUrl = $postArr['ThumbUrl'];
//                $URL = "https://album.icpnt.com/api/user/albumShare?id=" . $this->interceptNumber($postArr['PagePath']);
//                $messageData = <<<EOF
//                    {
//
//                        "touser": "$openID",
//                        "msgtype": "link",
//                        "link": {
//                              "title": "点此进入，即可分享朋友圈",
//                              "description": "打开页面，点击右上角的【...】，分享到朋友圈",
//                              "url": "$URL",
//                              "thumb_url": "$ThumbUrl"
//                        }
//                    }
//EOF;
////                $this->printLog($this->interceptNumber($postArr['PagePath']));
//
//                $sendCustomerMessage = $this->test_curl($messageUrl, $messageData);
////                echo 'success';
//
//            }
//        }
//
//    }

    //获取access_token  将获取access_token存放在session中
    public function getAccessToken()
    {
        if ($_SESSION['access_token'] && $_SESSION['expire_time'] > time()) {  //如果access_token在session中并且没有过期
            return $_SESSION['access_token'];
        } else { //如果access_token不存在或者已经过期 重新获取access_token并存入session中
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . C('WX.app_id') . '&secret=' . C('WX.app_secret');
            $access_token_array = $this->http_curl($url);
            $access_token = $access_token_array['access_token'];
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expire_time'] = time() + 7200;
            return $access_token;
        }
    }


    private function interceptNumber($PagePath)
    {
        $patterns = "/\d+/"; //第一种
        preg_match_all($patterns, $PagePath, $arr);
        return $arr[0][0];
    }


}