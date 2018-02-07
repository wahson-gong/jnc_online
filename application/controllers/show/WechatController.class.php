<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2017/12/1
 * Time: 15:23
 */
class WechatController extends BaseController
{
    //public $appID = "";
    //public $secret = "";
    //获取openID
    public  function infoAction(){

        if (!isset($_SESSION['openid'])){
            $appID = "wx54dad1a359d51c95";
            //绝对路径
            $callback_url = "http://jnc.cdsile.cn/?c=wechat&a=callback";
            $callback_url  = urlencode($callback_url);
            //1、引导用户进入授权页面同意授权，获取code
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appID}&redirect_uri={$callback_url}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            return $this->jump($url,'',0);
        }
        return  $_SESSION['openid'];
    }
    //授权成功回调
    public function callbackAction(){
        $appID = "wx54dad1a359d51c95";
        $secret = "c6000d22a71be19e0caa9b3877fed7e1";
        //获取code
        $code = $_GET['code'];
        //通过code换取网页授权access_token
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appID}&secret={$secret}&code={$code}&grant_type=authorization_code";
        //获取openID
        $token = file_get_contents($url);
        $json = json_decode($token);
        $_SESSION['openid'] = $json->openid;
        return $this->jump("http://jnc.cdsile.cn/?c=login&a=index",'',0);
    }
    //授权成功回调
    public function infoBackAction(){
        $appID = "wx54dad1a359d51c95";
        $secret = "c6000d22a71be19e0caa9b3877fed7e1";
        //获取code
        $code = $_GET['code'];
        //通过code换取网页授权access_token
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appID}&secret={$secret}&code={$code}&grant_type=authorization_code";
        //获取openID
        $token = file_get_contents($url);
        $json = json_decode($token);
        $_SESSION['openid'] = $json->openid;
        return $this->jump("?c=base&a=openId",'',0);
    }
}