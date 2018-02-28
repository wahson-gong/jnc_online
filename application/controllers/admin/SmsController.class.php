<?php

/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018/2/23
 * Time: 14:24
 */
class SmsController extends BaseController
{
    public function indexAction(){
        include CUR_VIEW_PATH ."Ssms".DS."sms_list.html";
    }
    public function sendAction(){
        header("Content-type: text/html; charset=uft-8");
        date_default_timezone_set('PRC'); //设置默认时区为北京时间
        //短信接口用户名 $uid
        $uid = 104395;
        //短信接口密码 $passwd
        $pwd = md5('VJZfuw');
        //接入号
        $no = 106910134395;
        //===========获取用户信息===============
        $page = isset($_GET['page'])?$_GET['page']:1;
        $start = ($page-1)*500;
        $qi = new ModelNew();
        $date = $qi->M("qh")->where(['zhuangtai'=>1])->one();
        $target = $date['zhuti'];
        $start_time = strtotime($date['kaishishijian']);
        $start_date = date("m月d日 H:i",$start_time);
        $model = new ModelNew();
        $lists = $model->findBySql("select *from sl_wdyh where qihao={$date['qihao']} limit $start,500");
        foreach ($lists as $k=>$v){
            $tel = $v['yonghuming'];
            $msg = "【剑南春】办宴会，赢{$target}之旅。您的奖券号码为：{$v['jiangquanhaoma']}。{$start_date}“剑南春俱乐部”官方微信邀您直播见证，玩大转盘还能领取大礼包！退订回T";
            $arr['msg'] = $msg;
            $msg = urlencode($msg);
            $gateway = "http://119.23.114.82:6666/cmppweb/sendsms?uid={$uid}&pwd={$pwd}&mobile={$tel}&srcphone={$no}&msg={$msg}";
            $result = file_get_contents($gateway);
            if ($result==0){
                flush();
                if ($k%50==0&&$k!=0){
                    sleep(0.5);
                }
            }else{
                $error = new ModelNew();
                $arr['dianhua'] = $tel;
                $arr['xingming'] = $v['xingming'];
                $arr['type'] = "邀请短信";
                $arr['code'] = $result;
                $arr['qihao'] = $date['qihao'];
                $error->M('sms')->insert($arr);
            }

        }
        if ($lists){
            $page = $page+1;
            return $this->jump("index.php?p=admin&c=sms&a=send&page=$page","",0);
        }else{
            echo 2;
        }
        //========================================
    }

    public function sorryAction(){
        header("Content-type: text/html; charset=uft-8");
        date_default_timezone_set('PRC'); //设置默认时区为北京时间
        //短信接口用户名 $uid
        $uid = 104395;
        //短信接口密码 $passwd
        $pwd = md5('VJZfuw');
        $tel = 18512833695;
        //接入号
        $no = 106910134395;
        //===========获取用户奖券号===============
        $page = isset($_GET['page'])?$_GET['page']:1;
        $start = ($page-1)*500;
        $qi = new ModelNew();
        $date = $qi->M("qh")->where(['zhuangtai'=>1])->one();
        $model = new ModelNew();
        $lists = $model->findBySql("select *from sl_wdyh where qihao={$date['qihao']} GROUP BY yonghuming limit $start,500");
        $good = new ModelNew();
        $rows = $good->M('zjdj')->where(['huojiangqishu'=>$date['qihao']])->all();

        foreach ($lists as $k=>$v){
            foreach ($rows as $val){
                if ($v['yonghuming']!=$val['yonghuming']){
                    $tel = $v['yonghuming'];
                    $code = substr($v['qihao'],-1);
                    $msg = "【剑南春】抱歉，“剑南春2018环球极致之旅”第{$code}期直播中您未中旅游大奖，但我们还准备了其他厚礼，请登陆“剑南春俱乐部”官方微信，大转盘礼品任您拿！";
                    $arr['msg'] = $msg;
                    $msg = urlencode($msg);
                    $gateway = "http://119.23.114.82:6666/cmppweb/sendsms?uid={$uid}&pwd={$pwd}&mobile={$tel}&srcphone={$no}&msg={$msg}";
                    $result = file_get_contents($gateway);
                    if ($result==0){
                        echo "客户".$v['xingming'].",信息发送成功<br>";
                        flush();
                        if ($k%49==0&&$k!=0){
                            sleep(0.5);
                        }
                    }else{
                        $error = new ModelNew();
                        $arr['dianhua'] = $tel;
                        $arr['xingming'] = $v['xingming'];
                        $arr['type'] = "未中奖短信";
                        $arr['qihao'] = $date['qihao'];
                        $arr['code'] = $result;
                        $error->M('sms')->insert($arr);
                    }
                }
            }
        }
        if ($lists){
            $page = $page+1;
            return $this->jump("index.php?p=admin&c=sms&a=sorry&page=$page","",0);
        }else{
            echo 2;
        }
        //========================================
    }

