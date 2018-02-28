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
        $yhmd=$model->M('yhmd');
        $rs  = $yhmd->findBySql("select count(*) from sl_yhmd WHERE kehudianhua=$tel")[0]['count(*)'];
        if (!$rs){
            echo '9';
        }
    }
    public function indexAction()
    {        //初始化返回参数
        //==================微信openID=======================
        if (!isset($_SESSION['cdsile_openid'])) {
            $appID = "wx54dad1a359d51c95";
            //绝对路径
            $callback_url = "http://jnc.cdsile.cn/?c=wechat&a=callback";
            $callback_url = urlencode($callback_url);
            //用户授权,获取code
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appID}&redirect_uri={$callback_url}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            return $this->jump($url,'',0);
        }
        $openid = $_SESSION['cdsile_openid'];
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
        $openid = $_SESSION['cdsile_openid'];
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
                $openid = $_SESSION['cdsile_openid'];
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
                    $model = new model('user');
                    $data['yonghuming'] = $tel;
                    $data['xingming']=$zs['kehuxingming'];
                    $_SESSION['xingming']=$data['xingming'];
                    $model->insert($data);
                    $_SESSION['tel'] = $tel;

                    $_yhmd=new ModelNew('wdyh');
                    $_wdmd=$_yhmd->findBysql("select * from sl_wdyh WHERE yonghuming='{$tel}'");

                    $str='';
                    $data_s2='';

                    foreach ($_wdmd as  $v) {
//                    $_model_zpgl = new ModelNew('zpgl');
//                    $rs = empty($_model_zpgl->findBySql("select suoluetu from sl_zpgl WHERE yanhuidanhao='{$v['yanhuidanhao']}'")[0]) ? '' : $_model_zpgl->findBySql("select suoluetu from sl_zpgl WHERE yanhuidanhao='{$v['yanhuidanhao']}'")[0];
//                    if ($rs) {
//                        $__model=new ModelNew();
//                        $_data_wdyh['touxiang'] = $rs['suoluetu'];
//                        $__model->query("update sl_wdyh set touxiang='{$rs['suoluetu']}' WHERE yanhuidanhao='{$v['yanhuidanhao']}'");
//                    }
                     $data_s2+=$v['jifen']/5;
                     $str.=$v['jiangquanhaoma'].',';
                    }
                    $str=rtrim($str,',');

                 $member = new ModelNew();
                 $member->query("update sl_user set openid_wx='{$openid}' WHERE yonghuming={$tel}");
                 $openid = $_SESSION['cdsile_openid'];

                echo json_encode(array(
                    'status'=>3,
                    'str'=>$str,
                    'num'=>$data_s2,
                ));
            }}
    }
    }



}