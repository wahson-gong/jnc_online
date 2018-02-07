<?php
//首页控制器
header("Content-type:text/html;charset=utf-8");
class IndexController extends   BaseController
{
    //index方法
    //第一步:检测用户是否存在资格


    //图片上传
    public function uploadAction(){
        $filename ="./public/uploads/img/".time().$_FILES['file']['name'];
        move_uploaded_file($_FILES["file"]["tmp_name"],$filename);//将临时地址移动到指定地址
        $model=new ModelNew();
        $data['touxiang']=$filename;
        $wdyh=$model->M('wdyh');
        $wdyh->where(['yonghuming'=>$_SESSION['tel']])->update($data);
        return $data['touxiang'];
    }

    public function textAction(){
//        $modle=new ModelNew();
//        $sl=$modle->M('jqsl');
//        $data_sl['shuliang'] =879798789;
//        $sl->where(['diqu' => '四川省'])->update($data_sl);
//        $_qh=new ModelNew('qihao');
//        $qh=$_qh->findBySql("select *from sl_qihao WHERE zhuangtai=1")[0]['qihao'];
//        echo $qh;
//        $shi="成都市";
//        $_qh=new ModelNew('qihao');
//        $result=$_qh->findBySql("select *from sl_qihao WHERE shi LIKE '%".$shi."%'")[0];
//        if ($result){
//            echo 1;die;
//        }
  var_dump($_SESSION);
    }


    public function wdyhAction(){
        //        $tel=18582479523;
        $tel=$_SESSION['tel'];
             $model=new ModelNew();
             $wdyh=$model->M('wdyh');
             $result=$wdyh->where(['yonghuming'=>$tel])->all();
            if (!empty($result)){
                $data['status'] = true;
                $data['msg'] = $result;
            }
            $this->ajaxReturn($data);
    }

    public function upload1Action(){
        $url=$_GET['url'];
        $id=$_GET['id'];
        $model=new ModelNew();
        $data['touxiang']=$url;
        $wdyh=$model->M('wdyh');
        $wdyh->where(['id'=>$id])->update($data);

        $danhao=$wdyh->findBySql("select yanhuidanhao from sl_wdyh WHERE id=$id")[0]['yanhuidanhao'];
        $_model=new ModelNew('zpgl');
        $_data['suoluetu']=$url;
        $_data['sclx']="用户上传";
        $_model->where(['yanhuidanhao'=>$danhao])->update($_data);
    }


    public function upload2Action(){

    }

