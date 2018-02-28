<?php

/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018/2/6
 * Time: 14:54
 */
class BackupsController extends BaseController
{
    public function updateAction(){
        $model = new ModelNew();
        $lists = $model->findBySql("select *from sl_listjp");
        $des = "";
        foreach ($lists as $v){
            if ($v['num']==0){
                echo "{$v['jiangpin']}已被抽完。";
                $des .= "\n{$v['jiangpin']}已被抽完。\n";
            }else{
                $times = $v['fixed_times'];
                $percent = $v['fixed_percent'];
                $prize = new ModelNew();
                $re = $prize->query("update sl_listjp set times={$times},percent={$percent} WHERE id={$v['id']}");
                if ($re){
                    echo "{$v['jiangpin']}更正概率{$percent},更正当日开奖次数{$times}；";
                    $des .= "{$v['jiangpin']}更正概率{$percent},更正当日开奖次数{$times}；";
                }else{
                    echo "{$v['jiangpin']}更正失败；";
                    $des .= "\n{$v['jiangpin']}更正失败；\n";
                }
            }
        }
        $arr['u1'] = "System";
        $arr['u2'] = $des;
        $arr['u3'] = $_SERVER["REMOTE_ADDR"];
        $arr['u4'] = "自动更新";
        $system = new ModelNew();
        $system->M("system")->insert($arr);
        echo "更新完成！";
    }
}