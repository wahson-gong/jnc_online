<?php

/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018/2/23
 * Time: 14:24
 */
class AlismsController extends BaseController
{
    public function sendAction(){
        header("Content-type: text/html; charset=uft8");
        date_default_timezone_set('PRC'); //设置默认时区为北京时间
        //短信接口用户名 $uid
        $uid = 104394;
        //短信接口密码 $passwd
        $pwd = md5('m91TcV');
        $tel = $_POST['tel'];
        //接入号
        $no = 106910134394;
        $message=rand(1000,9000);
        $_SESSION["a".$tel] = $message;
        $msg = "【剑南春】您的短信验证码是：{$message}，90秒内有效，登陆后参加“剑南春极致之旅”活动。";
        $msg = urlencode($msg);
        $gateway = "http://119.23.114.82:6666/cmppweb/sendsms?uid={$uid}&pwd={$pwd}&mobile={$tel}&srcphone={$no}&msg={$msg}";
        $result = file_get_contents($gateway);
        if ($result==0){
            echo "发送成功";
        }else{
            echo "发送失败";
        }
    }
}