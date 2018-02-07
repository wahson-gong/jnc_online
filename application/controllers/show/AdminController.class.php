<?php
// 品牌控制器
class AdminController extends BaseController
{
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


    public function imgAction(){
        $dir = "public/webuploader/upload/2018-1";
        $_data=[];
        $model=new ModelNew('yhmd');
        $_model=new ModelNew('zpgl');
        if (is_dir($dir))
        {
            if ($dh = opendir($dir))
            {
                while (($file = readdir($dh)) !== false)
                {
//                    echo $GLOBALS['config_cache']['SITEURL'].'/'.$file ;
                   $data=$GLOBALS['config_cache']['SITEURL'].'/'.$file;
                   $_data[]+=str_replace('http://jnc.cdsile.cn/','',$data);
//                    echo '<br>';
                }
                closedir($dh);
            }
        }
        foreach ($_data as $v){
                  $rs=empty($model->findBySql("select *from sl_yhmd WHERE yanhuidanhao=$v")[0])?'':$model->findBySql("select *from sl_yhmd WHERE yanhuidanhao=$v")[0];
                  if ($rs){
                        $_zp_data['laiyuanbianhao']=$rs['laiyuanbianhao'];
                        $_zp_data['qihao']=$rs['qihao'];
                        $_zp_data['quyu']=$rs['quyu'];
                        $_zp_data['shoujihao']=$rs['kehudianhua'];
                        $_zp_data['sclx']='管理员上传';
                        $_zp_data['yanhuidanhao']=$rs['yanhuidanhao'];
                        $_zp_data['suoluetu']="http://jnc.cdsile.cn/public/webuploader/upload/2018-1/".$v.".jpg";
                        $_model->insert($_zp_data);
                  }
        }
        die;
     }

     //测试方法
    public function textAction(){
            $qihao="2018年第一期";
            $_quyu=new ModelNew('daorujilu');
            $quyu=$_quyu->findBySql("select kaijiangquyu from sl_daorujilu WHERE kaijiangqihao='".$qihao."'")[0]['kaijiangquyu'];
            echo $quyu;
    }

    //所有获得活动详情的集中静态方法

    public static  function actAction(){
        $model=new ModelNew('qh');
        $result=$model->where(['zhuangtai'=>1])->find('huodongguize')->one()['huodongguize'];
        return html_entity_decode($result);
    }

    //判断是否载入的方法(共有)
    public function sfzuAction(){
        $id=$_GET['id'];
        $model=new ModelNew('daorujilu');
        $rs=$model->where(['id'=>$id])->find('zairuzhuangtai')->one()['zairuzhuangtai'];
        if ($rs=='已载入'){
            echo '1';
        }else{
            echo '12';
        }
    }

    
}