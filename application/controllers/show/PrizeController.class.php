<?php

/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018/1/27
 * Time: 14:08
 */
class PrizeController extends BaseController
{
    //中奖名单展示
    public function listAction(){
        $data['status'] = false;
        $data['msg'] = "暂无中奖记录";
        $model = new ModelNew();
        $lists = $model->M('zjmd')->orderBy('dtime DESC')->limit(0,20)->all();
        if (!empty($lists)){
            $data['status'] = true;
            $data['msg'] = $lists;
        }
        $this->ajaxReturn($data);
    }
    //获取抽奖次数
    public function isLoginAction(){
        $data['status'] = false;
        $data['mark'] = false;
        $data['msg'] = "加载失败";
        if(isset($_SESSION['tel'])){
            $tel = $_SESSION['tel'];
            //获取用户抽奖次数
            $model = new ModelNew();
            $list = $model->M('wdyh')->find('jifen')->where(['yonghuming'=>$tel])->one();
            $times = floor($list['jifen']/5);
            $data['msg'] = $times;
            $data['status'] = true;
            //判断抽奖券是否已经过期
            $code = new ModelNew();
            $row = $code->M('jpzb')->find('laiyuanbianhao,end')->where(['sfqy'=>'是'])->one();
            if (isset($row['end'])){
                $end = strtotime($row['end']);
                $now = time();
                if ($now>$end){
                    $data['info'] = "你有{$times}张券过期，系统将自动删除";
                    //清除过期积分
                    $model->M('wdyh')->query("update sl_wdyh set jifen=0 WHERE yonghuming={$tel}");
                    $data['msg'] = 0;
                    $data['mark'] = true;
                }
            }
        }

        $this->ajaxReturn($data);
    }

