<?php

namespace builder\data;

use builder\data\driver\MySql;
use builder\elements\Item;

/**
 * 数据类型
 */
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

    // 关联数据
    protected $relations = [];

    // 保留字段
    protected $reserved_fields = [
        'id', 'add_time', 'update_time', 'delete_time'
    ];

    // 
    private $config;

    private $item_configs;

    // 元素集
    protected $items = [];

    public function __construct($config){
        $this->config = $config;
    }

    public function parse($config){
        $this->config = [];
        $this->item_configs = isset($config['items']) ? $config['items'] : [];

        $this->loadMysql();

        foreach($this->item_configs as $conf){
            $this->items[] = new Item($conf);
        }
    }

    /**
     * 加载mysql解析
     */
    public function loadMysql(){
        $sql = new MySql($this->config);

        list($config, $item_configs) = $sql->parse();
        $this->item_configs += $item_configs;
        $this->config = $config;
    }

    /**
     * 渲染数据
     */
    public function rendering($builder){
        foreach($this->items as $item){
            $builder->rendering($item);
        }
    }
}