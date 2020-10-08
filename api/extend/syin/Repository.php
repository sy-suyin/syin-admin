<?php
namespace syin;

use think\db\Where;
use think\Model;

class Repository {

	protected $model;

	public function __construct($model = null) {
		$model || $model = $this->makeModel();

		$this->model = $model;
	}

	public function model(){
	}

	public function makeModel(){
		$model = model($this->model());

		if (!$model instanceof Model){

		}

		return $model;
	}

    public function all($columns = array('*')){
		$results = $this->model->field($columns)->order('id', 'desc')->select();
		return $results;
	}

	/**
	 * 自定义接口用分页
	 *
	 * @param array $config	 配置参数
	 * 						 page:当前页
	 * 						 num:每页数量
	 * 						 where:查询条件
	 * 						 hidden:不输出的字段属性
	 * 						 order:排序条件
	 * @param bool/int $total  传入总记录数将不会自动进行总数计算, 适用于特殊的复杂查询
	 *
	 */
    public function paginate($config = [], $total = false){
		$num 	 = isset($config['num']) 		? $config['num'] 		: 10;
		$page  	 = isset($config['page']) 		? $config['page']  		: 0;
		$where 	 = isset($config['where']) 		? $config['where'] 		: [];
		$search  = isset($config['search']) 	? $config['search'] 		: [];
		$hidden  = isset($config['hidden']) 	? $config['hidden'] 	: [];
		$order 	 = isset($config['order']) 		? $config['order'] 		: [];
		$page  	 = max(intval($page), 1);

		// 处理搜索
		$where = $this->dealSerach($where, $search);

		if(!empty($where) && !($where instanceof Where)){
			$where = new Where($where);
		}

		$data = $this->model
			->where($where)
			->hidden($hidden)
			->page($page, $num)
			->order($order)
			->select();

		if(! $total){
			$total = $this->model->where($where)->count();
		}

		return [
			'data' 	 => $data,
			'page' 	 => $page,
			'num' 	 => $num,
			'total'  => $total,
		];
	}

	/**
	 * 处理搜索
	 */
	protected function dealSerach($where, $search){
		if(empty($search)){
			return $where;
		}

		foreach($search as $key => $val){
			$where[$key] = ['like', '%'.$val.'%'];
		}

		// 如果使用es搜索, 此处只需返回搜索后的id给where

		return $where;
	}

    public function create(array $data){
		$result = $this->model->save($data);

		return $result ? $this->model : false;
	}

    public function update(array $data, $id){
		$result = $this->model->save($data, ['id' => $id]);

		return $result;
	}

    public function delete($id){

	}

    public function find($id, $columns = array('*')){
		$result = $this->model
			->field($columns)
			->where('id', $id)
			->find();

		return $result;
	}

    public function findBy($attribute, $value, $columns = array('*')){
		$result = $this->model
			->field($columns)
			->where($attribute, $value)
			->find();

		return $result;
	}
}