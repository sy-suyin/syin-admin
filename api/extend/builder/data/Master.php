<?php

namespace builder\data;

use builder\data\driver\MySql;
use builder\elements\Item;

/**
 * 数据类型
 */
class Master {

    public function __construct(){
    }

    public function parse($config){
        $config = $this->loadMysql($config);

        return $config;
    }
    
    /**
     * 加载json配置解析
     */
    public function loadJson($config){
    }

    /**
     * 加载mysql解析
     */
    public function loadMysql($config){
        $sql = new MySql($config);
        $master = $config['master'];
        $prefix = isset($master['prefix']) ? $master['prefix'] : null;
        $result = $sql->parse($master['table'], $prefix);
        $config['master'] = $result['master'];
        $config['items'] = array_merge($config['items'], $result['items']);
        return $config;
    }
}