<?php
/**
 * 后端代码构建者 - 数据仓库构建者
 */

namespace builder\builders\service;

use builder\builders\BaseBuilder;

class ControllerBuilder extends BaseBuilder{

    protected $config;
    protected $element;

    public function __construct($cofnig){
        $this->cofnig = $cofnig;
        parent::__construct();
    }

    /**
     * 渲染
     */
    public function rendering($item, $element){
    }

    /**
     * 构建, 输出结构
     */
    public function build($element){
        $html = $this->fetch('service/controller.tpl', []);

        $class_name = ucfirst($this->camelize($element->table));
        $file_name = $class_name.'.php';
        $save_path = env('ROOT_PATH') . 'dev/controller/'.$file_name;
        $html = '<?php'.PHP_EOL.$html;
        $this->save($save_path, $html);
    }
}