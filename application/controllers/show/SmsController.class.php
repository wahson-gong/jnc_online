<?php

/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018/1/3
 * Time: 16:45
 */
header("Content-type: text/html; charset=gbk2312");
class SmsController extends BaseController
{

    public function smsAction(){
//        header("Content-type: text/html; charset=gbk2312");
        date_default_timezone_set('PRC'); //
        $uid = 'SLKJ006499';
        $pwd = '123456';
//        $tel = $_POST['tel'];
        $tel = $_REQUEST['tel'];
        $message=rand(1000,9000);
        $_SESSION["a".$tel] = $message;
        $msg = rawurlencode(mb_convert_encoding($message, "gb2312", "utf-8"));
        $msg=$msg.'¡¾³É¶¼Ë¼ÀÖ¡¿';
        $gateway = "http://mb345.com:999/ws/BatchSend2.aspx?CorpID={$uid}&Pwd={$pwd}&Mobile={$tel}&Content={$msg}&SendTime=&cell=";
        $result = file_get_contents($gateway);
        if ($result>0){
            echo "success";
        }else{
            echo $result;
        }
    }
}