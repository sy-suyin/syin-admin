<?php
/**
 * 输入处理
 *
 * @version 1.0.0
 */

//----------------------------------------------------------------------

namespace syin;

class Input{

	/**
	 * 输入类实例
	 */
    protected static $instance;

	/**
	 * 数据处理类绑定
	 */
	protected $binds = [
		// 字符串
		'string'	=> input\StringInput::class,
		// 整数
		'int'		=> input\IntInput::class,
		// 数组
		'array'		=> input\ArrayInput::class,
		// 布尔
		'bool'		=> input\BoolInput::class,
		// 文件
		'file'		=> input\FileInput::class,
		// 浮点数
		'float'		=> input\FloatInput::class,
		// 处理没有传入的输入
		'empty'		=> input\EmptyInput::class
	];

	/**
	 * 各绑定简写对照
	 */
	protected $bind_contrast = [
		's' => 'string',
		'd' => 'int',
		'a' => 'array',
		'b' => 'bool',
		'f' => 'float',
		'ff'=> 'file',
	];

	protected $instances = [];

    public static function getInstance(){
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

	/**
	 * 获取数据
	 */
    public static function get($name, $default = null, $filter = '', $params = null){
		return self::getInstance()->input($name, $default, $filter, $params);
    }

	/**
	 * 返回处理输入类类实例
	 */
    public function make($bind){		
		if(isset($this->instances[$bind])){
			return $this->instances[$bind];
		}

		$class = isset($this->binds[$bind]) ? $this->binds[$bind] : $this->binds['empty'];
		$instance = new $class;
		$this->instances[$bind] = $instance;
		return $instance;
	}

	protected $data = [];

	/**
	 * 绑定获取参数时的数据集
	 * 
	 * @param array $data	数据集
	 *
	 */
	public function bind($data = []){
		$this->data = $data;;
	}

	/**
	 * 获取处理输入时的数据集
	 */
	public function getData(){
		if(empty($this->data)){
			$this->data = $_POST;
		}

		return $this->data;
	}

	/**
	 * 获取数据
	 */
	public function obtain($name, $bind='string', $default = null, $filter = null, $params = null){
		$params = is_null($params) ? $this->getData() : $params;
		$instance = $this->make($bind);
		$result = $instance->execute($name, $params, $default);

		if($result && $filter){
			// 处理输入
			$result = $this->filter($result, $filter, $bind);
		}

		return $result;
	}

	/**
	 * 处理输入
	 */
	public function input($name, $default = null, $filter = '', $params = null){
		// 获取数据类型, 并根据数据类型调用对应的类处理数据
		$type = 's';
		if (strpos($name, '/')) {
			list($name, $type) = explode('/', $name);
		}

		$bind = isset($this->bind_contrast[$type]) ? $this->bind_contrast[$type] : 'empty';

		return $this->obtain($name, $bind, $default, $filter, $params);
	}

	/**
	 * 数据过滤与筛选
	 */
	public function filter($data, $filter, $type = 'string'){
		$instance = $this->make($type);
		if(empty($filter)){
			return $data;
		}

		if(is_string($filter)){
			$filter = explode(',', $filter);
		} else {
			$filter = (array) $filter;
		}
	
		foreach($filter as $func){
			if(! method_exists($instance, $func)){
				continue;
			}

			$data = $instance->$func($data);
		}

		return $data;
	}

	public function __call($name, $arguments){
		$bind = $name;
		$instance = $this->make($bind);
		$arguments[] = $arguments[] = $arguments[] = $arguments[] = null;
		list($name, $default, $filter, $params) = $arguments;

		$result = $instance->execute($name, $params, $default);

		if($result && $filter){
			// 处理输入
			$result = $this->filter($result, $filter, $bind);
		}

		return $result;
	}
}
