<?php
//上传图片控制器
class IncController extends BaseController{
	
	//显示上传图片列表
	public function indexAction(){
		
	}

	//载入添加上传图片页面===>组图
	public function addWebuploaderAction(){
	    include CUR_VIEW_PATH ."inc".DS."webuploader_add.html";
	    
	}
	//载入添加下拉框页面
	public function addSelectAction(){
	    include CUR_VIEW_PATH ."inc".DS."SelectMenu".DS."select.html";
	     
	}
	//载入显示下拉框选中值页面
	public function showSelectAction(){
	    include CUR_VIEW_PATH ."inc".DS."SelectMenu".DS."select_show.html";
	
	}
	
	//显示多条记录表列表
	public function showDuotiaojiluAction(){
	    $guanlianziduan=empty($_REQUEST['guanlianziduan'])?"":$_REQUEST['guanlianziduan']  ;
	    $guanlianziduan_val=empty($_REQUEST['guanlianziduan_val'])?"":$_REQUEST['guanlianziduan_val']  ;
	    $model_id =empty($_REQUEST['model_id'])?"":$_REQUEST['model_id'];
	    //$sort_id = $_REQUEST['sort_id'];
	    $u6 = empty($_REQUEST['u6'])?"":$_REQUEST['u6'];
	    $where = " 1=1 ";
	    // 获得当前表名
	    $moxingModel = new MoxingModel("moxing");
	    $tableName = $moxingModel->oneRowCol("u1", "id={$model_id}")['u1'];
	    //先获取文章信息
	    $tableModel = new Model($tableName);
	    //查询条件
	    if(trim(str_replace("1=1", " ", $tableModel->getSqlWhereStr()))!="")
	    {
	        $where .= " and ".$tableModel->getSqlWhereStr();
	    }
	    //增加多条记录的查询手段
	    if($guanlianziduan_val=="")
	    {
	        $where .=" and  1=2 ";
	    }else
	    {
	        $where .=" and  {$guanlianziduan} in ({$guanlianziduan_val}) ";
	    }
	    
	
	    //得到字段模型
	    $filedModel=new FiledModel("filed");
	    if(trim($u6)!='')
	    {
	        $filedListU6=$filedModel->select("select * from sl_filed where model_id='{$model_id}' and u6='是' ");//模糊查询字段
	        if(count($filedListU6)>0)
	        {
	            $where=$where." and ";
	            foreach ($filedListU6 as $v)
	            {
	                $where=$where."  {$v['u1']} like '%{$u6}%' or ";
	            }
	            $where=$where." 1=2 ";
	        }
	         
	    }
	    //需要显示的字段
	    $filedLists=$filedModel->select("select * from sl_filed where model_id='{$model_id}' and u5='是' order by u10 asc ");//显示查询字段
	     
	
	    // 载入分页类
	    include LIB_PATH . "Page.class.php";
	    // 获取autotable总的记录数
	    $total = $tableModel->total($where);
	    // 指定分页数，每一页显示的记录数
	    $pagesize = 10;
	    // $pagesize = $GLOBALS['config']['pagesize'];
	    // 获取当前页数，默认是1
	    $current = isset($_GET['page']) ? $_GET['page'] : 1;
	    $offset = ($current - 1) * $pagesize;
	    // 使用模型完成数据的查询
	    $tableModel = $tableModel->pageRows($offset, $pagesize, $where);
	    // 使用分页类获取分页信息
	    $page = new Page($total, $pagesize, $current, "index.php", array(
	        "p" => "admin",
	        "c" => "autotable",
	        "a" => "index",
	        "model_id" => "{$model_id}",
	        //"sort_id" => "{$sort_id}"
	    ));
	    $pageinfo = $page->showPage();
	     
	    include CUR_VIEW_PATH  ."inc".DS."Sautotable".DS. "autotable_list.html";
	}

	
	//载入添加多条记录表页面
	public function addDuotiaojiluAction(){
	     
	    $model_id = $_REQUEST['model_id'];
	    //得到字段模型
	    $filedModel=new Model("filed");
	    $filedLists=$filedModel->select("select * from sl_filed where model_id='{$model_id}'  order by  u10 asc,id desc ");//显示查询字段
	
	     
	    include CUR_VIEW_PATH ."inc".DS."Sautotable".DS."autotable_add.html";
	}
	
