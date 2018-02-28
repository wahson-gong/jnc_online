<?php
//自动表控制器
class ActController extends BaseController{
	
	//显示自动表列表
	public function zpqAction(){

        include CUR_VIEW_PATH . "Sact" . DS . "act_zpq.html";
	}


	//大转盘

    public function dzpAction(){
        include CUR_VIEW_PATH . "Sact" . DS . "act_dzp.html";
    }


    //极致之旅
    public function jzzlAction(){
        include CUR_VIEW_PATH . "Sact" . DS . "act_jzzl.html";
    }

    //极致之旅中间名单
    public function hjmdAction(){
        include CUR_VIEW_PATH . "Sact" . DS . "act_hjmd.html";
    }
}