    public function congratulationAction(){
        header("Content-type: text/html; charset=uft-8");
        date_default_timezone_set('PRC'); //设置默认时区为北京时间
        //短信接口用户名 $uid
        $uid = 104395;
        //短信接口密码 $passwd
        $pwd = md5('VJZfuw');
        $tel = 18512833695;
        //接入号
        $no = 106910134395;
        //===========获取用户奖券号===============

        $qi = new ModelNew();
        $date = $qi->M("qh")->where(['zhuangtai'=>1])->one();
        $target = $date['zhuti'];
        $model = new ModelNew();
        $lists = $model->M('zjdj')->where(['huojiangqishu'=>$date['qihao']])->all();
        foreach ($lists as $k=>$v){
            $tel = $v['yonghuming'];
            $code = substr($v['qihao'],-1);
            $msg = "【剑南春】恭喜！您的奖券号码".$v['zhongjianghaoma']."在“剑南春2018环球极致之旅”第{$code}期直播中抽中{$target}之旅大奖，请登陆“剑南春俱乐部”官方微信，领取您的奖品！退订回T";
            $arr['msg'] = $msg;
            $msg = urlencode($msg);
            $gateway = "http://119.23.114.82:6666/cmppweb/sendsms?uid={$uid}&pwd={$pwd}&mobile={$tel}&srcphone={$no}&msg={$msg}";
            $result = file_get_contents($gateway);
            if ($result==0){
                echo "客户".$v['xingming'].",信息发送成功<br>";
                flush();
                if ($k%49==0&&$k!=0){
                    sleep(0.5);
                }
            }else{
                $error = new ModelNew();
                $arr['dianhua'] = $tel;
                $arr['xingming'] = $v['xingming'];
                $arr['qihao'] = $date['qihao'];
                $arr['type'] = "中奖短信";
                $arr['code'] = $result;
                $error->M('sms')->insert($arr);
            }

        }
        echo 2;
        //========================================
    }

    public function secondAction(){
        header("Content-type: text/html; charset=uft-8");
        date_default_timezone_set('PRC'); //设置默认时区为北京时间
        //短信接口用户名 $uid
        $uid = 104395;
        //短信接口密码 $passwd
        $pwd = md5('VJZfuw');
        //接入号
        $no = 106910134395;
        //===========获取用户信息===============
        $page = isset($_GET['page'])?$_GET['page']:1;
        $start = ($page-1)*500;
        $qi = new ModelNew();
        $numbers = $qi->findBySql("select *from sl_sms  limit $start,500");
        foreach ($numbers as $k=>$val){
            $tel = $val['dianhua'];
            $msg = $val['msg'];
            $msg = urlencode($msg);
            $gateway = "http://119.23.114.82:6666/cmppweb/sendsms?uid={$uid}&pwd={$pwd}&mobile={$tel}&srcphone={$no}&msg={$msg}";
            $result = file_get_contents($gateway);
            if ($result==0){
                echo $tel."发送成功";
                flush();
                if ($k%49==0&&$k!=0){
                    sleep(0.5);
                }
                $del = new ModelNew();
                $del->query("delete from sl_sms WHERE id={$val['id']}");
            }else{
                $error = new ModelNew();
                $arr['code'] = $result;
                $error->query("update sl_sms set code={$result} WHERE id={$val['id']}");
            }
        }

        if ($numbers){
            $page = $page+1;
            return $this->jump("index.php?p=admin&c=sms&a=send&page=$page","",0);
        }else{
            return $this->jump("index.php?p=admin&c=sms&a=index","发送完成",3);
        }
    }

    public function smsAction(){
        header("Content-type: text/html; charset=uft-8");
        date_default_timezone_set('PRC'); //设置默认时区为北京时间
        //短信接口用户名 $uid
        $uid = 104395;
        //短信接口密码 $passwd
        $pwd = md5('VJZfuw');
        //接入号
        $no = 106910134395;
        //===========获取用户信息===============
        $tel = $_POST['tel'];
        $msg = $_POST['msg'];
        $msg = urlencode($msg);
        $gateway = "http://119.23.114.82:6666/cmppweb/sendsms?uid={$uid}&pwd={$pwd}&mobile={$tel}&srcphone={$no}&msg={$msg}";
        $result = file_get_contents($gateway);
        if ($result==0){
            $this->jump("?p=admin&c=sms&a=index","发送成功",3);
        }else{
            $this->jump("?p=admin&c=sms&a=index","发送失败".$result,3);
        }

    }

    public function testAction(){
        $page = isset($_GET['page'])?$_GET['page']:1;
        $start = ($page-1)*500;
        $qi = new ModelNew();
        $date = $qi->M("qh")->where(['zhuangtai'=>1])->one();
        $target = $date['zhuti'];
        $start_time = strtotime($date['kaishishijian']);
        $start_date = date("m月d日 H:i",$start_time);
        $model = new ModelNew();
        $lists = $model->findBySql("select *from sl_wdyh where qihao={$date['qihao']} limit $start,500");
        foreach ($lists as $k=>$v){
            $tel = $v['yonghuming'];
            $msg = "【剑南春】办宴会，赢{$target}之旅。您的奖券号码为：{$v['jiangquanhaoma']}。{$start_date}“剑南春俱乐部”官方微信邀您直播见证，玩大转盘还能领取大礼包！退订回T";
            $arr['msg'] = $msg;
            $msg = urlencode($msg);
        }
    }
}