    //我的奖品
    public function wdjpAction(){
       $tel=$_SESSION['tel'];
//            $tel=18582479523;
//            $model=new ModelNew();
//            $wdjp=$model->M('prize');
//            $result=$wdjp->where(['yonghuming'=>$tel])->all();
//            if (!empty($result)){
//             $data['status'] = true;
//            $data['msg'] = $result;
//            }
//           $this->ajaxReturn($data);

        $model=new ModelNew();
        $wdjp=$model->M('zjmd');
        $result=$wdjp->findBySql("select lingjiangzhuangtai,yanhuidanhao,yanhuishijian from sl_zjmd where tel ='{$tel}' GROUP  BY  yanhuidanhao ");
        $_data_prize = [];

        foreach ($result  as $k => $v)
        {
            $_data["yanhuidanhao"]=$v["yanhuidanhao"];
            $_data["yanhuishijian"]=$v["yanhuishijian"];
            $_data["lingjiangzhuangtai"]=$v["lingjiangzhuangtai"];
            //$_data["huojiangshijian"]=$v["huojiangshijian"];
            $_data["jiangxiang"]=$wdjp->findBySql("select * from sl_zjmd where tel ='{$tel}' and yanhuidanhao='{$v["yanhuidanhao"]}' ");
            $_data_prize[] = $_data;
        }


        if (!empty($result)){
            $data['status'] = true;
            $data['msg'] = $_data_prize;
        }else
        {
            $data['status'] = false;
            $data['msg'] = "error";
        }
        $this->ajaxReturn($data);

    }
    //终极大奖
    public function zjdjAction(){
//        $tel=18582479523;
        $tel=$_SESSION['tel'];
        $model=new ModelNew();
        $wdjp=$model->M('zjdj');
//        $result=$wdjp->where(['yonghuming'=>$tel])->where(['zhuangtai'=>2])->all();
        $result=$wdjp->where(['yonghuming'=>$tel])->all();
        if (!empty($result)){
            $data['status'] = true;
            $data['msg'] = $result;
        }else{
            $data['status'] = false;
            $data['msg'] = 'false';
        }
        $this->ajaxReturn($data);
    }
    //奖券号码
    public static function jqAction(){
        //        $tel=18582479523;
        $tel=$_SESSION['tel'];
        $model=new  ModelNew();
        $jq=$model->M('jiangquan');
        $result=$jq->where(['yonghuming'=>$tel])->all();
        return $result;
    }
    //判断抽奖的次数还有多少
    public function checkAction(){
        //        $tel=18582479523;
        $tel=$_SESSION['tel'];
        $model=new ModelNew();
        $num=$model->M('wdyh');
        $result=$num->findBySql("select jifen from sl_wdyh WHERE yonghuming=$tel")[0]['jifen'];
        if ($result/5){
            echo $result/5;
        }else{
            echo '0';
        }
    }
    //终极大奖的领取的方法
    public function lqdjAction(){
        $data['yonghuming']=$_GET['name'];  //姓名
        $data['dianhua']=$_GET['tel'];    //电话
        $data['shenfenzheng']=$_GET['idcard'];  //身份证
        $data['jiangpinid']=$_GET['id'];   //奖品的id
        $id=$data['jiangpinid'];
        $model=new ModelNew();
        $result=$model->M('lqdj')->findBySql("select count(*) from sl_lqdj WHERE jiangpinid=$id")[0]['count(*)'];
        if ($result){
            echo '3';exit;
        }
        $rs=$model->M('lqdj')->insert($data);
        if ($rs){
             $data1['zhuangtai']=1;
             $model->M('zjdj')->where(['id'=>$data['jiangpinid']])->update($data1);
             echo '1';
        }else{ echo '2';}

    }
    //获取积分的静态的方法
    public static  function jifenAction(){
        //        $tel=18582479523;
        $tel=$_SESSION['tel'];
        $model=new  ModelNew();
        $wdyh=$model->M('wdyh');
        $rs=$wdyh->where(['yonghuming'=>$tel])->find('jifen')->one()['jifen']/5;
        return $rs;
    }

    //转盘中奖信息的填写
    public function zpjlAction(){
        $data['yanhuidanhao']=$_POST['danhao'];
        $danhao=$data['yanhuidanhao'];
        $data['shouhuoren']=$_POST['name'];
        $data['shouhuodianhua']=$_POST['tel'];
        $data['dizhi']=$_POST['dizhi'];
        $data['xiangxidizhi']=$_POST['xiangxidizhi'];
        $model=new ModelNew();
        $zpjl=$model->M('zpjl');
        $rs=$zpjl->findBySql("select count(*) from sl_zpjl WHERE yanhuidanhao=$danhao")[0]['count(*)'];
        if ($rs){
            echo '3';exit;
        }
        $result=$zpjl->insert($data);
        if ($result){
            $_model=new ModelNew('zjmd');
            $_data['lingjiangzhuangtai']='已领奖';
            $_model->where(['yanhuidanhao'=>$data['yanhuidanhao']])->update($_data);
            echo '2';
        }else{
            echo '1';
        }
    }

    //直播倒计时的功能实现的方法
    public function daojishiAction(){
        $model=new ModelNew();
        $wdjp=$model->M('kjsp');
        $result=$wdjp->findBySql("select qihao from sl_kjsp WHERE zhuangtai='点播' GROUP  BY  qihao ");
        $_data_prize = [];
        foreach ($result  as $k => $v)
        {
            $_data["qihao"]=$v["qihao"];
            $_data["quyu"]=$wdjp->findBySql("select quyu from sl_kjsp where  qihao='{$v["qihao"]}' ");
            $_data_prize[] = $_data;
        }


        if (!empty($result)){
            $data['status'] = true;
            $data['msg'] = $_data_prize;
        }else
        {
            $data['status'] = false;
            $data['msg'] = "error";
        }
        $this->ajaxReturn($data);
    }

