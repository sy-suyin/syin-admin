<?php
/** 
 * 自定义错误异常处理类
 */
namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;

class Runtime extends Handle{
	public function render(Exception $e){
		$status = 200;

        if (is_error($e)) {
            $status = $e->getStatusCode();
            $error_msg = $e->getErrorMsg();

            if(request()->isAjax()){
                return json(array(
                    'status' => 0,
                    'msg'    => $error_msg
                ), $status);
            }else{
                return $this->display($status, $error_msg);
            }
        }

        if ( $e instanceof HttpException ) {
            $msg = $e->getMessage();
            $msg = explode(':', $msg)[0];

            if('not exists' == substr($msg, -10)){
                $cnt = trim(substr($msg, 0, -10));

                if(in_array($cnt, ['method', 'controller', 'module'])){
                    return $this->display(404, '未找到相关页面');
                }
            }
        }

        /** 
         * 2019-01-14 添加
         * 
         * 当非程序自定义错误或http错误时，记录错误内容到日志中
         */
        $this->setLog($e);

        /** 
         * 2019-01-17 添加
         * 
         * 当关闭调试模式时, 系统出现异常, 屏蔽系统默认的错误处理, 直接加载 500 页面
         */
        $is_debug = config('app_debug');
        if(empty($is_debug)){
            if(request()->isAjax()){
                return json(array(
                    'status' => 0,
                    'msg'    => '服务器异常，请稍后重试'
                ), $status);
            }else{
                return $this->display(500, '');
            }
        }

        //可以在此交由系统处理
        return parent::render($e);
    }

    /** 
     * 显示特殊页面(如 403, 404页面)
     */
    private function display($status, $msg){
        $template = config('http_exception_template');
        $tpl = isset($template[$status]) ? $template[$status] : $template[500];
        $tpl =  env('ROOT_PATH').'/public/html/'.$tpl;

        return \think\Response::create($tpl, 'view')->assign(['msg' => $msg]);
    }

    /**
     * 设置日志
     */
    private function setLog(Exception $e){
        $log_text = '['.date('Y-m-d H:i:s').']'.$e->getMessage().' in '.$e->getFile().' in line '.$e->getLine().PHP_EOL;
        $path = env('RUNTIME_PATH').'error_log'.'/'.date('Ym').'/';

        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }

        $filename	= $path.date('d').'.log.php';
        if(!is_file($filename))
            $log_text = '<?php exit("Access denied!"); ?>'.PHP_EOL.$log_text;

            		
		$logfile = fopen($filename, 'a+');

		if(!$logfile)
			return false;
		
		$loglen = fwrite($logfile, $log_text);
		fclose($logfile);
    }
}