<?php
//首页控制器
header("Content-type:text/html;charset=utf-8");
class PlateController extends   BaseController
{
      public function __construct()
      {
          $m = $_GET['m'];
          if ($m!="act_jzzl"&&$m!="act_dzp"&&$m!="act_zpq"&&$m!="act_zpss"&&$m!="act_zpxq"&&$m!="act_hjmd"){
              if (empty($_SESSION['tel']) == true){
                  $this->jump('index.php?p=show&c=login&a=index','请先登录',3);
              }
              $model=new ModelNew('user');
              $tel=$_SESSION['tel'];
              $rs=$model->findBySql("select *from sl_user WHERE yonghuming=$tel");
              if (empty($rs)==true){
                  $this->jump('index.php?p=show&c=login&a=index','请先登录',3);
              }
          }

      }

}