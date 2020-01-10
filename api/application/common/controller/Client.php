<?php
/** 
 * 管理后台基类
 */

namespace app\common\controller;

use think\Controller;
use think\Request;

class Client extends Controller{

	public function __construct(){
		parent::__construct();
	}

	/** 
	 * 空页面
	 */
	public function _empty(Request $request){
		// $pages = \think\facade\View::__get('menus_accesses');

		// $controller = $request->controller();
		// $controller = strtolower($controller);

		// if(empty($pages)){
		// 	return show_error('未找到相关页面', 404);
		// }

		// if(!isset($pages[$controller])){
		// 	return show_error('未找到相关页面', 404);
		// }else{
		// 	$page = reset($pages[$controller]);

		// 	$this->redirect($page['controller'].'/'.$page['action']);
		// }
	}
}