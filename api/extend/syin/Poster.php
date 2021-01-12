<?php
/**
 * 输入处理
 *
 * @version 1.0.0
 */

//----------------------------------------------------------------------

namespace syin;

use \think\Image; 

/**
 * 分享海报
 */
class Poster{

    /**
     * 获取public目录路径
     */
    public function getBasePath(){
        $base_path = env('root_path') . 'public/';

        return $base_path;
    }

    /**
     * 生成一张海报
     */
    public function generate($name, $config){
        $hash = md5($name);
        $file_path = 'poster/' . $hash . '.png';
        $save_path = $this->getBasePath() . $file_path;

        if(is_file($save_path)){
            return $file_path;
        }

        $image = Image::open($config['background']);

        // 添加水印
        $this->addWater($image, $config['content']);

        // 保存图片
        $image->save($save_path);

        return $file_path;
    }

    /**
     * 添加水印
     */
    protected function addWater($image, $waters){
		foreach($waters as $params){
            if(empty($params['value'])){
				continue;
			}

            if($params['type'] == 'image'){
                $this->addImageWater($image, $params);
            }else if($params['type'] == 'text'){
                $this->addTextWater($image, $params);
            }
        }
    }

    /**
     * 生成图片水印
     */
    protected function addImageWater($image, $params){
        $base_path = $this->getBasePath();
        $source = $base_path . $params['value'];
        $info = getimagesize($source);
        $flag = true;

        if (false === $info || (IMAGETYPE_GIF === $info[2] && empty($info['bits']))) {
            return false;
        }

        if(!empty($params['width']) && !empty($params['height'])){
            $pos = strrpos($params['value'], '.');
            $source_name = substr($params['value'], 0, $pos);
            $source_name .= '_' . $params['width'] . 'x' . $params['height'];
            // 补上后缀
            $source_name .= substr($params['value'], $pos);

            $temp = Image::open('./qrcode.png');
            $source = $base_path . $source_name;
            $temp->crop($info[0], $info[1], 0, 0, $params['width'], $params['height'])->save($source);
            $flag = true;
        }

        // 添加水印
        $alpha = !empty($params['alpha']) ? $params['alpha'] : 100;
        $image->water($source, $params['locate'], $alpha);

        // 删除水印附加的图片
        $flag && unlink($source);

        return true;
    }

    /**
     * 生成文字水印
     */
    protected function addTextWater($image, $params){
        $base_path = $this->getBasePath();
        $font_path = $base_path . $params['font'];
        $size  = !empty($params['size'])  ?: 40;
        $color = !empty($params['color']) ?: '#000000';

        $image->text($params['value'], $font_path , $size, $color, $params['locate']);	
        return true;
    }
}