<?php

/** 
 * 简易加解密
 * 
 * 相比该方法, 在可能的情况下, 最好选择使用 openssl_encrypt 方法
 */
namespace app\common\library;

class Mcrypt{

	/**
	 * 加密
	 * 
	 * @param string 	$key	秘钥
	 * @param mixed 	$data	需加密数据
	 * 
	 * @return mixed
	 */
	public static function encrypt($key, $data){
		$td = mcrypt_module_open("des", "", "ecb", "");//使用MCRYPT_DES算法,ecb模式
		$size = mcrypt_enc_get_iv_size($td);       //设置初始向量的大小
		$iv = mcrypt_create_iv($size,MCRYPT_RAND); //创建初始向量
	
		$key_size = mcrypt_enc_get_key_size($td);       //返回所支持的最大的密钥长度（以字节计算）
		$salt = '';
		$subkey = substr(md5(md5($key).$salt), 0,$key_size);//对key复杂处理，并设置长度
	
		mcrypt_generic_init($td, $subkey, $iv);
		$endata = mcrypt_generic($td, $data);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		$endata = base64_encode($endata);
		return $endata;
	}

	/**
	 * 解密
	 * 
	 * @param string $key		秘钥
	 * @param mixed $endata		解密数据
	 * 
	 * @return mixed
	 */
	public static function decrypt($key, $endata){
		$endata = base64_decode($endata, false);
		$td = mcrypt_module_open("des", "", "ecb", "");//使用MCRYPT_DES算法,ecb模式
		$size = mcrypt_enc_get_iv_size($td);       //设置初始向量的大小
		$iv = mcrypt_create_iv($size,MCRYPT_RAND); //创建初始向量
		$key_size = mcrypt_enc_get_key_size($td);       //返回所支持的最大的密钥长度（以字节计算）
		$salt = '';
		$subkey = substr(md5(md5($key).$salt), 0,$key_size);//对key复杂处理，并设置长度
		mcrypt_generic_init($td, $subkey, $iv);
		$data = rtrim(mdecrypt_generic($td, $endata)).'\n';
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return rtrim($data, '/\n');
	}
}