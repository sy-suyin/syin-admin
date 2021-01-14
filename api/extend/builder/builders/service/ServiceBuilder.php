<?php
/**
 * 后端代码构建者 - 逻辑层构建者
 */

namespace builder\builders\service;

use builder\common\BaseBuilder;
use builder\dataset\DataType;
use builder\enums\DataTypeEnum;

class ServiceBuilder extends BaseBuilder {

    protected $config;
    protected $element;
    protected $fields = [];
    protected $rules = [];

    public function __construct($config){
        $this->config = $config;
        parent::__construct();
    }

    /**
     * 渲染
     */
    public function rendering($item, $element){
        $field = $item->field;
        $option = false;
        switch($item->data_type){
            case DataTypeEnum::STRING:{
                $option = ['type' => 'string', 'name' => $field];
                break;
            }
            case DataTypeEnum::INT:{
                $option = ['type' => 'int', 'name' => $field];
                break;
            }
            case DataTypeEnum::FLOAT:{
                $option = ['type' => 'float', 'name' => $field];
                break;
            }
            case DataTypeEnum::ARRAY:{
                $option = ['type' => 'array', 'name' => $field];
                break;
            }
            case DataTypeEnum::BOOL:{
                $option = ['type' => 'bool', 'name' => $field];
                break;
            }
            case DataTypeEnum::DATE:{
                $option = ['type' => 'string', 'name' => $field, 'filter' => 'time'];
                break;
            }
            case DataTypeEnum::TIME:{
                $option = ['type' => 'string', 'name' => $field, 'filter' => 'time'];
                break;
            }
        }

        $this->rules[$field.'|'.$item->name] = 'require';
        $this->fields[$field] = $option;
    }

    /**
     * 构建, 输出结构
     */
    public function build($element){
        $this->element = $element;

        $html = $this->fetch('service/service.tpl', [
            'fields'     => $this->fields,
            'rules'      => $this->rules,
        ]);

        $class_name = ucfirst($this->camelize($element->table));
        $file_name = $class_name.'Service.php';
        $save_path = env('ROOT_PATH') . 'dev/service/'.$file_name;
        $html = '<?php'.PHP_EOL.$html;
        $this->save($save_path, $html);
    }
}