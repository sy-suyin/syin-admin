<?php
namespace app\client\controller;

use builder\Builder;
use builder\data\driver\MySql;

class Develop {

    /**
     * 获取所有表信息
     */
    public function getTables(){
        $tables = db()->getConnection()->getTables(config('database.database'));
        $prefix = config('database.prefix');

        if($prefix){
            $temp = [];
            foreach($tables as $table){
                $table = substr($table, 3);

                if(in_array($table, ['admin'])){
                    continue;
                }

                $temp[] = $table;
            }
            
            $tables = $temp;
        }

        return show_success('', [
            'result' => $tables
        ]);
    }

    /**
     * 获取表具体信息
     */
    public function getTableDetail(){
        new Builder();

        $mysql = new Mysql();
        $mysql -> parse('admin');
    }

    /**
     * 创建数据表
     */
    public function createTable(){

    }

    /**
     * 修改数据表
     */
    public function editTable(){

    }
}