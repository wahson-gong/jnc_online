<?php
//首页控制器
header("Content-type:text/html;charset=utf-8");
class PlateController extends   BaseController
{
      public function __construct()
      {
          $m = $_GET['m'];
          if ($this->is_weixin()){
              if ($m!="act_jzzl"&&$m!="act_dzp"&&$m!="act_zpq"&&$m!="act_zpss"&&$m!="act_zpxq"&&$m!="act_hjmd"&&$m!="act_wqsp"&&$m!="xy_login"&&$m!="index_kfzx"){
                  if (empty($_SESSION['tel']) == true){
                      $this->jump('index.php?p=show&c=login&a=index','请先登录',0);
                  }
                  $model=new ModelNew('user');
                  $tel=$_SESSION['tel'];
                  $rs=$model->findBySql("select *from sl_user WHERE yonghuming=$tel");
                  if (empty($rs)==true){
                      $this->jump('index.php?p=show&c=login&a=index','请先登录',0);
                  }
              }
          }else{
              $this->jump('index.php?p=show&c=login&a=index','请先登录',0);
          }

      }

    public function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) {

            return true;

        }
        return false;

    }
}