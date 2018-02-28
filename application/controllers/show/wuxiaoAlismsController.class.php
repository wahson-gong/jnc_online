<?php
header("Content-type:text/html;charset=utf-8");
include "application/config/sms.php";
class AlismsController extends BaseController
{
     public function sendAction(){
             $tel = $_REQUEST['tel'];
             $code=rand(1000,9999);

             $response = SmsDemo::sendSms(
                 "阿里云短信测试专用", // 短信签名
                 "SMS_123738093", // 短信模板编号
                 $tel, // 短信接收者
                 Array(  // 短信模板中字段的值
                     "code"=>$code,
                 )
             );
            $_SESSION["a".$tel] = $code;
             if ($response->Code=='OK'){
                 echo "发送成功";
             }else{
                 echo "'发送失败";
             }
     }


     public function qunsendAction(){
         $model=new ModelNew('phone');
         $rs=$model->findBySql("select *from sl_phone limit 0,2");
         $phone=[];
         $qianming=[];
         $nicheng=[];
        $name=['111111','1111111'];
         foreach ($rs as $k=>$v){
             $phone[$k]=$v['dianhua'];
             $qianming[$k]=$v['qianming'];
             $nicheng[$k]=array(
               "code"=>$v['xingming'],
             );
         }

//var_dump($name);echo '<hr>';
//var_dump($phone);die;
         $response=SmsDemo::sendBatchSms($phone,$qianming,$nicheng);
         if ($response->Code=='OK'){
             echo "发送成功";
         }else{
             echo "'发送失败";
         }
     }

}