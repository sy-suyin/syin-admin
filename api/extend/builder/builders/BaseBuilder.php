<?php
/**
 * 构建者基类
 */

namespace builder\builders;

class BaseBuilder{

    protected $tpl_path = '';

    public function __construct(){
        $this->tpl_path = env('ROOT_PATH') . 'extend/syin/builder/template/';
    }

	/**
	 * 下划线转驼峰
	 */
	function camelize($uncamelized_words,$separator='_'){
		$uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
   		return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

	/**
     * 驼峰命名转下划线命名
     */
    function uncamelize($camelCaps,$separator='_'){
    	return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

    /**
     * 渲染模板页面
     */
    public function fetch($path, $data = []){
        $table = $this->element->table;
        $func_name = $this->camelize($table);

        $data['table'] = $table;
        $data['table_name'] = $this->element->table_name;
        $data['class_name'] = ucfirst($func_name);
        $data['func_name'] = $func_name;
        $path = $this->tpl_path . $path;
        $html = app('view')->fetch($path, $data);

        return $html;
    }

    /**
     * 保存模板内容
     */
    public function save($save_path, $html){
        $save_dir = dirname($save_path);
        if(!is_dir($save_dir)){
            mkdir($save_dir, 0755, true);
        }
        file_put_contents($save_path, $html);
    }
}