    //-------------------------
    //导出导入功能的搁置地方
    public function excelAction(){

        include "public/PHPExcel-1.8/Classes/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("思乐科技")
            ->setLastModifiedBy("思乐科技")
            ->setTitle("数据EXCEL导出")
            ->setSubject("数据EXCEL导出")
            ->setDescription("备份数据")
            ->setKeywords("excel")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $model=new ModelNew();
        $jp=$model->M('zjmd');
        $data=$jp->all();

//        $data=['1'=>2,'2'=>3,'去'=>3,'我'=>3,'饿'=>3,'人'=>3,'他'=>3,' 有'=>3,'饿1'=>3];
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','奖项名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','获奖时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','联系方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','姓名');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','宴会时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','宴会单号');
        foreach($data as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['prize'])
                ->setCellValue('B'.$num, $v['dtime'])
                ->setCellValue('C'.$num, $v['tel'])
                ->setCellValue('D'.$num, $v['username'])
                ->setCellValue('E'.$num, $v['yanhuishijian'])
                ->setCellValue('F'.$num, $v['yanhuidanhao']);
        }
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'中奖人员列表以及相关信息'.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    //宴会名单的导入功能
    public function mingdanAction()
    {
        include "public/PHPExcel-1.8/Classes/PHPExcel.php";
        include "public/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php";
        include 'public/PHPExcel-1.8/Classes/PHPExcel/Reader/Excel5.php';
        $model=new ModelNew();
        $cw=$model->M('error');

        $drjl=$model->M('daorujilu');
        $id=$_GET['id'];
        $shuju=$drjl->findBySql("select *from sl_daorujilu WHERE id=$id")[0];
        $url=$shuju['biaogewenjian'];
        $lybh=$shuju['laiyuanbianhao'];
        $url=str_replace('http://jnc.cdsile.cn','.',$url);
//      $filename="./public/uploads/yhmd.xlsx";
        $filename=$url;
//      $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load($filename); //$filename可以是上传的文件，或者是指定的文件
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        $k = 0;
//        $_qh=new ModelNew('qh');
//        $qh=$_qh->findBySql("select *from sl_qh WHERE zhuangtai=1")[0];

        $md=$model->M('yhmd');
//循环读取excel文件,读取一条,插入一条
        $sql="";
        $_cf_data_dh=[];
        $_cf_data_tel=[];
        for($j=2;$j<=$highestRow;$j++)
        {
            $data['yanhuidanhao'] = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//获取A列的值
            $data['chengshijingli'] = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//获取B列的值
            $data['bobaoyewuyuan'] = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//获取B列的值
            $data['zhongduanbianma'] = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();//获取B列的值
            $data['zhongduanmingcheng'] = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();//获取B列的值
            $data['zhuoshu'] = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();//获取B列的值
            $data['yanhuishijian'] = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();//获取B列的值
            $data['yanhuidizhi'] = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue();//获取B列的值
            $data['yanhuidizhi'] =str_replace('\'',',',$data['yanhuidizhi']);
            $data['kehuxingming'] = $objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue();//获取B列的值
            $data['kehudianhua'] = $objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue();//获取B列的值
            $data['gengdanrenxingming'] = $objPHPExcel->getActiveSheet()->getCell("K".$j)->getValue();//获取B列的值
            $data['sheng'] = $objPHPExcel->getActiveSheet()->getCell("L".$j)->getValue();//获取B列的值
            $data['shi'] = $objPHPExcel->getActiveSheet()->getCell("M".$j)->getValue();//获取B列的值
            $data['diyubianhao'] = $objPHPExcel->getActiveSheet()->getCell("N".$j)->getValue();//获取B列的值
            $data['qihao'] =$shuju['kaijiangqihao'];
            $data['quyu'] =$shuju['kaijiangquyu'];
            $_cf_data_dh[]+=$data['yanhuidanhao'];
            $_cf_data_tel[]+=$data['kehudianhua'];
            $sql.="('{$data['yanhuidanhao']}','{$data['chengshijingli']}'
            ,'{$data['bobaoyewuyuan']}','{$data['zhongduanbianma']}','{$data['zhongduanmingcheng']}','{$data['zhuoshu']}','{$data['yanhuishijian']}',
            '{$data['yanhuidizhi']}','{$data['kehuxingming']}',
            '{$data['kehudianhua']}','{$data['gengdanrenxingming']}',
            '{$data['sheng']}','{$data['shi']}','{$data['qihao']}','{$data['diyubianhao']}','{$data['quyu']}'),";
            if ($j%1000==0){
                $sql = rtrim($sql, ',');
                $rs=$md->query("INSERT INTO sl_yhmd (yanhuidanhao,chengshijingli,bobaoyewuyuan
               ,zhongduanbianma,zhongduanmingcheng,zhuoshu,yanhuishijian,yanhuidizhi,kehuxingming
               ,kehudianhua,gengdanrenxingming,sheng,shi,qihao,diyubianhao,quyu)  VALUES ".$sql);
                $sql='';
            }

            }
        $sql = rtrim($sql, ',');
        $rs=$md->query("INSERT INTO sl_yhmd (yanhuidanhao,chengshijingli,bobaoyewuyuan
       ,zhongduanbianma,zhongduanmingcheng,zhuoshu,yanhuishijian,yanhuidizhi,kehuxingming
       ,kehudianhua,gengdanrenxingming,sheng,shi,qihao,diyubianhao,quyu)  VALUES ".$sql);

        $md->query("delete from sl_yhmd WHERE yanhuidanhao='' or kehudianhua=''");


        $_czrz1=new ModelNew('czrz');
        $_czrz['caozuorenyuan']=$_SESSION['admin']['username'];
        $_czrz['caozuoleixing']="excel表操作";
        $_czrz['caozuoshijian']=date('Y-m-d h:i:s ',time()+3600*8);
        $_czrz1->insert($_czrz);
