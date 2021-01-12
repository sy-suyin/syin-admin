<?php
/**
 * 日志记录
 *
 * @version 1.0.0
 */

//----------------------------------------------------------------------

namespace syin;

class Log{

    /**
     * 调试日志
     */
    public function debug($config){
        $time = !empty($config['time']) ? $config['time'] : date('Y-m-d H:i:s');
        $data = !empty($config['data']) ? $config['data'] : '';

        if(is_array($data)){
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $log_text = "[{$time}]{$data}";

    }
}