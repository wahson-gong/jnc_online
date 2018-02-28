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
        header('Content-Disposition: attachment;filename="'.'开奖期号'.'.xlsx"');
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
        header('Content-Disposition: attachment;filename="'.'奖品信息'.'.xlsx"');
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
        $id=$_GET['id'];
        $_zpgl_model=new ModelNew('yanhuitupian');
        $_mulu=$_zpgl_model->where(['id'=>$id])->find('tupianlujing')->one()['tupianlujing'];
//        $dir = "public/webuploader/upload/2018.1.1";
        $dir = "public/webuploader/upload/".$_mulu;

        $_data=[];
        $model=new ModelNew('yhmd');
        $_model=new ModelNew('zpgl');
        if (is_dir($dir))
        {
            if ($dh = opendir($dir))
            {
                $i=1;
                $sql='';
                while (($file = readdir($dh)) !== false)
                {
//                    echo $GLOBALS['config_cache']['SITEURL'].'/'.$file ;
                     $data=$GLOBALS['config_cache']['SITEURL'].'/'.$file;
//                   $_data[]+=str_replace('http://jnc.cdsile.cn/','',$data);
                     $v1=str_replace('http://jnc.cdsile.cn/','',$data);
                     $v=substr($v1,0,-4);



                     if ($v!=''){

                     $rs=empty($model->findBySql("select *from sl_yhmd WHERE yanhuidanhao='".$v."'")[0])?'':$model->findBySql("select *from sl_yhmd WHERE yanhuidanhao='".$v."'")[0];
                      }
                    if (empty($rs)?false:true){
//                        $url=$dir."/".$v1;
//                        $_rs=self::shuiyinAction($v['yanhuidanhao'],$url);
//                      $_zp_data['laiyuanbianhao']=$rs['laiyuanbianhao'];
                        $_zp_data['qihao']=$rs['qihao'];
                        $_zp_data['quyu']=$rs['quyu'];
                        $_zp_data['shoujihao']=$rs['kehudianhua'];
                        $_zp_data['sclx']='管理员上传';
                        $_zp_data['yanhuidanhao']=$rs['yanhuidanhao'];
                        $_zp_data['suoluetu']="http://jnc.cdsile.cn/".$dir."/".$v1;
//                        $_zp_data['suoluetu']=$_rs;
//                    $_model->insert($_zp_data);
                    $sql.="('{$_zp_data['qihao']}','{$_zp_data['quyu']}','{$_zp_data['shoujihao']}'
                    ,'{$_zp_data['sclx']}','{$_zp_data['yanhuidanhao']}','{$_zp_data['suoluetu']}'),";
                    if (($i%200)==0){
                        $sql = rtrim($sql, ',');
                         $_model->query("INSERT INTO sl_zpgl (qihao,quyu,shoujihao,sclx,yanhuidanhao,suoluetu)  VALUES ".$sql);
                        $sql='';
                    }
                   }
                   $i++;
                }
                $sql = rtrim($sql, ',');
                $_model->query("INSERT INTO sl_zpgl (qihao,quyu,shoujihao,sclx,yanhuidanhao,suoluetu)  VALUES ".$sql);

                $_tupian_=new ModelNew('yanhuitupian');
                $_tp_data['zhuangtai']="已载入";
                $_tupian_->where(['id'=>$id])->update($_tp_data);
                closedir($dh);
            }
        }
