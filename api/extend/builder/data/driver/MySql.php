<?php

namespace builder\data\driver;

use builder\enums\DataTypeEnum;
use builder\enums\ItemTypeEnum;

/**
 * mysql数据解析
 */
class MySql {

    /**
     * 根据数据表生成的配置数据
     */
    protected $config;

    /**
     * Enum类型识别为单选框的结尾字符,默认会识别为单选下拉列表
     */
    protected $enumRadioSuffix = ['data', 'state', 'status'];

    /**
     * Set类型识别为复选框的结尾字符,默认会识别为多选下拉列表
     */
    protected $setCheckboxSuffix = ['data', 'state', 'status'];

    /**
     * Int类型识别为日期时间的结尾字符,默认会识别为日期文本框
     */
    protected $intDateSuffix = ['time'];

   /**
     * 开关后缀
     */
    protected $switchPrefix = ['is'];

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

    public function __construct($config = null){
        // 此处设置过滤跳过字段

        if(!is_null($config)){
            !empty($config['ignoreFields']) && $this->ignoreFields = $config['ignoreFields'];
            !empty($config['reservedField']) && $this->reservedField = $config['reservedField'];
            !empty($config['createtime']) && $this->createtime = $config['createtime'];
            !empty($config['updateTimeField']) && $this->updateTimeField = $config['updateTimeField'];
            !empty($config['deleteTimeField']) && $this->deleteTimeField = $config['deleteTimeField'];
        }
    }

    /**
     * 获取配置数据
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     * 解析数据表
     */
    public function parse($table, $prefix = null){
        if(is_null($prefix)){
            $prefix = config('database.prefix');
        }

        $table_full = $prefix . $table;

        // 查询数据表信息
        $table_info = db()->query("show table status like '{$table_full}'");

        if(empty($table_info)){
            return false;
        }

        $table_info = $table_info[0];
        $table_title = $table_info['Comment'];
        $table_title = explode(';', $table_title)[0];
        $table_title = explode(':', $table_title)[0];
        // 主配置
        $master = [
            'pk'    => '',
            'table' => $table,
            'title' => $table_title,
            'table_full' => $table_full,
            'is_sort_deleted' => false, // 是否为软删除
        ];
        // 控件配置数据 
        $items = [];

        /**
         * 获取数据表数据
         */

        $sql = "SELECT * FROM `information_schema`.`columns` "
            . "WHERE TABLE_SCHEMA = ? AND table_name = ? "
            . "ORDER BY ORDINAL_POSITION";

        $row = db()->query($sql, [config('database.database'), $table_full]);

        foreach($row as $options){
            $comment = $options['COLUMN_COMMENT'];
            $comment = explode(':', $comment);
            $item['name'] = $comment[0];
            $item = [
                'name'  => $options['COLUMN_NAME'],
                'title' => $comment[0],
                'data'  => [],
            ];

            // 设置主键
            if($options['COLUMN_KEY'] == 'PRI' && $master['pk'] == ''){
                $master['pk'] = $item['name'];
            }

            // 判断删除
            if($item['name'] == $this->deleteTimeField){
                $master['is_sort_deleted'] = true;
            }

            // if(in_array($field, $this->reserved_fields)){
            //     return;
            // }

            if(isset($comment[1])){
                $data = explode(',', $comment[1]);
                foreach($data as $key => $val){
                    $item['data'][$key] = explode('=', $val);
                }
            }

            // 计算数据类型
            $items[] = $this->getItemType($options, $item);
        }

        $this->config = [
            'master' => $master,
            'items'  => $items,
        ];
        return $this->config;
    }

