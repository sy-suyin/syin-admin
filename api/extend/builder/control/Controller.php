<?php
/**
 * 构建者调度
 */
namespace builder\control;

use builder\builders\service\ControllerBuilder;
use builder\builders\service\RepositoryBuilder;
use builder\builders\service\ServiceBuilder;
use builder\builders\service\ModelBuilder;

class Controller {

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

        // 生成构建者
        $this->generateBuilders();

        // 预处理数据
        $this->generateData();
    }

    /**
     * 生成将参与代码生成的构建者
     */
    protected function generateBuilders(){
        foreach($this->config['builders'] as $config){
            $builder = new $config['class']($config['config']);
            $this->builders[] = $builder;
        }
    }

    /**
     * 预处理配置信息
     */
    protected function generateData(){
        $this->element = new Table($this->config['master'], $this->config['items']);
    }

    /**
     * 建造方法
     */
    public function build(){
        foreach($this->builders as $builder){
            $this->element->rendering($builder);
            $builder->build($this->element);
        }
    }
}