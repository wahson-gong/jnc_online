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
            foreach ($lists as $v){
                $times = $v['fixed_times'];
                $percent = $v['fixed_percent'];
                $prize = new ModelNew();
                $prize->query("update sl_listjp set times={$times},percent={$percent} WHERE id={$v['id']}");
            }

            echo "更新完成";

    }
}