    /**
     * 获取表单控件类型
     */
    protected function getItemType($options, $config = []){
        $field_name = $options['COLUMN_NAME'];
        $type 	    = $options['DATA_TYPE'];
        $config['date_type'] = DataTypeEnum::STRING;
        $config['item_type'] = ItemTypeEnum::STRING;

        switch ($type) {
            case 'bigint':
            case 'int':
            case 'mediumint':
            case 'smallint':
            case 'tinyint':
                // 整数
                $config['date_type'] = DataTypeEnum::INT;
                $config['item_type'] = ItemTypeEnum::NUMBER;
                break;
            case 'enum':
                // 下拉(单选)
                $config['date_type'] = DataTypeEnum::STRING;
                $config['item_type'] = ItemTypeEnum::SELECT;
                break;
            case 'set':
                // 下拉(多选)
                $config['date_type'] = DataTypeEnum::STRING;
                $config['item_type'] = ItemTypeEnum::SELECTS;
                break;
            case 'decimal':
            case 'double':
            case 'float':
                // 浮点数
                $config['date_type'] = DataTypeEnum::FLOAT;
                $config['item_type'] = ItemTypeEnum::NUMBER;
                break;
            case 'longtext':
            case 'text':
            case 'mediumtext':
            case 'smalltext':
            case 'tinytext':
                // 文本域
                $config['date_type'] = DataTypeEnum::STRING;
                $config['item_type'] = ItemTypeEnum::TEXT;
                break;
            case 'year':
            case 'date':
            case 'time':
            case 'datetime':
            case 'timestamp':
                $config['date_type'] = DataTypeEnum::INT;
                $config['item_type'] = ItemTypeEnum::TIME;
                break;
            default:
                break;
        }

        // 指定后缀说明也是个时间字段
        if ($this->isMatchSuffix($field_name, $this->intDateSuffix)) {
            $config['date_type'] = DataTypeEnum::INT;
            $config['item_type'] = ItemTypeEnum::TIME;
        }
        // 指定后缀结尾且类型为enum,说明是个单选框
        else if ($this->isMatchSuffix($field_name, $this->enumRadioSuffix) && $options['DATA_TYPE'] == 'enum') {
            $config['date_type'] = DataTypeEnum::STRING;
            $config['item_type'] = ItemTypeEnum::RADIO;
        }
        // 指定后缀结尾且类型为set,说明是个复选框
        else if ($this->isMatchSuffix($field_name, $this->setCheckboxSuffix) && $options['DATA_TYPE'] == 'set') {
            $config['date_type'] = DataTypeEnum::STRING;
            $config['item_type'] = ItemTypeEnum::CHECKBOX;
        }
        // 指定前缀开始或后缀结尾且类型为char或tinyint且长度为1,说明是个Switch复选框
        else if (($this->isMatchPrefix($field_name, $this->switchPrefix) || $this->isMatchSuffix($field_name, $this->switchSuffix)) && ($options['COLUMN_TYPE'] == 'tinyint(1)' || $options['COLUMN_TYPE'] == 'char(1)') && $options['COLUMN_DEFAULT'] !== '' && $options['COLUMN_DEFAULT'] !== null) {
            $config['date_type'] = DataTypeEnum::INT;
            $config['item_type'] = ItemTypeEnum::SWITCH;
        }
        // 指定后缀结尾城市选择框
        else if ($this->isMatchSuffix($field_name, $this->citySuffix) && ($options['DATA_TYPE'] == 'varchar' || $options['DATA_TYPE'] == 'char')) {
            $config['date_type'] = DataTypeEnum::STRING;
            $config['item_type'] = ItemTypeEnum::CITY;
        }
        // 指定后缀结尾JSON配置
        else if ($this->isMatchSuffix($field_name, $this->jsonSuffix) && ($options['DATA_TYPE'] == 'varchar' || $options['DATA_TYPE'] == 'text')) {
            $config['date_type'] = DataTypeEnum::STRING;
            $config['item_type'] = ItemTypeEnum::JSON;
        }

        return $config;
    }

    /**
     * 判断是否符合指定前缀
     * @param string $field     字段名称
     * @param mixed  $suffixArr 后缀
     * @return boolean
     */
    protected function isMatchPrefix($field, $prefixArr)
    {
        $prefixArr = is_array($prefixArr) ? $prefixArr : explode(',', $prefixArr);
        foreach ($prefixArr as $k => $v) {
            if (preg_match("/^{$v}/i", $field)) {
                return true;
            }
        }
        return false;
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