<?php

namespace builder\control;

use builder\dataset\DataType;

class Table {

    /**
     * 主要配置
     */
    protected $config;

    // 控件集
    protected $items = [];

    public function __construct($config, $items = []){
        $this->config = $config;

        $this->generateItems($items);
        // $this->table = $table;
        // $this->table_name = $table_name;
    }

    /**
     * 生成控件集合
     */
    protected function generateItems($items = []){
        foreach($items as $item){
            $this->items[] = new Item($item);
        }
    }

    /**
     * 渲染数据
     */
    public function rendering($builder){
        foreach($this->items as $item){
            $builder->rendering($item, $this);
        }
    }

	public function __get($name){
		return isset($this->config[$name]) ? $this->config[$name] : false;
	}
}