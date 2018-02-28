<?php
// 字段控制器
class FiledController extends BaseController
{
    
    // 显示字段列表
    public function indexAction()
    {
        // 先获取字段信息
        $model_id = $_GET['model_id'];
        $filedModel = new FiledModel("filed");
        $filed = $filedModel->select("select * from sl_filed where model_id='{$model_id}' ");
        include CUR_VIEW_PATH . "Sfiled" . DS . "filed_list.html";
    }
    
    // 载入添加字段页面
    public function addAction()
    { 
        $canshuModel = new Model("canshu");
        $u7 = $canshuModel->select("SELECT * from sl_canshu where classid='4' order by id asc");
        include CUR_VIEW_PATH . "Sfiled" . DS . "filed_add.html";
    }
    
    // 载入编辑字段页面
    public function editAction()
    {
        $canshuModel = new Model("canshu");
        $u7 = $canshuModel->select("SELECT * from sl_canshu where classid='4' order by id asc");
        $model_id = empty($_GET['model_id'])?"":$_GET['model_id'];
        $filed_id = empty($_GET['id'])?"":$_GET['id'];
        $filedModel = new FiledModel("filed");
        $filed = $filedModel->selectByPk($filed_id);
        
        include CUR_VIEW_PATH . "Sfiled" . DS . "filed_edit.html";
    }
    
    // 定义insert方法，完成字段的插入
    public function insertAction()
    {
        $filedModel = new FiledModel("filed");
        $moxing_id = empty($_GET['model_id'])?'':$_GET['model_id'];
        // 1.收集表单数据
        $data = $filedModel->getFieldArray();
        // 2.验证和处理
        if ($data['model_id'] == '') {
            $this->jump('/index.php?p=admin&c=moxing&a=index', 'model_id不能为空', 2);
        }
        
        if ($data['u1'] == '') {
            $this->jump('index.php?p=admin&c=filed&a=add&model_id=$moxing_id', '数据库字段名不能为空', 2);
        }
        if ($data['u2'] == '') {
            $this->jump('index.php?p=admin&c=filed&a=add&model_id=$moxing_id', '字段名称不能为空', 2);
        }
        if ($data['u2'] == '') {
            $this->jump('index.php?p=admin&c=filed&a=add&model_id=$moxing_id', '数据库字段名不能为空', 2);
        }
        
        // 是否必填
        if ($data['u4'] == 'on') {
            $data['u4'] = "是";
        } else {
            $data['u4'] = "否";
        }
        // 是否显示
        if ($data['u5'] == 'on') {
            $data['u5'] = "是";
        } else {
            $data['u5'] = "否";
        }
        // 是否检索
        if ($data['u6'] == 'on') {
            $data['u6'] = "是";
        } else {
            $data['u6'] = "否";
        }
        
        //默认值
        if ($data['u8'] == '') {
            $data['u8'] = " ";
        }
        $this->helper('input');
        $data = deepspecialchars($data);
        $data = deepslashes($data);
        // 加载字段名和其所属表名
        $filed_u1 = $data['u1']; // 字段名 如： biaoti
        $moxingModel = new MoxingModel("moxing");
        $moxing_id = $data['model_id'];
        $table_name = $moxingModel->oneRowCol("u1", "id='{$moxing_id}'")['u1'];
        $table_name1 = str_replace("sl_", "", $table_name);
        $tableModel = new Model($table_name1);
        // 拼接数据库新添加字段的SQL
        $commom = new Common();
        $filedTpye = $commom->getFiledType($data['u7']);
        $bitian = $data['u4'] == "是" ? "not null" : "null";
        if ($filedTpye != "mediumtext") {
            //$defultValue = empty(trim($data['u8']))?'':trim($data['u8']);
        }

        //$sql = "alter table `{$table_name}`  Add column {$filed_u1} {$filedTpye} {$bitian} {$defultValue} COMMENT '{$data['u2']}' ";
        $sql = "alter table `{$table_name}`  Add column {$filed_u1} {$filedTpye} {$bitian} COMMENT '{$data['u2']}' ";
        
        $filedModel->start_T();
        // 3调用模型完成入库并给出提示   // 4.创建数据表的字段
       if ($filedModel->insert($data) ) {
          
           $tableModel->query($sql);
           $filedModel->comit_T();
           $this->jump('index.php?p=admin&c=filed&a=index&model_id=' . $moxing_id, '添加成功', 2);
       } else {
           $filedModel->roll_T();
           echo $sql;die();
           //$this->jump('index.php?p=admin&c=filed&a=add', '添加失败');
       }
       
    }

