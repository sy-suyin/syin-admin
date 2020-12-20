<?php

namespace app\common\criteria;

use syin\Criteria;
use syin\Repository;

/**
 * 查询今日数据
 */
class todayCriteria extends Criteria{

    public function apply($model, Repository $repository){
        $time = strtotime(date('Y-m-d 00:00:00'));
        $query = $model->where('add_time', '>', $time - 1);
        return $query;
    }
}