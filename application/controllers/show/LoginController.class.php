<?php
//首页控制器
header("Content-type:text/html;charset=utf-8");
class LoginController extends   BaseController
{
    //index方法


    //第一步:检测用户是否存在资格
    public function usercheckAction(){
        $tel = $_GET['tel'];
        $model=new ModelNew();
        $yhmd=$model->M('user');
        $rs  = $yhmd->findBySql("select count(*) from sl_yhmd WHERE kehudianhua=$tel")[0]['count(*)'];
        if (!$rs){
            echo '9';
        }
    }
    public function indexAction()
    {        //初始化返回参数
        //==================微信openID=======================

        $openid = $this->openIdAction();
        $member = new ModelNew();
        $row = $member->M('user')->where(['openid_wx' => $openid])->one();
        if ($row) {
            //已绑定直接登录
            $_SESSION['xingming'] = $row['xingming'];
            $_SESSION['tel'] = $row['yonghuming'];
            return $this->jump("?c=single&m=index_wdyh", '', 0);
        }
        //========================END============================
        include CUR_VIEW_PATH."templates".DS."m".DS. "index_login.html";
    }

    public function loginAction()
    {
        //前端正则验证非空和验证码非空以及协议阅读
        //1代表电话格式错误 2代表验证码错误 3代表成功 4代表用户已经存在 5.名单不存在此用户
        $request = $_SERVER['REQUEST_METHOD'];
        if ($request=='POST'){
            $tel = $_POST['tel'];
            $yzm = $_POST['yzm'];
            $number=empty($_SESSION["a".$tel])?1111:$_SESSION["a".$tel];
    //        $number=1111;

            $_model = new  ModelNew();
            $user = $_model->M("user");
            $result = $user->findBySql("select count(*) from sl_user WHERE yonghuming=$tel")[0]['count(*)'];

            $yhmd=$_model->M("yhmd");
            $rs     = $yhmd->findBySql("select count(*) from sl_yhmd WHERE kehudianhua=$tel")[0]['count(*)'];

            $dhbl=$_model->M("djbl");

            $rs1    =$dhbl->findBySql("select * from sl_djbl");

            $wdyh   =new model('wdyh');

            $sl     =$_model->M('jqsl');//奖券数量查询

            if($number!=$yzm){
                echo '2';exit;
            }
            if (!$rs){
                echo '5';exit;
            }
            if ($result) {
                $_SESSION['tel']=$tel;
                $model_1 = new ModelNew('user');
                $xingming=$model_1->findBySql("select *from sl_user WHERE yonghuming=$tel")[0]['xingming'];
                $member = new ModelNew();
                $openid = $_SESSION['openid'];
                $re = $member->query("update sl_user set openid_wx='{$openid}' WHERE yonghuming={$tel}");
                $_SESSION['xingming']=$xingming;
                echo '4';
            } else {
                if (empty($number) == true) {
                    echo '1';
                } else {
                    @$zs=$yhmd->findBySql("select * from sl_yhmd WHERE kehudianhua=$tel and zhuangtai=2")[0];
                    if (!$zs){
                        echo '5';exit;
                    }
                    if ($zs['zhuoshu']<=1){
                        echo '5';exit;
                    }
    //              $zs1=$yhmd->where(['kehudianhua'=>$tel])->find()->all();
                    $model = new model('user');
                    $data['yonghuming'] = $tel;
                    $data['xingming']=$zs['kehuxingming'];
                    $_SESSION['xingming']=$data['xingming'];
                    $model->insert($data);
    //                $STR='';
    //                $NUM='';
    //                foreach ($zs1 as $zs){
                    //echo $zs['yanhuidanhao'];
                    $zhuoshu=$zs['zhuoshu'];
                    $yanhuidanhao=$zs['yanhuidanhao'];
                    $shi=$zs['shi'];
                    $sheng=$zs['sheng'];
                    $a=0;
                    $data_wdyh['jifen']='';
                    foreach($rs1 as $v){
                        if ($v['zhuoshuxiao']<=$zhuoshu && $zhuoshu<=$v['zhuoshuda']){
                            $data_wdyh['jifen']=$v['jiangquanshu']*5;
                        }else if ($zhuoshu>50){
                            $data_wdyh['jifen']=12*5;
                        }
                        if ($a<$data_wdyh['jifen']){
                            $a=$data_wdyh['jifen'];
                        }
                    }
                    $data_wdyh['jifen']=$a;
                    $data_wdyh['shi']=$shi;
                    $data_wdyh['sheng']=$sheng;
                    $data_wdyh['yonghuming']=$tel;
                    $data_wdyh['yanhuidanhao']=$yanhuidanhao;
                    $data_wdyh['yanhuishijian']=$zs['yanhuishijian'];
                    $data_wdyh['xingming']=$_SESSION['xingming'];
                    $data_wdyh['yanhuidizhi']=$zs['yanhuidizhi'];

                    $_qh=new ModelNew('qh');

    //                @$result=$_qh->findBySql("select *from sl_qihao WHERE shi LIKE '%".$shi."%'")[0];
                    $quyu=$_qh->findBySql("select *from sl_qh WHERE zhuangtai=1")[0]['kaijiangquyu'];
                    $qihao=$_qh->findBySql("select *from sl_qh WHERE zhuangtai=1")[0]['qihao'];

    //                if ($result){

    //                     $sl_num=$sl->findBySql('select count(*) from sl_jqsl WHERE   diqu="'.$shi.'"')[0]['count(*)'];
                    @$sl_num=$sl->findBySql('select count(*) from sl_jqsl WHERE   diqu="'.$quyu.'"')[0]['count(*)'];


                    if ($sl_num){
                        $sl = new  ModelNew('jqsl');
                        $sl_num_s=$sl->findBySql('select shuliang from sl_jqsl WHERE   diqu="'.$quyu.'"')[0]['shuliang'];//数量

                        switch ($zhuoshu){
                            case 2<=$zhuoshu && $zhuoshu<=5:
                                $data_s2=1;
                                break;
                            case 6<=$zhuoshu && $zhuoshu<=10:
                                $data_s2=2;
                                break;
                            case 11<=$zhuoshu && $zhuoshu<=21:
                                $data_s2=4;
                                break;
                            case 21<=$zhuoshu && $zhuoshu<=35:
                                $data_s2=6;
                                break;
                            case 36<=$zhuoshu && $zhuoshu<=50:
                                $data_s2=9;
                                break;
                            case 50<$zhuoshu :
                                $data_s2=12;
                                break;
                        }

                        $data_sl['shuliang']=$data_s2+$sl_num_s;
                        $sl->where(['diqu'=>$quyu])->update($data_sl);
                        $str="";
                        for ($i=1;$i<=$data_s2;$i++){
                            $sum=$i+$sl_num_s;
                            $a="$quyu-$sum,";
                            $str.="$quyu-$sum,";
                            $jq=$_model->M('jiangquan');
                            $_jq['yanhuishijian']=$zs['yanhuishijian'];
                            $_jq['yonghuming']=$zs['kehudianhua'];
                            $_jq['yanhuidanhao']=$zs['yanhuidanhao'];
                            $_jq['zhuangtai']="待开奖";
    //                               $_jq['laiyuanbianhao']=$zs['laiyuanbianhao'];
                            $_jq['qihao']=$zs['qihao'];
                            $_jq['haoma']=rtrim($a, ',');;
                            $_jq['quyu']=$quyu;
                            $jq->insert($_jq);
                        }
                    }else{
    //                         $sl_num_s=$sl->findBySql('select shuliang from sl_jqsl WHERE   diqu="'.$shi.'"')[0]['shuliang'];
                        $sl=new ModelNew('jqsl');
                        $data_sl['diqu']=$quyu;
                        switch ($zhuoshu){
                            case 2<=$zhuoshu && $zhuoshu<=5:
                                $data_s2=1;
                                break;
                            case 6<=$zhuoshu && $zhuoshu<=10:
                                $data_s2=2;
                                break;
                            case 11<=$zhuoshu && $zhuoshu<=21:
                                $data_s2=4;
                                break;
                            case 21<=$zhuoshu && $zhuoshu<=35:
                                $data_s2=6;
                                break;
                            case 36<=$zhuoshu && $zhuoshu<=50:
                                $data_s2=9;
                                break;
                            case 50<$zhuoshu :
                                $data_s2=12;
                                break;
                        }
                        $str="";
                        for ($i=1;$i<=$data_s2;$i++){
                            $sum=$i;
                            $a="$quyu-$sum,";
                            $str.="$quyu-$sum,";
                            $jq=$_model->M('jiangquan');
                            $_jq['yanhuishijian']=$zs['yanhuishijian'];
                            $_jq['yonghuming']=$zs['kehudianhua'];
                            $_jq['yanhuidanhao']=$zs['yanhuidanhao'];
                            $_jq['zhuangtai']="待开奖";
                            $_jq['qihao']=$zs['qihao'];
                            $_jq['haoma']=$a;
                            $_jq['quyu']=$quyu;
                            $jq->insert($_jq);
                        }
                        $data_sl['shuliang']=$sum;
                        $sl->insert($data_sl);
                    }
//                } else {
//
//                    //-----------------------------------
//                    $_qh1=new ModelNew('qihao');
//                 @   $result=$_qh1->findBySql("select *from sl_qihao WHERE sheng LIKE '%".$sheng."%'")[0];
//
//                    if ($result) {
//                        $sl_num = $sl->findBySql('select count(*) from sl_jqsl WHERE   diqu="'.$sheng.'"')[0]['count(*)'];
//                        if ($sl_num) {
//                            $sl_num_s = $sl->findBySql('select shuliang from sl_jqsl WHERE    diqu="'.$sheng.'"')[0]['shuliang'];//数量
//                            switch ($zhuoshu) {
//                                case 2 <= $zhuoshu && $zhuoshu <= 5:
//                                    $data_s2 = 1;
//                                    break;
//                                case 6 <= $zhuoshu && $zhuoshu <= 10:
//                                    $data_s2 = 2;
//                                    break;
//                                case 11<=$zhuoshu && $zhuoshu<=21:
//                                    $data_s2=4;
//                                    break;
//                                case 21<=$zhuoshu && $zhuoshu<=35:
//                                    $data_s2=6;
//                                    break;
//                                case 36<=$zhuoshu && $zhuoshu<=50:
//                                    $data_s2=9;
//                                    break;
//                                case 50<=$zhuoshu :
//                                    $data_s2=50;
//                                    break;
//                            }
//                            $data_sl['shuliang'] = $data_s2 + $sl_num_s;
//                            $modle=new ModelNew();
//                            $sl=$modle->M('jqsl');
////                          $data_sl['shuliang'] =654564;
//                            $rs=$sl->where(['diqu' => $sheng])->update($data_sl);
//                            $str = "";
//                            for ($i = 1; $i <= $data_s2; $i++) {
//                                $sum = $i + $sl_num_s;
//                                $a = "$sheng-$sum,";
//                                $str .= "$sheng-$sum,";
//                                $jq = $_model->M('jiangquan');
//                                $_jq['yanhuishijian'] = $zs['yanhuishijian'];
//                                $_jq['yonghuming'] = $zs['kehudianhua'];
//                                $_jq['yanhuidanhao'] = $zs['yanhuidanhao'];
//                                $_jq['zhuangtai'] = "待开奖";
////                              $_jq['laiyuanbianhao']=$zs['laiyuanbianhao'];
//                                $_jq['qihao'] = $zs['qihao'];
////                              $_jq['quyu']=$zs['quyu'];
//                                $_jq['haoma'] = $a;
//                                $jq->insert($_jq);
//                            }
//                        } else {
////                            $sl_num_s=$sl->findBySql('select shuliang from sl_jqsl WHERE    diqu="'.$sheng.'"')[0]['shuliang'];//数量
//                            $sl=new ModelNew('jqsl');
//                            $data_s3['diqu'] = $sheng;
//                            switch ($zhuoshu) {
//                                case 2 <= $zhuoshu && $zhuoshu <= 5:
//                                    $data_s2 = 1;
//                                    break;
//                                case 6 <= $zhuoshu && $zhuoshu <= 10:
//                                    $data_s2 = 2;
//                                    break;
//                                case 11<=$zhuoshu && $zhuoshu<=21:
//                                    $data_s2=4;
//                                    break;
//                                case 21<=$zhuoshu && $zhuoshu<=35:
//                                    $data_s2=6;
//                                    break;
//                                case 36<=$zhuoshu && $zhuoshu<=50:
//                                    $data_s2=9;
//                                    break;
//                                case 50<=$zhuoshu :
//                                    $data_s2=50;
//                                    break;
//                            }
//                            $str="";
//                            for ($i = 1; $i <= $data_s2; $i++) {
//                                $sum = $i;
//                                $a = "$sheng-$sum,";
//                                $str .= "$sheng-$sum,";
//                                $jq = $_model->M('jiangquan');
//                                $_jq['yanhuishijian'] = $zs['yanhuishijian'];
//                                $_jq['yonghuming'] = $zs['kehudianhua'];
//                                $_jq['yanhuidanhao'] = $zs['yanhuidanhao'];
//                                $_jq['zhuangtai'] = "待开奖";
////                              $_jq['laiyuanbianhao']=$zs['laiyuanbianhao'];
//                                $_jq['qihao'] = $zs['qihao'];
////                              $_jq['quyu']=$zs['quyu'];
//                                $_jq['haoma'] = $a;
//                                $jq->insert($_jq);
//                            }
//                            $data_s3['shuliang']=$sum;
//
////                            $data_sl['bianhao'] = $yanhuidanhao;
//                            $sl->insert($data_s3);
//                        }
//                        //--------------------------------------
//                    }
                $_SESSION['tel'] = $tel;


//                }
                $data_wdyh['jiangquanhaoma'] = $str;
                $data_wdyh['qihao'] = $qihao;
                $wdyh->insert($data_wdyh);
//                    $STR .= $str;
//                    $NUM += $data_s2;
                $member = new ModelNew();
                $openid = $_SESSION['openid'];
                $member->query("update sl_user set openid_wx='{$openid}' WHERE yonghuming={$tel}");
                echo json_encode(array(
                    'status'=>3,
                    'str'=>$str,
                    'num'=>$data_s2,
                ));
            }}
    }
    }



}