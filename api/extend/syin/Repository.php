<?php
namespace syin;

use think\db\Where;
use think\Model;

class Repository {

	protected $model;
	protected $query;

	/**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

	/**
	 * 输入类实例, 继承该类必须重新定义
	 */
    protected static $instance;

	public function __construct($model = null, $criteria = []) {
		$model || $model = $this->makeModel();

		$this->model = $model;
		$this->criteria = $criteria;
	}

	/**
	 * 返回模型空间路径
	 */
	public function model(){
		return '';
	}

	/**
	 * 获取唯一模型类实例
	 * 使用此方法前需先在继承的仓库中定义 $instance 方法
	 */
    public static function getInstance(){
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

	/**
	 * 生成模型类实例
	 */
	public function makeModel(){
		$model = model($this->model());

		if (!$model instanceof Model){
			throw Exception('Class must be an instance of think\\Model');
		}

		$this->query = $model;
		return $model;
	}

	/**
	 * 查询所有数据
	 */
    public function all($columns = array('*')){
		$this->applyCriteria();
		$results = $this->query->field($columns)->order('id', 'desc')->select();
		return $results;
	}

	/**
	 * 查询数据
	 */
	public function select($where = [], $columns = array('*')){
		$this->applyCriteria();
		$where && $where = new Where($where);
		$results = $this->query->where($where)->field($columns)->order('id', 'desc')->select();
		return $results;
	}

	/**
	 * 查询被软删除的数据
	 */
	public function withDeleted($is_deleted = true){
		$this->query = $is_deleted ? $this->model->onlyTrashed() : $this->model;

		return $this;
	}

	/**
	 * 自定义接口用分页
	 *
	 * @param array $config	 配置参数
	 * 						 page:当前页
	 * 						 num:每页数量
	 * 						 where:查询条件
	 * 						 search:搜索条件
	 * 						 hidden:不输出的字段属性
	 * 						 order:排序条件
	 * @param bool/int $total  传入总记录数将不会自动进行总数计算, 适用于特殊的复杂查询
	 *
	 */
    public function paginate($config = [], $total = false){
		$num 	 = isset($config['num']) 		? $config['num'] 		: 10;
		$page  	 = isset($config['page']) 		? $config['page']  		: 0;
		$where 	 = isset($config['where']) 		? $config['where'] 		: [];
		$search  = isset($config['search']) 	? $config['search'] 	: [];
		$hidden  = isset($config['hidden']) 	? $config['hidden'] 	: [];
		$order 	 = isset($config['order']) 		? $config['order'] 		: [];
		$page  	 = max(intval($page), 1);

		// 处理搜索
		$where = $this->dealSerach($where, $search);

		if(!empty($where) && !($where instanceof Where)){
			$where = new Where($where);
		}

		$this->applyCriteria();
		$data = $this->query
			->where($where)
			->hidden($hidden)
			->page($page, $num)
			->order($order)
			->select();

		if(! $total){
			$total = $this->query->where($where)->count();
		}

		return [
			'data' 	 => $data,
			'page' 	 => $page,
			'num' 	 => $num,
			'total'  => $total,
		];
	}

	/**
	 * 处理搜索, 继承后可覆盖修改此处
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

	/**
	 * 新建数据
	 */
    public function create(array $data){
		$result = $this->model->save($data);

		return $result ? $this->model : false;
	}

	/**
	 * 修改数据
	 */
    public function update(array $data, $id, $attribute ='id'){
		$result = $this->query->save($data, [$attribute => $id]);

		return $result;
	}

    public function find($id, $columns = array('*')){
		$this->applyCriteria();
		$result = $this->query
			->field($columns)
			->where('id', $id)
			->find();

		return $result;
	}

    public function findBy($attribute, $value, $columns = array('*')){
		$this->applyCriteria();
		$result = $this->query
			->field($columns)
			->where($attribute, $value)
			->find();

		return $result;
	}

	/**
	 * 设定模型where查询条件
	 * 
	 * @param array $where
	 * @param bool  $or
	 * 
	 */
	public function where($where, $or = false){
		$query = $this->query;
		// $this->query = $this->query->where($where);
        foreach ($where as $field => $value) {
            if ($value instanceof \Closure) {
                $query = (!$or)
                    ? $query->where($value)
                    : $query->orWhere($value);
            } elseif (is_array($value)) {
                if (count($value) === 2) {
					list($operator, $search) = $value;
				} elseif (count($value) === 1) {
					$search = $value[0];
					$operator = '=';
				}

				$query = (!$or)
					? $query->where($field, $operator, $search)
					: $query->orWhere($field, $operator, $search);
            } else {
                $query = (!$or)
                    ? $query->where($field, '=', $value)
                    : $query->orWhere($field, '=', $value);
            }
		}

		$this->query = $query;
		return $this;
	}

	/**
	 * 重置查询条件
	 */
	public function resetQuery(){
		unset($this->query);
		$this->query = $this->model();
	}

	/**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true){
        $this->skipCriteria = $status;
        return $this;
	}
	
	/**
     * @return mixed
     */
    public function getCriteria() {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria) {
        $this->query = $criteria->apply($this->query, $this);
        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria) {
        $this->criteria[] = $criteria;
        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria() {
        if($this->skipCriteria === true)
            return $this;

        foreach($this->getCriteria() as $criteria) {
            if($criteria instanceof Criteria)
                $this->query = $criteria->apply($this->query, $this);
        }

        return $this;
	}

	public function getLastSql(){
		return $this->model->getLastSql();
	}
}