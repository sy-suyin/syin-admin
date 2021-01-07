<?php
namespace app\common\criteria;

use syin\Criteria;
use syin\Repository;

/**
 * 基础查询
 */
class BaseCriteria extends Criteria{

    public function apply($model, Repository $repository){
        $query = $model->where('is_disabled', 0);
        return $query;
    }
}