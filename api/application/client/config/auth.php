<?php

return [
	/**
	 * 后台管理员登录认证的cookie名称
	 *
	 * 类型：字符串
	 */
	'admin_auth_cookie' => 'schedule_admin_auth',

	/**
	 * token授权过期时长，以分钟为单位
	 *
	 * 类型：整型
	 */
	'token_expire' => 60,

	/**
	 * token加密密钥
	 *
	 * 类型：字符串
	 */
	'token_key' => 'QXD1XZTiqKsI*rZK0*M^EbQxDapijpyd',

	/**
	 * 访问白名单, 在该名单中的路由, 未登录时可直接访问
	 */
	'whitelist' => array(
		'login' => ['index'],
		'index' => ['refreshtoken']
	)
];