	//载入添加多条记录表页面
	public function editDuotiaojiluAction(){
	    $model_id = $_REQUEST['model_id'];
	    // 获取autotable_id
	    $autotable_id = $_GET['id'] ;
	    //得到字段模型
	    $filedModel=new Model("filed");
	    $filedLists=$filedModel->select("select * from sl_filed where model_id='{$model_id}'  order by  u10 asc,id desc ");//显示查询字段
	     
	    // 获得当前表名
	    $moxingModel = new MoxingModel("moxing");
	    $tableName = $moxingModel->oneRowCol("u1", "id={$model_id}")['u1'];
	    //先获取文章信息
	    $tableModel = new Model($tableName);
	     
	    $autotable = $tableModel->selectByPk($autotable_id);
	    //var_dump($autotable);die();
	    include CUR_VIEW_PATH ."inc".DS."Sautotable".DS."autotable_edit.html";
	     
	}
	
	//插入添加多条记录表 
	public function insertDuotiaojiluAction(){
	    $model_id = $_REQUEST['model_id'];
	    // 获得当前表名
	    $moxingModel = new MoxingModel("moxing");
	    $tableName = $moxingModel->oneRowCol("u1", "id={$model_id}")['u1'];
	    //先获取文章信息
	    $tableModel = new Model($tableName);
	     
	    //1.收集表单数据
	    $data=$tableModel->getFieldArray();
	     
	    //2.验证和处理
	     
	    $this->helper('input');
	    $data = deepspecialchars($data);
	    //$data = deepslashes($data);
	     
	     
	    //如果字段设置为当前时间
	    $filedModel=new Model("filed");
	    $filedAraay=$filedModel->select("select u1 from sl_filed where model_id='{$model_id}' ");
	    foreach ($filedAraay as $v)
	    {
	        $filedList=$filedModel->select("select * from sl_filed where  u7='时间框' and u8='CURRENT_TIMESTAMP' and model_id='{$model_id}' and u1='{$v['u1']}' ");
	        if(count($filedList)>0)
	        {
	            $data[$v['u1']]= date('Y-m-d H:i:s',time());
	        }
	    }
	    //如果字段设置为文件和图片
	    foreach ($filedAraay as $v)
	    {
	        $filedList=$filedModel->select("select * from sl_filed where  u7='图片'  and model_id='{$model_id}' and u1='{$v['u1']}' ");
	        //判断是否为图片，请图片参数不为空
	        if(count($filedList)>0 )
	        {
	            //echo "select * from sl_filed where  u7='图片'  and model_id='{$model_id}' and u1='{$v['u1']}'  <br>";
	            //$data[$v['u1']]= date('Y-m-d H:i:s',time());
	            //处理文件上传,需要使用到Upload.class.php
	            $this->library("Upload"); //载入文件上传类
	            $upload = new Upload(); //实例化上传对象
	            if ($filename = $upload->up($_FILES[$v['u1']])){
	                //成功
	                $data[$v['u1']] = $filename;
	                //调用模型完成入库操作，并给出相应的提示
	    
	            }else {
	                //print_r($_FILES[$v['u1']]);
	                //$this->jump('index.php?p=admin&c=autotable&a=add&model_id='.$model_id."&sort_id=".$sort_id.$data['sort_id'],$upload->error(),3);
	            }
	             
	        }
	    }
	     
	     
	    //单独处理密码
	    foreach ($filedAraay as $v)
	    {
	        $filedList=$filedModel->select("select * from sl_filed where  u7='密码' and model_id='{$model_id}' and u1='{$v['u1']}' ");
	        if(count($filedList)>0)
	        {
	            $data[$v['u1']]=md5($data[$v['u1']]);
	        }
	    }
	     
	    //单独处理文件
	    foreach ($filedAraay as $v)
	    {
	        $filedList=$filedModel->select("select * from sl_filed where  u7='文件' and model_id='{$model_id}' and u1='{$v['u1']}' ");
	        if(count($filedList)>0)
	        {
	            //上传文件到服务器
	            $this->library("Upload"); //载入文件上传类
	            $upload = new Upload(); //实例化上传对象
	            if ($filename = $upload->up($_FILES[$v['u1']])){
	                $data[$v['u1']]=$filename;
	            }
	             
	        }
	    }
	     
	    
	    //3调用模型完成入库并给出提示
	    if ($tableModel->insert($data)) {
	        echo '<script>window.top.location.reload() </script>';
	       // $this->jump('index.php?p=admin&c=inc&a=index&model_id='.$model_id,'添加成功',2);
	    } else {
	        echo '<script>alert("添加失败");window.top.location.reload() </script>';
	       // $this->jump('index.php?p=admin&c=autotable&a=add&model_id='.$model_id,'添加失败');
	    }
	
	}
	