//        foreach ($_data as $v){
//                  $rs=empty($model->findBySql("select *from sl_yhmd WHERE yanhuidanhao=$v")[0])?'':$model->findBySql("select *from sl_yhmd WHERE yanhuidanhao=$v")[0];
//                  if ($rs){
//                        $_zp_data['laiyuanbianhao']=$rs['laiyuanbianhao'];
//                        $_zp_data['qihao']=$rs['qihao'];
//                        $_zp_data['quyu']=$rs['quyu'];
//                        $_zp_data['shoujihao']=$rs['kehudianhua'];
//                        $_zp_data['sclx']='管理员上传';
//                        $_zp_data['yanhuidanhao']=$rs['yanhuidanhao'];
//                        $_zp_data['suoluetu']="http://jnc.cdsile.cn/public/webuploader/upload/2018-1/".$v.".jpg";
//                        $_model->insert($_zp_data);
//                  }
//        }
        $this->jump('index.php?p=admin&c=autotable&a=index&model_id=134','载入成功',3);
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

    public function sfzu1Action(){
        $id=$_GET['id'];
        $model=new ModelNew('yanhuitupian');
        $rs=$model->where(['id'=>$id])->find('zhuangtai')->one()['zhuangtai'];
        if ($rs=='已载入'){
            echo '1';
        }else{
            echo '12';
        }
    }


    public static function shuiyinAction($danhao,$url,$shi){

       // $dst_path = 'public/webuploader/upload/2018-1/10000.jpg';
       // $font = 'public/css/Menlo.ttc';//字体路径
        header("Content-type:text/html;charset=utf-8");
        $dst_path =$url;
//创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
//打上文字
        $font = 'public/css/zw1.TTF';//字体
        $t_color = imagecolorallocatealpha($dst, 0, 0, 255, 80);//最后一个参数值越大越透明
        imagefttext($dst, 40, 0, 100, 150, $t_color, $font, $shi);
        imagefttext($dst, 40, 0, 100, 100, $t_color, $font, $danhao);
//输出图片
        list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        switch ($dst_type) {
            case 1://GIF
                ob_clean();
//                header('Content-Type: image/gif');
//                imagegif($dst);
//                break;
                $file_name='public/webuploader/upload/test/'.$danhao.'.gif';
                imagejpeg($dst,$file_name);
                return $file_name;
                break;
            case 2://JPG
                ob_clean();
//                header('Content-Type: image/jpeg');
                $file_name='public/webuploader/upload/test/'.$danhao.'.jpg';
                imagejpeg($dst,$file_name);
                return $file_name;
                break;
            case 3://PNG
                ob_clean();
//                header('Content-Type: image/png');
                $file_name='public/webuploader/upload/test/'.$danhao.'.png';
                imagepng($dst,$file_name);
                return $file_name;
                break;
            default:
                break;
        }
        imagedestroy($dst);
    }
    //获取直播的开始的时间
    public static function timeAction(){
        $model=new ModelNew('qh');
        $time=empty($model->where(['zhuangtai'=>1])->find('kaishishijian')->one()['kaishishijian'])?'':$model->where(['zhuangtai'=>1])->find('kaishishijian')->one()['kaishishijian'];

        return $time;

    }

    //判断直播是否开始
    public function timeoutAction(){

        $model=new ModelNew('qh');
        $time=empty($model->where(['zhuangtai'=>1])->find('kaishishijian')->one()['kaishishijian'])?'':$model->where(['zhuangtai'=>1])->find('kaishishijian')->one()['kaishishijian'];
        $time_=strtotime($time);



        if ($time==''){
            echo '3';
        }else{
            if ((time()-$time_)>=0){
                echo '1';
            }else{
                echo '2';
            }
        }

    }
    //查找往期视频
    public function czwqspAction(){
         $modle=new ModelNew('kjsp');
         $rs=empty($modle->where(['zhuangtai'=>'点播'])->find()->one())?'':$modle->where(['zhuangtai'=>'点播'])->find()->one();

         if ($rs==''){
             echo '2';
         }else{
             $data['qihao']=$rs['qihao'];
             $data['quyu']=$rs['quyu'];
             echo json_encode($data);
         }
    }

    public function imageAction(){
        header("Content-type:text/html;charset=utf-8");
        $dst_path ="public/webuploader/upload/2018-1/10000.jpg";
//创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
//打上文字
        $font = 'public/css/zw1.TTF';//字体
        $t_color = imagecolorallocatealpha($dst, 0, 0, 255, 100);//最后一个参数值越大越透明
        imagefttext($dst, 50, 0, 100, 150, $t_color, $font, '中国四川');
        imagefttext($dst, 50, 0, 100, 100, $t_color, $font, '20182');
//输出图片
        list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        switch ($dst_type) {
            case 1://GIF
                ob_clean();
                header('Content-Type: image/gif');
                imagegif($dst);
                break;
            case 2://JPG
                ob_clean();
                header('Content-Type: image/jpeg');
                imagejpeg($dst);
                break;
            case 3://PNG
                ob_clean();
                header('Content-Type: image/png');
                imagepng($dst);
                break;
            default:
                break;
        }
        imagedestroy($dst);
    }

    public function uploadAction(){
        $filename ="./public/uploads/img/".time().$_FILES['file']['name'];
        move_uploaded_file($_FILES["file"]["tmp_name"],$filename);//将临时地址移动到指定地址
        $data['url']=$filename;

        echo json_encode(['url'=>$filename]);
    }

    //奖券生成的方法
    public function jqscAction(){
        $xz=$_GET['limit'];
        $id=$_GET['id'];
        $_qh=new ModelNew('qh');
        $qihao=$_qh->findBySql("select qihao from sl_qh WHERE zhuangtai=1")[0]['qihao'];

        $_quyu=new ModelNew('daorujilu');
        $quyu=$_quyu->findBySql("select kaijiangquyu from sl_daorujilu WHERE id=$id")[0]['kaijiangquyu'];
        $time=$_quyu->findBySql("select kaijiangshijian from sl_daorujilu WHERE id=$id")[0]['kaijiangshijian'];

        $zhaungtai=$_quyu->findBysql("select jiangquanzhuangtai from sl_daorujilu WHERE id=$id")[0]['jiangquanzhuangtai'];
        if ($zhaungtai=='已生成'){
            echo '2';die;
        }


        $model=new ModelNew('yhmd');

        $rs=$model->M('yhmd')->findBysql("SELECT * FROM sl_yhmd WHERE zhuangtai=2 AND quyu='".$quyu."' limit $xz,500");

        $_model=new ModelNew('jqsl');
        $__model=new ModelNew('jiangquan');
        $___model=new ModelNew('wdyh');
        $sl     =$_model->M('jqsl');//奖券数量查询

        foreach ($rs as $v){
           @$sl_num=$sl->findBySql('select count(*) from sl_jqsl WHERE   diqu="'.$quyu.'"')[0]['count(*)'];
           $zhuoshu=$v['zhuoshu'];
           $yanhuidanhao='';
           /*
            * 多个宴会单号
            */
           if ($sl_num){
           $sl = new  ModelNew('jqsl');
           $sl_num_s=$sl->findBySql('select shuliang from sl_jqsl WHERE   diqu="'.$quyu.'"')[0]['shuliang'];//数量
           switch ($zhuoshu){
               case 2<=$zhuoshu && $zhuoshu<=5:
                   $data=1;
                   break;
               case 6<=$zhuoshu && $zhuoshu<=10:
                   $data=2;
                   break;
               case 11<=$zhuoshu && $zhuoshu<=20:
                   $data=4;
                   break;
               case 21<=$zhuoshu && $zhuoshu<=35:
                   $data=6;
                   break;
               case 36<=$zhuoshu && $zhuoshu<=50:
                   $data=9;
                   break;
               case 50<$zhuoshu :
                   $data=12;
                   break;
           }
           $data_sl['shuliang']=$data+$sl_num_s;
           $sl->where(['diqu'=>$quyu])->update($data_sl);
           $_haoma='';
           for ($i=1;$i<=$data;$i++){
               $sum=$i+$sl_num_s;
               $a="$quyu"."$sum,";
               $_haoma.=$a;
               $jq=$__model->M('jiangquan');
               $_jq['yanhuishijian']=$v['yanhuishijian'];
               $_jq['yonghuming']=$v['kehudianhua'];
               $_jq['yanhuidanhao']=$v['yanhuidanhao'];
               $_jq['zhuangtai']="待开奖";
               $_jq['qihao']=$qihao;
               $_jq['haoma']=rtrim($a, ',');
               $_jq['quyu']=$quyu;
               $_jq['kaijiangshijian']=$time;
               $jq->insert($_jq);
           }
           $dd=$___model->M('wdyh');
           $_dd=$v;
           $_dd['jiangquanhaoma']=rtrim($_haoma, ',');
           $_dd['jifen']=$data*5;
           $_dd['qihao']=$qihao;
               $_dd['yonghuming']=$v['kehudianhua'];
               $_dd['xingming']=$v['kehuxingming'];
           $dd->insert($_dd);
          }else{
           $sl = new  ModelNew('jqsl');
           switch ($zhuoshu){
               case 2<=$zhuoshu && $zhuoshu<=5:
                   $data=1;
                   break;
               case 6<=$zhuoshu && $zhuoshu<=10:
                   $data=2;
                   break;
               case 11<=$zhuoshu && $zhuoshu<=20:
                   $data=4;
                   break;
               case 21<=$zhuoshu && $zhuoshu<=35:
                   $data=6;
                   break;
               case 36<=$zhuoshu && $zhuoshu<=50:
                   $data=9;
                   break;
               case 50<$zhuoshu :
                   $data=12;
                   break;
           }
           $data_sl['shuliang']=$data;
           $data_sl['diqu']=$quyu;
           $sl->insert($data_sl);
           $_haoma='';
           for ($i=1;$i<=$data;$i++){
               $sum=$i;
               $a="$quyu"."$sum,";
               $_haoma.=$a;
               $jq=$__model->M('jiangquan');
               $_jq['yanhuishijian']=$v['yanhuishijian'];
               $_jq['yonghuming']=$v['kehudianhua'];
               $_jq['yanhuidanhao']=$v['yanhuidanhao'];
               $_jq['zhuangtai']="待开奖";
               $_jq['qihao']=$qihao;
               $_jq['haoma']=rtrim($a, ',');
               $_jq['quyu']=$quyu;
               $_jq['kaijiangshijian']=$time;
               $jq->insert($_jq);
           }
           $dd=$___model->M('wdyh');
           $_dd=$v;
           $_dd['jiangquanhaoma']=rtrim($_haoma, ',');
           $_dd['jifen']=$data*5;
           $_dd['qihao']=$qihao;
           $_dd['yonghuming']=$v['kehudianhua'];
           $_dd['xingming']=$v['kehuxingming'];
           $dd->insert($_dd);
          }
        }
        $xz=$xz+500;
        if ($rs){
            $this->jump('index.php?p=show&c=admin&a=jqsc&id='.$id.'&limit='.$xz,'',0);
        }else{
            $poi=new  ModelNew('daorujilu');
            $poi->M('daorujilu')->query("update sl_daorujilu set jiangquanzhuangtai='已生成' WHERE id=$id");
            echo '1';
        }

    }

    public  static function  xnljAction($name){
        $model=new ModelNew('listjp');
        $url=$model->where(['jiangpin'=>$name])->find('url')->one()['url'];
        return $url;
    }

    public function hahaAction(){
        $name=$_GET['name'];
        $url=AdminController::xnljAction($name);
        $this->jump($url,'',0);
    }

    public function zpppAction(){
        $id=$_GET['id'];
        $xz=$_GET['limit'];
        $_quyu=new ModelNew('daorujilu');
        $qihao=$_quyu->findBySql("select kaijiangqihao from sl_yanhuitupian WHERE id=$id")[0]['kaijiangqihao'];

        $model=new ModelNew('zpgl');
        $_model=new ModelNew('wdyh');
        $rs=$_model->findBySql("select yanhuidanhao from sl_wdyh WHERE qihao='".$qihao."' limit ".$xz.",500");
//        var_dump("select yanhuidanhao from sl_wdyh WHERE qihao='".$qihao."' limit ".$xz.",20");die;
        foreach ($rs as $v){
            $a=$model->findBySql("select suoluetu from sl_zpgl WHERE yanhuidanhao='{$v['yanhuidanhao']}'")[0]['suoluetu'];
            if ($a){
                $v=$_model->query("update sl_wdyh set touxiang='{$a}' WHERE yanhuidanhao='{$v['yanhuidanhao']}'");
            }
        }
        $xz=$xz+500;
        if ($rs){
            $this->jump('index.php?p=show&c=admin&a=zppp&id='.$id.'&limit='.$xz,'',0);
        }else{
            echo '2';
        }

    }


    public function dzdcAction(){
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
        $jp=$model->M('zpjl');
        $data=$jp->all();

//        $data=['1'=>2,'2'=>3,'去'=>3,'我'=>3,'饿'=>3,'人'=>3,'他'=>3,' 有'=>3,'饿1'=>3];
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','收货人电话');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','收货人');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','领取时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','地址');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','详细地址');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','宴会单号');
        foreach($data as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['shouhuodianhua'])
                ->setCellValue('B'.$num, $v['shouhuoren'])
                ->setCellValue('C'.$num, $v['dtime'])
                ->setCellValue('D'.$num, $v['dizhi'])
                ->setCellValue('E'.$num, $v['xiangxidizhi'])
                ->setCellValue('F'.$num, $v['yanhuidanhao']);
        }
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();
        ob_start();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'转盘大奖收货地址'.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


    public function hahAction(){
        var_dump(date("Y m d : H i s",time()));
    }



    
}