    //banner
    public function bannerAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $model = new ModelNew();
        $lists = $model->M('banner')->limit(0,3)->where(['status'=>'大转盘'])->orderBy('paixu ASC')->all();
        $row = $model->findBySql("select hdgz from sl_jpzb WHERE sfqy='是'");
        if ($lists){
            $data['status'] = true;
            $data['msg'] = $lists;
            $data['rule'] = isset($row[0]['hdgz'])?html_entity_decode($row[0]['hdgz']):'';
        }
        $this->ajaxReturn($data);
    }

    //奖品
    public function prizeAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $model = new ModelNew();
        $row = $model->findBySql("select laiyuanbianhao from sl_jpzb WHERE sfqy='是' limit 0,1");
        if (isset($row[0]['laiyuanbianhao'])){
            $lists = $model->findBySql("select *from sl_listjp WHERE laiyuanbianhao={$row[0]['laiyuanbianhao']} ORDER BY paixu DESC ");
        }else{
            $lists = $model->M('listjp')->orderBy('dtime ASC')->limit(0,8)->all();
        }
        if ($lists){
            $data['status'] = true;
            $data['msg'] = $lists;
        }
        $this->ajaxReturn($data);
    }

    //大转盘抽奖
    public function rollAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        if (!isset($_SESSION['tel'])){
            $data['msg'] = "请先登录账号";
            $this->ajaxReturn($data);
        }
        $tel = $_SESSION['tel'];
        $model = new ModelNew();
        $lists = $model->M('jpzb')->find('laiyuanbianhao,start,end')->where(['sfqy'=>'是'])->all();
        $now = time();
        $flag = '';
        //确认当前是否有活动
        if ($lists){
            foreach ($lists as $v){
                if ($now>=strtotime($v['start'])&&$now<=strtotime($v['end'])){
                    $flag = $v['laiyuanbianhao'];
                }
            }
        }

        if (empty($flag)){
            //获取用户抽奖次数
            $data['msg'] = "暂无活动，敬请期待";
            $this->ajaxReturn($data);
        }
        $prizeModel = new ModelNew();
        $rows = $prizeModel->findBySql("select paixu,jiangpin,id,percent,num,times from sl_listjp WHERE laiyuanbianhao = {$flag}");
        $arr = [];
        foreach ($rows as $k=>$val){
            $arr[$val['id']]= $val['percent']*100;
        }
        $total = array_sum($arr);
        if ($total==0){
            $data['msg'] = "活动结束";
            $this->ajaxReturn($data);
        }
        //查询用户抽奖次数
        $user = new ModelNew('wdyh');

        $username = $_SESSION['xingming'];
        $score = $user->M('wdyh')->where(['yonghuming'=>$tel])->find('jifen,yanhuidanhao,yanhuishijian')->one();
        if($score['jifen']<5){
            $data['msg'] = "您的抽奖机会已用完";
            $this->ajaxReturn($data);
        }
        //抽奖
        foreach ($arr as $key => $proCur) {
            $randNum = mt_rand(1, $total);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                //未中奖减少基数，保证公平性
                $total -= $proCur;
            }
        }
        $list = [];
        //获取转盘旋转角度
        foreach ($rows as $item){
            if ($item['id']==$result){
                $no = $item['paixu'];
                $prize = $item['jiangpin'];
                $num = $item['num'];
                $times = $item['times'];
            }
        }
        if ($num<=1 || $times<=1){
            $list['percent'] = 0;
        }

        //开启事务
        $model->start_T();
        try{

            if ($num <= 0 || $times<=0){
                throw new Exception("没有中奖，再接再厉");
            }

            //扣减奖品数量和开奖次数
            if ($num>=1 && $times>=1){
                $list['num'] = $num-1;
                $list['times'] = $times-1;
            }
            $update = new ModelNew();
            $re = $update->M('listjp')->where(['id'=>$result])->update($list);

            if (!$re){
                throw new Exception("没有中奖，再接再厉");
            }

            //生成中奖记录，未中奖不生成记录
            if ($prize!="谢谢参与"){
                $insert = new ModelNew();
                $res = $insert->M('zjmd')->insert(['tel'=>$tel,'prize'=>$prize,'username'=>$username,'lingjiangzhuangtai'=>"未领奖",'yanhuidanhao'=>$score['yanhuidanhao'],'yanhuishijian'=>$score['yanhuishijian']]);
                if (!$res){
                    throw new Exception("没有中奖，再接再厉");
                }
            }




            //扣除积分
            $ret = $user->query("update sl_wdyh set jifen = jifen - 5 where yonghuming = {$tel}");

            if (!$ret){
                throw new Exception("没有中奖，再接再厉");
            }

            if ($result){
                $data['status'] = true;
                $data['no'] = $no;
                $data['msg'] = $prize;
                $data['times'] = floor($score['jifen']/5)-1;
                $data['prize'] = [
                    'tel'=>$tel,
                    'prize'=>$prize,
                    'dtime'=>date("m-d H:i",time())
                ];
            }
            $model->comit_T();
            unset ($arr);
            $this->ajaxReturn($data);
        }catch (Exception $e){
            $model->roll_T();
            $data['status'] = true;
            $data['msg'] = "谢谢参与";
            $this->ajaxReturn($data);
        }
    }

    //终极大奖名单
    public function ultimateAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        //获取最新一期
        $cate = new ModelNew();
        $row = $cate->findBySql("select huojiangqishu from sl_zjdj GROUP BY huojiangqishu ORDER BY dtime DESC ");
        $date = !empty($_GET['date'])?trim($_GET['date']):$row[0]['huojiangqishu'];
        $area = !empty($_GET['area'])?trim($_GET['area']):'';
        $model = new ModelNew();
        $lists = $model->findBySql("select *from sl_zjdj where huojiangqishu LIKE '%{$date}%' and zhongjianghaoma LIKE '{$area}%' ORDER BY dtime DESC");
        //获取人名和人名处理
        foreach ($lists as $k=>$v){
            $user = new ModelNew();
            $row = $user->M('user')->find('xingming,xingbie')->where(['yonghuming'=>$v['yonghuming']])->one();
            if (!$row){
                $lists[$k]['xingming'] = "***";
            }else {
                if (!isset($row['xingbie'])) {
                    $lists[$k]['xingming'] = mb_substr($row['xingming'], 0, 1) . "**";
                } else {
                    if ($row['xingbie'] == '女') {
                        $lists[$k]['xingming'] = mb_substr($row['xingming'], 0, 1) . "女士";
                    } else {
                        $lists[$k]['xingming'] = mb_substr($row['xingming'], 0, 1) . "先生";
                    }
                }
            }
        }
        if ($lists){
            $data['status'] = true;
            $data['msg'] = $lists;
        }
        $this->ajaxReturn($data);
    }

    //照片墙
    public function galleryAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $page = isset($_GET['page'])?$_GET['page']:1;
        $start = ($page-1)*4;
        if (trim($_GET['date'])!="期数"){
            $date = trim($_GET['date']);
        }else{
            $model = new ModelNew();
            $row = $model->M('wdyh')->find('qihao')->groupBy('qihao')->orderBy('yanhuishijian DESC')->one();
            $date = $row['qihao'];
        }
        $area = trim($_GET['area'])!="区域"?trim($_GET['area']):'';
        $gallery = new ModelNew();
        $lists = $gallery->M('wdyh')->findBySql("select *from sl_wdyh where qihao LIKE '%{$date}%' and jiangquanhaoma LIKE '{$area}%' ORDER BY yanhuishijian DESC limit $start,4");
        if ($lists){
            foreach ($lists as $k=>$v){
                $arr = explode(',',$v['jiangquanhaoma']);
                $index = count($arr);
                $first = strstr($arr[0],'-',true)." ".substr(strstr($arr[0],'-'),1,strlen($arr[0]));
                $code = $first.strstr($arr[$index-2],'-');
                $lists[$k]['jiangquanhaoma'] = $code;
            }
            $data['status'] = true;
            $data['msg'] = $lists;
        }
        $this->ajaxReturn($data);
    }

    //照片详情
    public function detailAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $id = $_GET['id'];
        $model = new ModelNew();
        $row = $model->M('wdyh')->where(['id'=>$id])->one();
        if ($row){
            $arr = explode(',',$row['jiangquanhaoma']);
            $index = count($arr);
            $first = strstr($arr[0],'-',true)." ".substr(strstr($arr[0],'-'),1,strlen($arr[0]));
            $code = $first.strstr($arr[$index-2],'-');
            $row['jiangquanhaoma'] = $code;
            if(!$row['dianzan']){
                $row['dianzan'] = 0;
            }
            //微信分享
            $this->library("JSSDK");
            $appid = "wx54dad1a359d51c95";
            $appsecret = "c6000d22a71be19e0caa9b3877fed7e1";
            $jssdk = new JSSDK($appid,$appsecret);
            $jssdk->url = $_GET['url'];
            $data['package'] = $jssdk->GetSignPackage();
            $data['status'] = true;
            $data['msg'] = $row;
        }
        $this->ajaxReturn($data);
    }

    //点赞
    public function likeAction(){
        $data['status'] = false;
        $data['msg'] = "您已经点过赞了";
        $openid = $this->openIdAction();
        $id = $_GET['id'];
        $model = new ModelNew();
        $row = $model->findBySql("select mingdan,dianzan from sl_wdyh WHERE id = {$id}");
        $arr = explode(',',$row[0]['mingdan']);
        foreach ($arr as $v){
            if ($v==$openid){
                $this->ajaxReturn($data);
            }
        }
        $list = $row[0]['mingdan'].",$openid";
        $re = $model->query("update sl_wdyh set dianzan=dianzan+1,mingdan='{$list}' WHERE id = {$id}");
        if ($re){
            $data['status'] = true;
            $data['msg'] = $row[0]['dianzan']+1;
        }
        $this->ajaxReturn($data);
    }

    //照片搜索
    public function searchAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $keywords = isset($_GET['keywords'])?$_GET['keywords']:'not found';
        $model = new ModelNew();
        $row = $model->findBySql("select *from sl_wdyh where yanhuidanhao LIKE '%{$keywords}%' OR jiangquanhaoma LIKE '%{$keywords}%' OR yonghuming LIKE '%{$keywords}%' ORDER BY yanhuishijian DESC ");
        if ($row){
            foreach ($row as $k=>$v){
                $arr = explode(',',$v['jiangquanhaoma']);
                $index = count($arr);
                $first = strstr($arr[0],'-',true)." ".substr(strstr($arr[0],'-'),1,strlen($arr[0]));
                $code = $first.strstr($arr[$index-2],'-');
                $row[$k]['jiangquanhaoma'] = $code;
            }
            $data['status'] = true;
            $data['msg'] = $row;
        }
        $this->ajaxReturn($data);
    }

    //活动期数统计
    public function issueAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $model = new ModelNew();
        $lists = $model->M('wdyh')->groupBy('qihao')->orderBy('dtime DESC')->all();
        if ($lists){
            $data['status'] = true;
            $data['msg'] = $lists;
        }
        $this->ajaxReturn($data);
    }

    //地区
    public function areaAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $model = new ModelNew();
        $lists = $model->M('quyu')->orderBy('dtime DESC')->all();
        if ($lists){
            $data['status'] = true;
            $data['msg'] = $lists;
        }
        $this->ajaxReturn($data);
    }

    //极致之旅名单
    public function doubleAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        //获取最新一期
        $cate = new ModelNew();
        $list= $cate->findBySql("select huojiangqishu from sl_zjdj GROUP BY huojiangqishu ORDER BY dtime DESC ");
        $model = new ModelNew();
        if ($list){
            $lists = $model->findBySql("select *from sl_zjdj where huojiangqishu='{$list[0]['huojiangqishu']}' ORDER BY dtime DESC");

            //获取人名和人名处理
            foreach ($lists as $k=>$v){
                $user = new ModelNew();
                $row = $user->M('user')->find('xingming,xingbie')->where(['yonghuming'=>$v['yonghuming']])->one();
                if (!$row){
                    $lists[$k]['xingming'] = "***";
                }else{
                    if (!isset($row['xingbie'])){
                        $lists[$k]['xingming'] = mb_substr($row['xingming'],0,1)."**";
                    }else{
                        if ($row['xingbie']=='女'){
                            $lists[$k]['xingming'] = mb_substr($row['xingming'],0,1)."女士";
                        }else{
                            $lists[$k]['xingming'] = mb_substr($row['xingming'],0,1)."先生";
                        }
                    }
                }

            }

            if (isset($list[1])){
                $rows = $model->findBySql("select *from sl_zjdj where huojiangqishu='{$list[1]['huojiangqishu']}' ORDER BY dtime DESC");
                foreach ($rows as $k=>$v){
                    $user = new ModelNew();
                    $row = $user->M('user')->find('xingming,xingbie')->where(['yonghuming'=>$v['yonghuming']])->one();
                    if (!$row){
                        $lists[$k]['xingming'] = "***";
                    }else {
                        if (!isset($row['xingbie'])) {
                            $rows[$k]['xingming'] = mb_substr($row['xingming'], 0, 1) . "**";
                        } else {
                            if ($row['xingbie'] == '女') {
                                $rows[$k]['xingming'] = mb_substr($row['xingming'], 0, 1) . "女士";
                            } else {
                                $rows[$k]['xingming'] = mb_substr($row['xingming'], 0, 1) . "先生";
                            }
                        }
                    }
                }
            }
            if ($lists){


                $data['status'] = true;
                $data['one'] = $lists;
                if (isset($rows)){
                    $data['two'] = $rows;
                }else{
                    $data['two'] = "empty";
                }
            }
        }


        $this->ajaxReturn($data);
    }

    //轮播图
    public function banAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $model = new ModelNew();
        $lists = $model->M('banner')->limit(0,3)->where(['status'=>'极致之旅'])->orderBy('paixu ASC')->all();
        if ($lists){
            $data['status'] = true;
            $data['msg'] = $lists;
        }
        $this->ajaxReturn($data);
    }

    //活动规则
    public function ruleAction(){
        $data['status'] = false;
        $data['msg'] = "加载失败";
        $model = new ModelNew();
        $list = $model->M('qh')->orderBy('dtime DESC')->one();
        if ($list){
            $data['status'] = true;
            $data['msg'] = html_entity_decode($list['huodongguize']);
        }
        $this->ajaxReturn($data);
    }
}