	//定义update方法，完成自动表的更新
	public function updateDuotiaojiluAction(){
	    $model_id = $_REQUEST['model_id'];
	     
	    // 获得当前表名
	    $moxingModel = new MoxingModel("moxing");
	    $tableName = $moxingModel->oneRowCol("u1", "id={$model_id}")['u1'];
	    //先获取文章信息
	    $tableModel = new Model($tableName);
	     
	    //1.收集表单数据
	    $data=$tableModel->getFieldArray();
	     
	    //2.验证和处理
	     
	    $this->helper('input');
	    $data = deepspecialchars($data);
	    //如果字段设置为文件和图片
	    $filedModel=new Model("filed");
	    $filedAraay=$filedModel->select("select u1 from sl_filed where model_id='{$model_id}' ");
	    foreach ($filedAraay as $v)
	    {
	        $filedList=$filedModel->select("select * from sl_filed where  u7='图片'  and model_id='{$model_id}' and u1='{$v['u1']}' ");
	        //判断是否为图片，请图片参数不为空
	        if(count($filedList)>0 )
	        {
	            //echo "select * from sl_filed where  u7='图片'  and model_id='{$model_id}' and u1='{$v['u1']}'  <br>";
	            //$data[$v['u1']]= date('Y-m-d H:i:s',time());
	            //处理文件上传,需要使用到Upload.class.php
	            $this->library("Upload"); //载入文件上传类
	            $upload = new Upload(); //实例化上传对象
	            if ($filename = $upload->up($_FILES[$v['u1']])){
	                //成功
	                $data[$v['u1']] = $filename;
	                //调用模型完成入库操作，并给出相应的提示
	
	            }else {
	                //print_r($_FILES[$v['u1']]);
	                //$this->jump('index.php?p=admin&c=autotable&a=add&model_id='.$model_id."&sort_id=".$sort_id.$data['sort_id'],$upload->error(),3);
	            }
	             
	        }
	    }
	     
	     
	    //单独处理文件
	    foreach ($filedAraay as $v)
	    {
	        $filedList=$filedModel->select("select * from sl_filed where  u7='文件'  and model_id='{$model_id}' and u1='{$v['u1']}' ");
	        //判断是否为文件，请文件参数不为空
	        if(count($filedList)>0 )
	        {
	            //处理文件上传,需要使用到Upload.class.php
	            $this->library("Upload"); //载入文件上传类
	            $upload = new Upload(); //实例化上传对象
	            if ($filename = $upload->up($_FILES[$v['u1']])){
	                $data[$v['u1']] = $filename;
	            }else {
	            }
	             
	        }
	    }
	    //调用模型完成更新
	    //var_dump($data);die();
	    if($tableModel->update($data)){
	        echo '<script>window.top.location.reload() </script>';
	        //$this->jump('index.php?p=admin&c=autotable&a=index&model_id='.$model_id,"更新成功",2);
	    }else{
	        echo '<script>alert("更新失败");window.top.location.reload() </script>';
	       // $this->jump('index.php?p=admin&c=autotable&a=edit&id='.$data['id'].'&model_id='.$model_id,"更新失败",2);
	    }
	}
	
