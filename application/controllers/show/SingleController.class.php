<?php
//首页控制器
class SingleController extends   PlateController {
	//index方法
	public function indexAction(){
	    $Common=new Common();
	    $moban=$Common->SafeFilterStr(trim($_REQUEST['m'])).'.html';
	    //加载对应的页面（微信端或者电脑端）
          if (empty($_GET['sp_id'])?false:true){
                $qihao=$_GET['sp_id'];
                $quyu=$_GET['qu'];
                $model=new ModelNew('kjsp');
                $url=$model->findBySql("select *from sl_kjsp WHERE qihao='".$qihao."' and quyu='".$quyu."'")[0];
                $_model=new ModelNew('jiangquan');
                $rs=$_model->findBySql("select *from sl_jiangquan WHERE qihao='{$url['qihao']}' and zhuangtai='中奖'");
            }
	    $pageFilePath=CUR_VIEW_PATH."templates".DS.$this->templates;
	    //d:\wwwroot\jnc\wwwroot\application\views\show\templates\defualt\index_jqhm.html
	    if(is_file($pageFilePath.DS.$moban))
	    {
	        include $pageFilePath.DS.$moban;
	    }else
	    {

	        if($this->templates=="m")
	        {
	            $this->templates= "defualt";
	        }else if($this->templates=="defualt")
	        {
	            $this->templates="m";
	        }else{
                $this->templates="m";
            }

	        $this->templates_path="/application/views/".PLATFORM."/templates/".$this->templates;
	        include CUR_VIEW_PATH."templates".DS.$this->templates.DS.$moban;
	    }
	    
	}
	
	
	 
	
}