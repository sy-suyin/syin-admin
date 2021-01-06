<?php

namespace builder\data\driver;

/**
 * 数据类型
 */
class MySql {

    protected $config;
    protected $table_full;
    protected $table_name;

    /**
     * Int类型识别为日期时间的结尾字符,默认会识别为日期文本框
     */
    protected $intDateSuffix = ['time'];

    /**
     * 开关后缀
     */
    protected $switchSuffix = ['switch'];

    /**
     * 富文本后缀
     */
    protected $editorSuffix = ['content'];

    /**
     * 城市后缀
     */
    protected $citySuffix = ['city'];

    /**
     * JSON后缀
     */
    protected $jsonSuffix = ['json'];

    /**
     * Selectpage对应的后缀
     */
    protected $selectpageSuffix = ['_id', '_ids'];

    /**
     * Selectpage多选对应的后缀
     */
    protected $selectpagesSuffix = ['_ids'];

    /**
     * 识别为图片字段
     */
    protected $imageField = ['image', 'images', 'avatar', 'avatars'];

    /**
     * 识别为文件字段
     */
    protected $fileField = ['file', 'files'];

    /**
     * 保留字段
     */
    protected $reservedField = ['admin_id'];

    /**
     * 排除字段
     */
    protected $ignoreFields = [];

    /**
     * 排序字段
     */
    protected $sortField = 'weigh';

    /**
     * 筛选字段
     * @var string
     */
    protected $headingFilterField = 'status';

    /**
     * 添加时间字段
     * @var string
     */
    protected $createTimeField = 'createtime';

    /**
     * 更新时间字段
     * @var string
     */
    protected $updateTimeField = 'updatetime';

    /**
     * 软删除时间字段
     * @var string
     */
    protected $deleteTimeField = 'deletetime';


    public function __construct($config){
        // 数据表前缀
        $table_prefix = $config['data']['prefix'];
		// 数据表全写
		$table_full = $table_prefix.$config['table'];

		// 查询数据表信息
		$table_info = db()->query("show table status like '{$table_full}'");
		$table_info = $table_info[0];

		// 获取数据表名称
		$table_name = $table_info['Comment'];
		$table_name = explode(';', $table_name)[0];
		$table_name = explode(':', $table_name)[0];
        $table_name = trim($table_name);

        $this->config = $config;
        $this->table_full = $table_full;
        $this->table_full = $table_full;
    }

