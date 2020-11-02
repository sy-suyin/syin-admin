<?php
namespace app\client\repository;

use syin\Repository;

class AdminLogRepository extends Repository {

    protected static $instance;
    protected static $content;
    protected static $data = null;

	public function model(){
		return 'app\client\model\AdminLog';
    }

    public static function getInstance(){
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * 设置日志内容
     */
    public static function setContent($content = ''){
        self::$content = $content;
    }

    /**
     * 设置日志额外数据
     */
    public static function setData($data = null){
        self::$data = $data;
    }

    /**
     * 记录
     */
    public static function record($content = '', $data = null){
        $instance = self::getInstance();
        $request = request();
        $auth = $request->auth;
        $admin_id = $auth->isLogin() ? $auth->id : 0;

        if(is_null($data)){
            $data = self::$data;
        }

        if(is_null($data)){
            $data = $request->param('', null, 'trim,strip_tags,htmlspecialchars');
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        if($content == '' && $request->title){
            $content = $request->title; 
        }

        $instance->model->save([
            'admin_id'   => $admin_id,
            'module'     => $request->module(),
            'controller' => $request->controller(),
            'action'     => $request->action(),
            'url'        => '',
            'content'    => $content,
            'data'       => $data,
            'client_ip'  => substr($request->server('HTTP_USER_AGENT'), 0, 255),
            'user_agent' => $request->ip(),
        ]);
    }
}