    // 定义update方法，完成字段的更新
    public function updateAction()
    {
        $filedModel = new FiledModel("filed");

        // 1.收集表单数据
        $data = $filedModel->getFieldArray();
        // 2.验证和处理
        if ($data['model_id'] == '') {
            $this->jump('/index.php?p=admin&c=moxing&a=index', 'model_id不能为空', 2);
        }

        if ($data['u1'] == '') {
            $this->jump('index.php?p=admin&c=filed&a=add&model_id=$moxing_id', '数据库字段名不能为空', 2);
        }
        if ($data['u2'] == '') {
            $this->jump('index.php?p=admin&c=filed&a=add&model_id=$moxing_id', '字段名称不能为空', 2);
        }

        // 是否必填
        if ($data['u4'] == 'on') {
            $data['u4'] = "是";
        } else {
            $data['u4'] = "否";
        }
        // 是否显示
        if ($data['u5'] == 'on') {
            $data['u5'] = "是";
        } else {
            $data['u5'] = "否";
        }
        // 是否检索
        if ($data['u6'] == 'on') {
            $data['u6'] = "是";
        } else {
            $data['u6'] = "否";
        }

        //默认值
        if ($data['u8'] == '') {
            $data['u8'] = " ";
        }


        $moxing_id = $data['model_id'];

        $this->helper('input');
        $data = deepspecialchars($data);
        $data = deepslashes($data);
        // 加载字段名和其所属表名
        $filed_u1 = $data['u1']; // 字段名 如： biaoti
        $moxingModel = new MoxingModel("moxing");
        $moxing_id = $data['model_id'];
        $table_name = $moxingModel->oneRowCol("u1", "id='{$moxing_id}'")['u1'];
        $table_name1 = str_replace("sl_", "", $table_name);
        $tableModel = new Model($table_name1);
        // 拼接数据库新添加字段的SQL
        $commom = new Common();
        $filedTpye = $commom->getFiledType($data['u7']);
        $bitian = $data['u4'] == "是" ? "not null" : "null";
        if ($filedTpye != "mediumtext") {
            $defultValue = trim($data['u8']) == "" ? "" : "default '{$data['u8']}'";
        }
        //如果时间为当前时间
        if($filedTpye=="timestamp" and $data['u8']=="CURRENT_TIMESTAMP")
        {
            
            $defultValue=" ";
        }
        $filed_u1_old = $filedModel->oneRowCol("u1", "id='{$data['id']}'")['u1'];
        
        $sql = "alter table `{$table_name}` change column {$filed_u1_old} {$filed_u1} {$filedTpye} {$bitian} {$defultValue} COMMENT '{$data['u2']}'";
        // 4.创建数据表的字段
        $tableModel->query($sql);
        
        // 3调用模型完成入库并给出提示
        if ($filedModel->update($data)) {
            $this->jump('index.php?p=admin&c=filed&a=index&model_id='.$data['model_id'], '修改成功', 2);
        } else {
            $this->jump('index.php?p=admin&c=filed&a=edit&id='.$data['id'].'&model_id='.$data['model_id'], '修改失败');
        }
    }
    
    // 定义delete方法，完成字段的删除
    public function deleteAction()
    {
        // 获取filed_id
        $filed_id = $_GET['id'];
        $model_id = $_GET['model_id'];
        $filedModel = new FiledModel("filed");
        $filed_u1 = $filedModel->oneRowCol("u1", "id='{$filed_id}'")['u1']; // 字段名 如： biaoti
        
        $moxingModel = new MoxingModel("moxing");
        $table_name = $moxingModel->oneRowCol("u1", "id='{$model_id}'")['u1'];
        $table_name1 = str_replace("sl_", "", $table_name);
        $tableModel = new Model($table_name1);
        
        // 删除sl_filed 表的数据，同时删除对应表的字段
        if ($filedModel->delete($filed_id)) {
            // 同时删除对应表的字段
            $tableModel->query("alter table `{$table_name}` drop column {$filed_u1}  ");
            
            $this->jump("index.php?p=admin&c=filed&a=index&model_id={$model_id}", "删除成功", 2);
        } else {
            $this->jump("index.php?p=admin&c=filed&a=index&model_id={$model_id}", "删除失败", 3);
        }
    }

    //ajax 中文转拼音
    public function Zh2PyAction()
    {
        //挂载中文转拼音类
        include LIB_PATH . "CUtf8_PY.class.php";
        $ch2ypClass =new CUtf8_PY();
        //接收参数
        $temp_str=empty($_GET["Zh_str"])?"":$_GET["Zh_str"] ;
        $model_id=empty($_GET["model_id"]) ? "" : $_GET["model_id"];
        $type=empty($_GET["type"]) ? "" : $_GET["type"];
//         $temp_str=$_GET["Zh_str"];
//         $model_id=$_GET["model_id"];
        if($model_id=="")
        {
            echo '{"status": "false","content": "model_id参数不能为空"}';
        }
        //转换后的参数
        $temp_str=$ch2ypClass->encode($temp_str,"all");
        //检查该model下的字端是否已经存在
        $filedModel = new Model("filed");
        //
        if(count($filedModel->select("select * from sl_filed where model_id={$model_id} and u1='{$temp_str}' "))>0 && $type=="add")
        {
            echo '{"status": "false","content": "该字段已经存在"}';
            
        }else
        {
            echo '{"status": "true","content": "'.$temp_str.'"}';
        }
        
        
    }

}