    public function parse(){
        $sql = "SELECT * FROM `information_schema`.`columns` "
        . "WHERE TABLE_SCHEMA = ? AND table_name = ? "
        . "ORDER BY ORDINAL_POSITION";

        $row = db()->query($sql, [config('database.database'), $this->table_full]);

        foreach($row as $val){
            $config['field'] = $field = $val['COLUMN_NAME'];

            $comment = $val['COLUMN_COMMENT'];
            $comment = explode(':', $comment);
            $config['name'] = $comment[0];

            // 设置主键
            if($val['COLUMN_KEY'] == 'PRI' && $this->pk == ''){
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
            $config = $this->getFieldType($val, $config);
            // $element->add($val);
        }
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
        $config = $this->getFieldType($options, $config);

        // 统计各数据类型数

        // $this->items[] = new Item($config);

        return [$config];
    }

    /**
     * 初始化类型值
     *
     */
    // public function initType($options, $config){
	// 	$key 	 = $options['COLUMN_NAME'];
    //     $type 	 = $options['DATA_TYPE'];

    //     // if (preg_match("/{$v}$/i", $field)) {

    //     $config['data_type'] = 'string';
    //     $config['form_type'] = 'text';
    //     $config['is_multi']  = false;

	// 	// 1. 根据数据库字段类型判断
    //     switch ($type) {
    //         case 'bigint':
    //         case 'int':
    //         case 'mediumint':
	// 		case 'smallint':
	// 		case 'tinyint':
	// 			// 整数
	// 			$config['data_type'] = DataType::$INT;
	// 			$config['form_type'] = 'number';
    //             break;
	// 		case 'enum':
	// 			// 下拉单选
	// 			$config['data_type'] = DataType::$STRING;
	// 			$config['form_type'] = 'select';
	// 			$config['is_multi'] = false;
	// 			break;
	// 		case 'set':
	// 			// 下拉多选
	// 			$config['data_type'] = DataType::$ARRAY;
	// 			$config['form_type'] = 'select';
	// 			$config['is_multi'] = true;
    //             break;
    //         case 'decimal':
    //         case 'double':
	// 		case 'float':
	// 			// 浮点数
	// 			$config['data_type'] = DataType::$FLOAT;
	// 			$config['form_type'] = 'number';
    //             break;
    //         case 'longtext':
    //         case 'text':
    //         case 'mediumtext':
    //         case 'smalltext':
	// 		case 'tinytext':
	// 			// 文本域
	// 			$config['data_type'] = DataType::$STRING;
	// 			$config['form_type'] = 'textarea';
	// 			break;
    //         default:
    //             break;
    //     }

	// 	$key_arr = explode('_', $key);

	// 	if(count($key_arr) > 1){
	// 		// 2. 根据数据库字段前后缀判断
	// 		$key_prefix = $key_arr[0];
	// 		$key_suffix = end($key_arr);

	// 		// 判断是否为关联字段
	// 		if($key_suffix == 'id' && $key != 'id'){
	// 			$this->is_relation = true;
	// 			// 此处待完善
	// 			$this->relation_table = substr($key, 0, strrpos($key, '_'));
	// 		}

    //         if($key_suffix == 'data'){
    //             if($type == 'enum'){
    //                 $config['data_type'] = DataType::$STRING;
    //                 $config['form_type'] = 'radio';
    //             } else if($type == 'set'){
    //                 $config['data_type'] = DataType::$ARRAY;
    //                 $config['form_type'] = 'checkbox';
    //             }
    //         }elseif($key_suffix == 'switch'){
    //             if($type == 'tinyint'){
    //                 $config['data_type'] = DataType::$BOOL;
    //                 $config['form_type'] = 'switch';
    //             }
    //         }elseif($key_suffix == 'date'){
    //             if($type == 'int'){
    //                 // 此处为日期, 前端传递格式如 2020-10-01, 后端处理暂未设定
    //                 $config['data_type'] = DataType::$TIME;
    //                 $config['form_type'] = 'time';
    //             }
    //         }elseif($key_suffix == 'time'){
    //             if($type == 'int'){
    //                 // 此处为时间, 前端传递格式如 2020-10-01 00:01:02
    //                 $config['data_type'] = DataType::$TIME;
    //                 $config['form_type'] = 'time';
    //             }
    //         }elseif($type == 'varchar'){
    //             if($key_suffix == 'color'){
    //                 $config['data_type'] = DataType::$STRING;
    //                 $config['form_type'] = 'color';
    //             }elseif($key_suffix == 'file'){
    //                 $config['data_type'] = DataType::$STRING;
    //                 $config['form_type'] = 'file';
    //                 $config['is_multi'] = false;
    //             }elseif($key_suffix == 'files'){
    //                 $config['data_type'] = DataType::$STRING;
    //                 $config['form_type'] = 'file';
    //                 $config['is_multi'] = true;
    //             }elseif($key_suffix == 'image' || $key_suffix == 'avatar'){
    //                 $config['data_type'] = DataType::$STRING;
    //                 $config['form_type'] = 'image';
    //                 $config['is_multi'] = false;
    //             }elseif($key_suffix == 'images' || $key_suffix == 'avatars'){
    //                 $config['data_type'] = 0;
    //                 $config['form_type'] = 'image';
    //                 $config['is_multi'] = false;
    //             }
    //         }

    //         if($key_prefix == 'is'){
    //             if($type == 'tinyint'){
    //                 $config['data_type'] = DataType::$BOOL;
    //                 $config['form_type'] = 'switch';
    //             }
    //         }
	// 	}

	// 	return $config;
    // }

    
    protected function getFieldType($options){
        $field_name = $options['COLUMN_NAME'];
        $type 	    = $options['DATA_TYPE'];

        $input_type = 'text';
        switch ($type) {
            case 'bigint':
            case 'int':
            case 'mediumint':
            case 'smallint':
            case 'tinyint':
				// 整数
                $data_type = 'int';
                $input_type = 'number';
                break;
            case 'enum':
            case 'set':
                // 下拉
                $data_type = 'string';
                $input_type = 'select';
                break;
            case 'decimal':
            case 'double':
            case 'float':
                // 浮点数
                $data_type = 'float';
                $input_type = 'number';
                break;
            case 'longtext':
            case 'text':
            case 'mediumtext':
            case 'smalltext':
            case 'tinytext':
				// 文本域
                $data_type = 'string';
                $input_type = 'textarea';
                break;
            case 'year':
            case 'date':
            case 'time':
            case 'datetime':
            case 'timestamp':
                $data_type = 'int';
                $input_type = 'datetime';
                break;
            default:
                break;
        }

        // 指定后缀说明也是个时间字段
        if ($this->isMatchSuffix($field_name, $this->intDateSuffix)) {
            $data_type  = 'int';
            $input_type = 'datetime';
        }
        // 指定后缀结尾且类型为enum,说明是个单选框
        if ($this->isMatchSuffix($field_name, $this->enumRadioSuffix) && $options['DATA_TYPE'] == 'enum') {
            $data_type  = 'string';
            $input_type = "radio";
        }
        // 指定后缀结尾且类型为set,说明是个复选框
        if ($this->isMatchSuffix($field_name, $this->setCheckboxSuffix) && $options['DATA_TYPE'] == 'set') {
            $data_type  = 'string';
            $input_type = "checkbox";
        }
        // 指定后缀结尾且类型为char或tinyint且长度为1,说明是个Switch复选框
        if ($this->isMatchSuffix($field_name, $this->switchSuffix) && ($options['COLUMN_TYPE'] == 'tinyint(1)' || $options['COLUMN_TYPE'] == 'char(1)') && $options['COLUMN_DEFAULT'] !== '' && $options['COLUMN_DEFAULT'] !== null) {
            $data_type  = 'int';
            $input_type = "switch";
        }
        // 指定后缀结尾城市选择框
        if ($this->isMatchSuffix($field_name, $this->citySuffix) && ($options['DATA_TYPE'] == 'varchar' || $options['DATA_TYPE'] == 'char')) {
            $data_type  = 'string';
            $input_type = "citypicker";
        }
        // 指定后缀结尾JSON配置
        if ($this->isMatchSuffix($field_name, $this->jsonSuffix) && ($options['DATA_TYPE'] == 'varchar' || $options['DATA_TYPE'] == 'text')) {
            $input_type = "fieldlist";
        }
        return $input_type;
    }

    /**
     * 判断是否符合指定后缀
     * @param string $field     字段名称
     * @param mixed  $suffixArr 后缀
     * @return boolean
     */
    protected function isMatchSuffix($field, $suffixArr)
    {
        $suffixArr = is_array($suffixArr) ? $suffixArr : explode(',', $suffixArr);
        foreach ($suffixArr as $k => $v) {
            if (preg_match("/{$v}$/i", $field)) {
                return true;
            }
        }
        return false;
    }
}