//        $rs=IndexController::sjtsAction($lybh,$shuju['kaijiangquyu']);
        $rs=IndexController::sjtsAction($lybh,$_cf_data_dh,$_cf_data_tel);
        if ($rs==1) {
            $drjl=$model->M('daorujilu');
            $_jilu['zairuzhuangtai']="已载入";
            $drjl->where(['id'=>$id])->update($_jilu);
            $this->jump("index.php?p=admin&c=autotable&a=index&model_id=130", '成功', 3);
        }else{
            $this->jump("index.php?p=admin&c=autotable&a=index&model_id=130", '系统繁忙,请稍后再试', 3);
        }
    }

        //数据的调试发的方法
    public static function sjtsAction($lybh,$danhao,$dianhua){


        $_cf_danhao = array_unique ($danhao);
        $_cf_danhao = array_diff_assoc ($danhao,$_cf_danhao);
        $_cf_danhao=array_unique($_cf_danhao); //这些都是重复的单号数据


        $_cf_dianhua = array_unique ($dianhua);
        $_cf_dianhua = array_diff_assoc ($dianhua,$_cf_dianhua);
        $_cf_dianhua =array_unique($_cf_dianhua); //这些都是重复的电话数据

//        var_dump($_cf_dianhua);die;
            $model = new  ModelNew('yhmd');
            $model2=new ModelNew('error');
//        foreach ($_cf_danhao as $v1){
//
//             $_data_danhao=$model->findBySql("select * from sl_yhmd WHERE yanhuidanhao='{$v1}'");
//             $model->query("update from sl_yhmd set zhuangtai=1 WHERE yanhuidanhao='{$v1}'");
//             foreach ($_data_danhao as $v){
//                    $v['cuowutishi']='宴会单号相同';
//                    $v['laiyuanbianhao']=$lybh;
//                    $model2->insert($v);
//             }
//
//        }


//        foreach ($_cf_dianhua as $v1){
//
//            $_data_dianhua=$model->findBySql("select * from sl_yhmd WHERE kehudianhua='{$v1}'");
//            $model->query("update from sl_yhmd set zhuangtai=1 WHERE kehudianhua='{$v1}'");
//            foreach ($_data_dianhua as $v){
//                $v['cuowutishi']='客户电话相同';
//                $v['laiyuanbianhao']=$lybh;
//                $model2->insert($v);
//            }
//
//        }

        return 1;
//        $cw=$model2->M('error');
//        $md=$model->M('yhmd');
//        $cf1=$md->findBySql("select * from sl_yhmd where kehudianhua in (select kehudianhua from sl_yhmd WHERE quyu='".$qy."' and zhuangtai=1 group by kehudianhua having count(1) > 1) ");
//        $cf2=$md->findBySql("select * from sl_yhmd where yanhuidanhao in (select yanhuidanhao from sl_yhmd WHERE quyu='".$qy."' and zhuangtai=1 group by yanhuidanhao having count(1) > 1) ");
//        foreach ($cf1 as $v){
//            $v['cuowutishi']='客户电话相同';
//            $v['laiyuanbianhao']=$lybh;
//            $cw->insert($v);
//            $id=$v['id'];
//            $rs=$md->query("update  sl_yhmd set zhuangtai=2 where id=$id");
//        }
//        foreach ($cf2 as $v1){
//            $v1['cuowutishi']='宴会单号相同';
//            $v1['laiyuanbianhao']=$lybh;
//            $cw->insert($v1);
//            $id=$v1['id'];
//            $rs=$md->query("update  sl_yhmd set zhuangtai=2 where id=$id");
//        }

    }

    public function imgAction(){
        $dir = "public/uploads/img";
        if (is_dir($dir))
        {
            if ($dh = opendir($dir))
            {
                while (($file = readdir($dh)) !== false)
                {
                    echo $GLOBALS['config_cache']['SITEURL'].'/'.$file ;
                    echo '<br>';
                }
                closedir($dh);
            }
        }
    }


    public function spqhAction(){
        $qihao=$_GET['qihao'];
        $model=new ModelNew();
        $wdjp=$model->M('kjsp');
        $result=$wdjp->findBySql("select * from sl_kjsp WHERE qihao='".$qihao."'");
        $data['status'] = true;
        $data['msg'] = $result;
        $this->ajaxReturn($data);

    }
    //当前奖期
    public static function dqjqAction(){
        $model=new ModelNew('qh');
        $result=$model->where(['zhuangtai'=>1])->all()[0];
        $qh=$result['qihao'];
        $_model=new ModelNew('kjsp');
        $result=$_model->where(['qihao'=>$qh])->one();
        return $result;
    }



    public function daojishi1Action(){
        $model=new ModelNew();
        $wdjp=$model->M('kjsp');
        $result=$wdjp->findBySql("select qihao from sl_kjsp GROUP  BY  qihao ");
        $_data_prize = [];
        foreach ($result  as $k => $v)
        {
            $_data["qihao"]=$v["qihao"];
            $_data["quyu"]=$wdjp->findBySql("select quyu from sl_kjsp where  qihao='{$v["qihao"]}' ");
            $_data_prize[] = $_data;
        }

            $data['msg'] = $_data_prize;
        var_dump($data['msg'][0]['quyu'][0]['quyu']);die;

    }


    //期刊的导出
    public function excel1Action(){
        include "public/PHPExcel-1.8/Classes/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("思乐科技")
            ->setLastModifiedBy("思乐科技")
            ->setTitle("数据EXCEL导出")
            ->setSubject("数据EXCEL导出")
            ->setDescription("备份数据")
            ->setKeywords("excel")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $model=new ModelNew();
        $jp=$model->M('qh');
        $data=$jp->all();

//        $data=['1'=>2,'2'=>3,'去'=>3,'我'=>3,'饿'=>3,'人'=>3,'他'=>3,' 有'=>3,'饿1'=>3];
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','期号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','开始时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','结束时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','开奖区域');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','活动规则');
        foreach($data as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['qihao'])
                ->setCellValue('B'.$num, $v['kaishishijian'])
                ->setCellValue('C'.$num, $v['jieshushijian'])
                ->setCellValue('D'.$num, $v['kaijiangquyu'])
                ->setCellValue('E'.$num, html_entity_decode($v['huodongguize']));
        }
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'开奖期号'.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    //奖品导出
    public function excel2Action(){
        include "public/PHPExcel-1.8/Classes/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("思乐科技")
            ->setLastModifiedBy("思乐科技")
            ->setTitle("数据EXCEL导出")
            ->setSubject("数据EXCEL导出")
            ->setDescription("备份数据")
            ->setKeywords("excel")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $model=new ModelNew();
        $jp=$model->M('listjp');
        $data=$jp->all();

//        $data=['1'=>2,'2'=>3,'去'=>3,'我'=>3,'饿'=>3,'人'=>3,'他'=>3,' 有'=>3,'饿1'=>3];
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','奖品名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','数量');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','中奖概率');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','开奖次数');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','奖品类型');
        foreach($data as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['jiangpin'])
                ->setCellValue('B'.$num, $v['num'])
                ->setCellValue('C'.$num, $v['percent'])
                ->setCellValue('D'.$num, $v['times'])
                ->setCellValue('E'.$num, $v['type']);
        }
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'奖品信息'.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
 //客服中心的数据的查询
    public function kfzxAction(){
        $model=new ModelNew('kefu');
        $rs=$model->find()->all();
             $data['status'] = true;
             $data['msg'] = $rs;
             $this->ajaxReturn($data);
    }

}