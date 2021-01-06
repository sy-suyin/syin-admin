<?php

namespace builder\elements;

use builder\dataset\DataType;

class Master {

    // 主键
    protected $pk = '';

    // 数据表(去除前缀后的)
    protected $table = '';

    // 数据表名称
    protected $table_name = '';

    // 是否软删除
    protected $is_sort_deleted = false;

    // 是否有关联
    protected $has_relation = false;

    // 字段集
    protected $items = [];

    // 关联数据
    protected $relations = [];

    // 保留字段
    protected $reserved_fields = [
        'id', 'add_time', 'update_time', 'delete_time'
    ];

    public function __construct($table, $table_name){
        $this->table = $table;
        $this->table_name = $table_name;
    }

    public function add($options){
        $config['field'] = $field = $options['COLUMN_NAME'];

        $comment = $options['COLUMN_COMMENT'];
        $comment = explode(':', $comment);
        $config['name'] = $comment[0];

        // 设置主键
        if($options['COLUMN_KEY'] == 'PRI' && $this->pk == ''){
            $this->pk = $field;
        }

        // 判断删除
        if($field == 'delete_time'){
            $this->is_sort_deleted = true;
        }

        if(in_array($field, $this->reserved_fields)){
            return;
        }

        if(isset($comment[1])){
            $data = explode(',', $comment[1]);
            foreach($data as $key => $val){
                $config['data'][$key] = explode('=', $val);
            }
        }

        // 计算数据类型
        $config = $this->initType($options, $config);

        // 统计各数据类型数

        $this->items[] = new Item($config);
    }

    /**
     * 初始化类型值
     * 	
	 *  数据类型
	 *      0: 字符串
	 *      1: 数字
	 *      2: 浮点数
	 *      3: 数组
	 *      4: 布尔
	 *      5: 日期
	 *      6: 时间
     * 
	 *  表单类型
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
    public function initType($options, $config){
		$key 	 = $options['COLUMN_NAME'];
        $type 	 = $options['DATA_TYPE'];
        
        // if (preg_match("/{$v}$/i", $field)) {

        $config['data_type'] = 'string';
        $config['form_type'] = 'text';
        $config['is_multi']  = false;

		// 1. 根据数据库字段类型判断
        switch ($type) {
            case 'bigint':
            case 'int':
            case 'mediumint':
			case 'smallint':
			case 'tinyint':
				// 整数
				$config['data_type'] = DataType::$INT;
				$config['form_type'] = 'number';
                break;
			case 'enum':
				// 下拉单选
				$config['data_type'] = DataType::$STRING;
				$config['form_type'] = 'select';
				$config['is_multi'] = false;
				break;
			case 'set':
				// 下拉多选
				$config['data_type'] = DataType::$ARRAY;
				$config['form_type'] = 'select';
				$config['is_multi'] = true;
                break;
            case 'decimal':
            case 'double':
			case 'float':
				// 浮点数
				$config['data_type'] = DataType::$FLOAT;
				$config['form_type'] = 'number';
                break;
            case 'longtext':
            case 'text':
            case 'mediumtext':
            case 'smalltext':
			case 'tinytext':
				// 文本域 
				$config['data_type'] = DataType::$STRING;
				$config['form_type'] = 'textarea';
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

            if($key_suffix == 'data'){
                if($type == 'enum'){
                    $config['data_type'] = DataType::$STRING;
                    $config['form_type'] = 'radio';
                } else if($type == 'set'){
                    $config['data_type'] = DataType::$ARRAY;
                    $config['form_type'] = 'checkbox';
                }
            }elseif($key_suffix == 'switch'){
                if($type == 'tinyint'){
                    $config['data_type'] = DataType::$BOOL;
                    $config['form_type'] = 'switch';
                }
            }elseif($key_suffix == 'date'){
                if($type == 'int'){
                    // 此处为日期, 前端传递格式如 2020-10-01, 后端处理暂未设定
                    $config['data_type'] = DataType::$TIME;
                    $config['form_type'] = 'time';
                }
            }elseif($key_suffix == 'time'){
                if($type == 'int'){
                    // 此处为时间, 前端传递格式如 2020-10-01 00:01:02
                    $config['data_type'] = DataType::$TIME;
                    $config['form_type'] = 'time';
                }
            }elseif($type == 'varchar'){
                if($key_suffix == 'color'){
                    $config['data_type'] = DataType::$STRING;
                    $config['form_type'] = 'color';
                }elseif($key_suffix == 'file'){
                    $config['data_type'] = DataType::$STRING;
                    $config['form_type'] = 'file';
                    $config['is_multi'] = false;
                }elseif($key_suffix == 'files'){
                    $config['data_type'] = DataType::$STRING;
                    $config['form_type'] = 'file';
                    $config['is_multi'] = true;
                }elseif($key_suffix == 'image' || $key_suffix == 'avatar'){
                    $config['data_type'] = DataType::$STRING;
                    $config['form_type'] = 'image';
                    $config['is_multi'] = false;
                }elseif($key_suffix == 'images' || $key_suffix == 'avatars'){
                    $config['data_type'] = 0;
                    $config['form_type'] = 'image';
                    $config['is_multi'] = false;
                }
            }

            if($key_prefix == 'is'){
                if($type == 'tinyint'){
                    $config['data_type'] = DataType::$BOOL;
                    $config['form_type'] = 'switch';
                }
            }
		}

		return $config;
	}

    /**
     * 渲染数据
     */
    public function rendering($builder){
        foreach($this->items as $item){
            $builder->rendering($item);
        }
    }

	public function __get($name){
		return $this->$name;
	}
}