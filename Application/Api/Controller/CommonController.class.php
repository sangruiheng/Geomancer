<?php

namespace Api\Controller;

use Api\Model\UserformidModel;
use Api\Model\UserModel;
use Api\Service\Token;
use Think\Controller;

Vendor('PHPMailer.src.PHPMailer');
Vendor('PHPMailer.src.SMTP');

class CommonController extends Controller
{
    //请求接口验证
    //域名验证

    public function _initialize()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods:POST,GET");
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    }

    /*字符串截断函数+省略号*/
    function subtext($text, $length)
    {
        if (mb_strlen($text, 'utf8') > $length)
            return mb_substr($text, 0, $length, 'utf8') . '...';
        return $text;
    }


    public function return_ajax($code = 400, $msg = '', $data = array())
    {
        $this->ajaxReturn(array('code' => $code, 'msg' => $msg, 'data' => $data));
    }


    //随机数
    public function createNonce($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


    /**
     * 打印数据
     * @param  string $txt 日志记录
     * @param  string $file 日志目录
     * @return
     */
    public function printLog($txt = "", $file = "ceshi.log")
    {
        $myfile = fopen($file, "a+");
        $StringTxt = "[" . date("Y-m-d H:i:s") . "]" . var_export($txt, true) . "\n";
        fwrite($myfile, $StringTxt);
        fclose($myfile);

    }











}