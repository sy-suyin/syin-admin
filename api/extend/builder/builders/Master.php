<?php
/**
 * 构建者调度
 */
namespace builder\builders;

use builder\builders\service\ControllerBuilder;
use builder\builders\service\RepositoryBuilder;
use builder\builders\service\ServiceBuilder;
use builder\builders\service\ModelBuilder;

class Master {

    // 
    protected $config;
    // 子建造者们
    protected $builders = [];
    // 子建造者索引
    protected $builders_calss = [
        ControllerBuilder::class,
        RepositoryBuilder::class,
        ServiceBuilder::class,
        ModelBuilder::class,
    ];

    public function __construct($config){
        $this->config = $config;
        $this->generatebuilders();
    }

    /**
     * 生成将参与代码生成的构建者
     */
    public function generatebuilders(){
        foreach($this->config['builders'] as $config){
            $builder = new $config['class']($config['config']);
            $this->builders[] = $builder;
        }
    }

    /**
     * 建造方法
     */
    public function build($element){
        foreach($this->builders as $builder){
            $this->element->rendering($builder, $element);
            $builder->build($element);
        }
    }
}