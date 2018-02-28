<?php
//参数控制器
class canshuController extends BaseController{
	
	//显示参数列表
	public function indexAction(){
		//先获取参数信息
		$classid=empty($_REQUEST['classid'])?"":$_REQUEST['classid'];
		$canshuModel = new Model("canshu");
		if(isset($_REQUEST['classid']) and $_REQUEST['classid']!='' and $_REQUEST['classid']!='0')
		{
		    $canshu=$canshuModel->select(" select a.id,a.classid,a.u1,a.u2,a.u3,a.u4,b.u1 as classname from sl_canshu as a,sl_canshu as b where a.classid={$classid} and b.id=a.classid");
		}else 
		{
		    $canshu=$canshuModel->select("select * from sl_canshu  where classid=0");
		}
		
		
		include CUR_VIEW_PATH."Scanshu".DS.  "canshu_list.html";
	}
	
	//显示参数列表===>树状图
	public function indextreeAction(){
	    //先获取参数信息
	    $classid=empty($_REQUEST['classid'])?"0":$_REQUEST['classid'];
	    $canshuModel = new Model("canshu");
	    if(isset($_REQUEST['classid']) and $_REQUEST['classid']!='' and $_REQUEST['classid']!='0')
		{
		    $canshu=$canshuModel->select(" select a.id,a.classid,a.u1,a.u2,a.u3,a.u4,b.u1 as classname,a.classid  as parentid,a.u1 as name  from sl_canshu as a,sl_canshu as b where a.classid={$classid} and b.id=a.classid");
		}else 
		{
		    $canshu=$canshuModel->select("select *,classid  as parentid,u1 as name  from sl_canshu ");
		}
	    //挂载 TREE 类
	    $this->helper('tree');
	    $tree =new Tree() ;
	    $tree->tree($canshu);
	    
	    $str = "<tr class=''>
            	    <td> \$id </td>
            	    <td style='text-align: left;'>
            	        <a href='/index.php?p=admin&c=canshu&a=index&classid=\$id'>\$spacer \$name</a>
            	    </td>
            	    <td>
            	    <a href='/index.php?p=admin&c=canshu&a=index&classid=\$id'> </a>
            	    </td>
            	    <td> \$u4 </td>
            	    <td>
                	    <a href='index.php?p=admin&c=canshu&a=edit&id=\$id' title='编辑' class='operation'><span class='ficon ficon-xiugai'></span></a>
                	    <a href='index.php?p=admin&c=canshu&a=add&classid=\$id' title='添加子类' class='operation'><span class='ficon ficon-zoomout'></span></a>
                	    <a onclick='return confirm('你确认将该频道删除到回收站?\r\n注意：相关栏目和内容也将一起删除到回收站');' id='repChannel_ctl01_btnDelete' href='index.php?p=admin&c=canshu&a=delete&id=\$id' title='删除' class='operation'><span class='ficon  ficon-shanchu'></span></a>
            	    </td>
	            </tr>";
	    $html='';
	    $html .= $tree->get_tree(0,$str,-1);
	
	    include CUR_VIEW_PATH."Scanshu".DS.  "canshu_tree_list.html";
	}

	//载入添加参数页面
	public function addAction(){
	    $canshuModel = new Model("canshu");
	    $u3=$canshuModel->select("SELECT *,u1 as name,classid as parentid from sl_canshu   order by id desc");
	    $classid = trim(isset($_GET['classid']) ? $_GET['classid'] : "0");
	    //挂载 TREE 类
	    $this->helper('tree');
	    $tree =new Tree() ;
	    $tree->tree($u3);
	    $str = "<option value=\$id >\$spacer\$name</option>";
	    $html_option='';
	    $html_option .= $tree->get_tree(0,$str,-1);
	    
	    
	    include CUR_VIEW_PATH ."Scanshu".DS. "canshu_add.html";
	}

	//载入编辑参数页面
	public function editAction(){
		 //获取该模型信息
	    $canshuModel = new Model("canshu");
	    //条件
	    $classid = trim(isset($_GET['classid']) ? $_GET['classid'] : "0");
	    $canshu_id = $_GET['id']; //出于考虑
	    //使用模型获取
	    $canshu = $canshuModel->selectByPk($canshu_id);
	    // var_dump($brand);
	    $u3=$canshuModel->select("SELECT * from sl_canshu   order by id desc");
	    
		include CUR_VIEW_PATH ."Scanshu".DS. "canshu_edit.html";
	}

	//定义insert方法，完成参数的插入
	public function insertAction(){
		 $canshuModel = new  Model("canshu");
	    //1.收集表单数据
	    $data=$canshuModel->getFieldArray();
	    //2.验证和处理
	    
	    
	    $this->helper('input');
	    $data = deepspecialchars($data);
	    $data = deepslashes($data);
	    if ($canshuModel->insert($data)) {
	        //4.创建数据表
	        $this->jump('index.php?p=admin&c=canshu&a=index&classid='.$_REQUEST['classid'],'添加成功',2);
	    } else {
	        $this->jump('index.php?p=admin&c=canshu&a=add&classid='.$_REQUEST['classid'],'添加失败');
	    }
	    
	}

	//定义update方法，完成参数的更新
	public function updateAction(){
	 $canshuModel = new  Model("canshu");
	    //1.收集表单数据
	    $data=$canshuModel->getFieldArray();
	    //2.验证和处理
	    
		//调用模型完成更新
		
		if($canshuModel->update($data)){
			$this->jump("index.php?p=admin&c=canshu&a=edit&id=".$data['id'],"更新成功",2);
		}else{
			$this->jump("index.php?p=admin&c=canshu&a=edit&id=".$data['id'],"更新失败",2);
		}
	}

	//定义delete方法，完成参数的删除
	public function deleteAction(){
	   //获取canshu_id
		$canshu_id = $_GET['id'];
		$canshuModel = new Model("canshu");
		
		if ($canshuModel->delete($canshu_id)){ 
		    echo "<script>alert('删除成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
			    //$this->jump("index.php?p=admin&c=canshu&a=index","删除成功",2);
		}else{
			$this->jump("index.php?p=admin&c=canshu&a=index","删除失败",3);
		}
		
	}
}