	//定义delete方法，完成自动表的删除
	public function deleteDuotiaojiluAction(){
	    $model_id = $_REQUEST['model_id'];
	    // 获取autotable_id
	    if($_REQUEST['id']=='')
	    {
	        $this->jump('index.php?p=admin&c=autotable&a=index&model_id='.$model_id,"删除失败，参数不能为空",3);
	    }
	    $sys_id = $_REQUEST['id'];
	    $array_id=explode(",", $sys_id);
	    $array_id=array_unique($array_id);
	     
	    // 获得当前表名
	    $moxingModel = new MoxingModel("moxing");
	    $tableName = $moxingModel->oneRowCol("u1", "id={$model_id}")['u1'];
	    //先获取文章信息
	    $tableModel = new Model($tableName);
	     
	    if ($tableModel->delete($array_id)!="false") {
	        echo '<script>window.top.location.reload() </script>';
	        //$this->jump('index.php?p=admin&c=autotable&a=index&model_id='.$model_id, "删除成功", 2);
	    } else {
	        echo '<script>alert("删除失败");window.top.location.reload() </script>';
	        //$this->jump('index.php?p=admin&c=autotable&a=index&model_id='.$model_id, "删除失败", 3);
	    }
	}
	
	//显示省市县三级联动
	public function showdiquAction(){
	
	    include CUR_VIEW_PATH  ."inc".DS."diqu".DS. "select.html";
	}
	
	
	//载入显示单张图片上传页面
	public function showWebUploaderAction(){
	    include CUR_VIEW_PATH ."inc".DS."webuploader".DS."image-upload".DS."index.html";
	}
	
	//载入选择省市页面
	public function SelectCityAction(){
	    include CUR_VIEW_PATH ."inc".DS."SelectCity".DS."index.html";
	}
	
	//载入批量上传页面
	public function BatchUploadAction(){
	    include CUR_VIEW_PATH ."inc".DS."webuploader".DS."image-upload".DS."index_batch.html";
	}
	
	//载入编辑上传图片页面
	public function editAction(){
		
	}

	//定义insert方法，完成上传图片的插入
	public function insertAction(){
	
		
	}

	//定义update方法，完成上传图片的更新
	public function updateAction(){
		
	}

	//定义delete方法，完成上传图片的删除
	public function deleteAction(){
		
	}
	
	//上传图片
	public function fileuploadAction(){
	    /**
	     * upload.php
	     *
	     * Copyright 2013, Moxiecode Systems AB
	     * Released under GPL License.
	     *
	     * License: http://www.plupload.com/license
	     * Contributing: http://www.plupload.com/contributing
	     */
	    
	    $my_filepath=empty($_GET["path"])?"":"/".$_GET["path"];//ghy 默认保存路径 
	    #!! IMPORTANT:
	    #!! this file is just an example, it doesn't incorporate any security checks and
	    #!! is not recommended to be used in production environment as it is. Be sure to
	    #!! revise it and customize to your needs.
	    
	    
	    // Make sure file is not cached (as it happens for example on iOS devices)
	    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	    header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    
	    
	    // Support CORS
	    // header("Access-Control-Allow-Origin: *");
	    // other CORS headers if any...
	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	        exit; // finish preflight CORS requests here
	    }
	    
	    
	    if ( !empty($_REQUEST[ 'debug' ]) ) {
	        $random = rand(0, intval($_REQUEST[ 'debug' ]) );
	        if ( $random === 0 ) {
	            header("HTTP/1.0 500 Internal Server Error");
	            exit;
	        }
	    }
	    
