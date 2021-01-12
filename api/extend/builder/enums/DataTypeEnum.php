<?php

namespace builder\enums;

use builder\common\BaseEnum;

/**
 * 数据类型
 */
class DataTypeEnum extends BaseEnum {

	// 字符串
	const STRING = 0;

	// 数字
	const INT = 1;

	// 浮点数
	const FLOAT = 2;

	// 数组
	const ARRAY = 3;

	// 布尔
	const BOOL = 4;

	// 日期
	const DATE = 5;

	// 时间
	const TIME = 6;

	public static function getMap(): array
	{
		return [
			self::STRING => '字符串',
			self::INT 	 => '数字',
			self::FLOAT  => '浮点数',
			self::ARRAY  => '字符串',
			self::BOOL   => '布尔值',
			self::DATE   => '日期',
			self::TIME   => '时间',
		];
	}
}