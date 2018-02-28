<?php
class BaseController extends Controller {

    //当前模板路径
    public $templates_path;
    //当前模板
    public $templates;
	    //构造方法
	public function __construct(){
 		//写入系统日志 start
 		$my_Model = new ModelNew();
 		$Common = new Common();
 		$systemModel = $my_Model->M("system");
 		$sys_data["u1"]=empty($_SESSION["yonghuming"])?"访客":$_SESSION["yonghuming"];
 		$sys_data["u2"]="页面访问,访问页面:" . $Common->getUrl();
 		$sys_data["u3"]=$Common->getIP();
// 		$sys_data["u4"]="访客记录";
// 		$systemModel->insert($sys_data);
 		//写入系统日志 end
		//选择模版类型
		if($Common->is_mobile())
		{
		    $this->templates="m";
		    
		}else
		{
		    $this->templates="defualt";
		}
		$this->templates_path="/application/views/".PLATFORM."/templates/".$this->templates;
		
	}
	
	/**
	 * Ajax方式返回数据到客户端
	 * @access protected
	 * @param mixed $data 要返回的数据
	 * @param String $type AJAX返回数据格式
	 * @return void
	 */
	public  function ajaxReturn($data,$type='') {
	    // 返回JSON数据格式到客户端 包含状态信息
	    header('Content-Type:application/json; charset=utf-8');
	    //header('Access-Control-Allow-Origin:*');
	    exit(json_encode($data));
	}

	public function openIdAction(){
        if (!isset($_SESSION['openid'])) {
            $appID = "wx54dad1a359d51c95";
            //绝对路径
            $callback_url = "http://jnc.cdsile.cn/?c=wechat&a=callback";
            $callback_url = urlencode($callback_url);
            //用户授权,获取code
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appID}&redirect_uri={$callback_url}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            return $this->jump($url,'',0);
        }
        return $_SESSION['openid'];
    }

    public function getUserAction(){
	    $data['status'] = false;
	    if (isset($_SESSION['tel'])){
	        $data['status'] = true;
        }
        $this->ajaxReturn($data);
    }

    //获取主题颜色
    public function colorAction(){
        $data['msg'] = "ffd092";
        $model = new ModelNew();
        $color = $model->M('qh')->find('zhutiyanse')->where(['zhuangtai'=>1])->one();
        if (empty($color['zhutiyanse'])){
            $data['msg'] = $color['zhutiyanse'];
        }
        $this->ajaxReturn($data);
    }
}