	    // header("HTTP/1.0 500 Internal Server Error");
	    // exit;
	    
	    
	    // 5 minutes execution time
	    @set_time_limit(5 * 60);
	    
	    // Uncomment this one to fake upload time
	    usleep(5000);
	    
	    // Settings
	    // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
	    $targetDir = $GLOBALS['config_cache']['UPLOAD_DIR'].'upload_tmp'.$my_filepath;
	    $uploadDir = $GLOBALS['config_cache']['UPLOAD_DIR'].'upload'.$my_filepath;
	    //$GLOBALS['config_cache']['UPLOAD_DIR']


	    $cleanupTargetDir = false; // Remove old files
	    $maxFileAge = 5 * 3600; // Temp file age in seconds

//        $targetDir= iconv('utf-8', 'gbk', $targetDir);
//        $uploadDir= iconv('utf-8', 'gbk', $uploadDir);
	    // Create target dir
	    if (!file_exists($targetDir)) {
	        @mkdir($targetDir,0777,true);
	    }
	    
	    // Create target dir
	    if (!file_exists($uploadDir)) {
	        @mkdir($uploadDir,0777,true);
	    }
	    
	    // Get a file name
	    if (isset($_REQUEST["name"])) {
	        $fileName = $_REQUEST["name"];
	    } elseif (!empty($_FILES)) {
	        $fileName = $_FILES["file"]["name"];
	    } else {
	        $fileName = uniqid("file_");
	    }
	    $commom = new Common();
	    
	    if($my_filepath=="")
	    {
	        $fileName="file_".$commom->getOrderId().$fileName;
	    }else 
	    {
	        //$fileName=$fileName;
	    }
	    
	    
	    $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	    $md5File = $md5File ? $md5File : array();
	    
	    if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
	        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
	    }
	    
	    $filePath = $targetDir . "/" . $fileName;
	    $uploadPath = $uploadDir . "/". $fileName;
	    
	    // Chunking might be enabled
	    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
	    
	    
	    // Remove old temp files
	    if ($cleanupTargetDir) {
	        if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
	            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	        }
	        
	        while (($file = readdir($dir)) !== false) {
	            $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
	            
	            // If temp file is current file proceed to the next
	            if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
	                continue;
	            }
	            
	            // Remove temp file if it is older than the max age and is not the current file
	            if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
	                @unlink($tmpfilePath);
	            }
	        }
	        closedir($dir);
	    }
	    
	    
	    // Open temp file
	    if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
	        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	    }
	    
	    if (!empty($_FILES)) {
	        if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
	            die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	        }
	        
	        // Read binary input stream and append it to temp file
	        if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
	            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	        }
	    } else {
	        if (!$in = @fopen("php://input", "rb")) {
	            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	        }
	    }
	    
	    while ($buff = fread($in, 4096)) {
	        fwrite($out, $buff);
	    }
	    
	    @fclose($out);
	    @fclose($in);
	    
	    rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
	    
	    $index = 0;
	    $done = true;
	    for( $index = 0; $index < $chunks; $index++ ) {
	        if ( !file_exists("{$filePath}_{$index}.part") ) {
	            $done = false;
	            break;
	        }
	    }
	    if ( $done ) {
	        if (!$out = @fopen($uploadPath, "wb")) {
	            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	        }
	        
	        if ( flock($out, LOCK_EX) ) {
	            for( $index = 0; $index < $chunks; $index++ ) {
	                if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
	                    break;
	                }
	                
	                while ($buff = fread($in, 4096)) {
	                    fwrite($out, $buff);
	                }
	                
	                @fclose($in);
	                @unlink("{$filePath}_{$index}.part");
	            }
	            
	            flock($out, LOCK_UN);
	        }
	        @fclose($out);
	    }
	    
	    // Return Success JSON-RPC response
	    die($GLOBALS['config_cache']['SITEURL']."/".$uploadPath);  
	}
	
}