<?php
namespace syin\builder;

class Element{

	protected $key;

	// 数据名称
	protected $name;

	// 默认数据
	protected $data;

	/**
	 * 数据类型
	 *
	 * 0: 字符串
	 * 1: 数字
	 * 2: 浮点数
	 * 3: 数组
	 * 4: 布尔
	 * 5: 日期
	 * 6: 时间
	 */
	protected $data_type = 0;

	/**
	 * 表单类型
	 *
	 *		文本 	text
	 * 		数字 	number
	 * 		下拉 	select
	 * 		文本域 	textarea
	 * 		单选	radio
	 * 		复选	checkbox
	 * 		开关	switch
	 * 		时间	time
	 * 		图片	image
	 * 		文件	file
	 * 		颜色	color
	 */
	protected $form_type = 'text';

	// 是否为保留字段
	protected $is_reserved = false;

	// 默认保留方法
	protected $reserved = ['id', 'sort', 'weight', 'is_disabled', 'add_time', 'update_time', 'delete_time'];

	// 该字段是否关联其他数据表
	protected $is_relation = false;

	// 关联的其他表
	protected $relation_table = '';

	// 是否为多选, 仅部分表单类型支持
	protected $is_multi = false;

	public function __construct($params){
		$this->init($params);
	}

	/**
	 * 初始化
	 */
	public function init($params){
		$key 	 = $params['COLUMN_NAME'];
		$type 	 = $params['DATA_TYPE'];
		$comment = $params['COLUMN_COMMENT'];

		if(in_array($key, $this->reserved)){
			$this->is_reserved = true;
		}

		$comment = explode(';', $comment);
		$this->name = $comment[0];

		// 此处可读取出额外值
		if(count($comment) > 1){
			// ...
		}

		// 获取数据类型和表单类型

		// 1. 根据数据库字段类型判断
        switch ($type) {
            case 'bigint':
            case 'int':
            case 'mediumint':
			case 'smallint':
			case 'tinyint':
				// 整数
				$this->data_type = 1;
				$this->form_type = 'number';
                break;
			case 'enum':
				// 下拉单选
				$this->data_type = 0;
				$this->form_type = 'select';
				$this->is_multi = false;
				break;
			case 'set':
				// 下拉多选
				$this->data_type = 3;
				$this->form_type = 'select';
				$this->is_multi = true;
                break;
            case 'decimal':
            case 'double':
			case 'float':
				// 浮点数
				$this->data_type = 2;
				$this->form_type = 'number';
                break;
            case 'longtext':
            case 'text':
            case 'mediumtext':
            case 'smalltext':
			case 'tinytext':
				// 文本域 
				$this->data_type = 0;
				$this->form_type = 'textarea';
				break;
            default:
                break;
		}

		$key_arr = explode('_', $key);
		
		if(count($key_arr) > 1){
			// 2. 根据数据库字段前后缀判断
			$key_prefix = $key_arr[0];
			$key_suffix = end($key_arr);

			// 判断是否为关联字段
			if($key_suffix == 'id' && $key != 'id'){
				$this->is_relation = true;
				// 此处待完善
				$this->relation_table = substr($key, 0, strrpos($key, '_'));
			}
	
			{
				if($key_suffix == 'data'){
					if($type == 'enum'){
						$this->data_type = 0;
						$this->form_type = 'radio';
					} else if($type == 'set'){
						$this->data_type = 3;
						$this->form_type = 'checkbox';
					}
				}elseif($key_suffix == 'switch'){
					if($type == 'tinyint'){
						$this->data_type = 4;
						$this->form_type = 'switch';
					}
				}elseif($key_suffix == 'date'){
					if($type == 'int'){
						// 此处为日期, 前端传递格式如 2020-10-01, 后端处理暂未设定
						$this->data_type = 5;
						$this->form_type = 'time';
					}
				}elseif($key_suffix == 'time'){
					if($type == 'int'){
						// 此处为时间, 前端传递格式如 2020-10-01 00:01:02
						$this->data_type = 5;
						$this->form_type = 'time';
					}
				}elseif($type == 'varchar'){
					if($key_suffix == 'color'){
						$this->data_type = 0;
						$this->form_type = 'color';
					}elseif($key_suffix == 'file'){
						$this->data_type = 0;
						$this->form_type = 'file';
						$this->is_multi = false;
					}elseif($key_suffix == 'files'){
						$this->data_type = 0;
						$this->form_type = 'file';
						$this->is_multi = true;
					}elseif($key_suffix == 'image' || $key_suffix == 'avatar'){
						$this->data_type = 0;
						$this->form_type = 'image';
						$this->is_multi = false;
					}elseif($key_suffix == 'images' || $key_suffix == 'avatars'){
						$this->data_type = 0;
						$this->form_type = 'image';
						$this->is_multi = false;
					}
				}
			}

			{
				if($key_prefix == 'is'){
					if($type == 'tinyint'){
						$this->data_type = 4;
						$this->form_type = 'switch';
					}
				}
			}
		}

		$this->key = $key;
	}

	public function __get($name){
		return $this->$name;
	}
}