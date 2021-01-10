<?php

namespace builder\enums;

use common\enums\BaseEnum;

/**
 * 表单元素类型
 */
class ItemTypeEnum extends BaseEnum {

	const STRING = '';
	const TEXT = '';
	const EDITOR = '';
	const NUMBER = '';
	const DATE = '';
	const TIME = '';
	const DATETIME = '';
	const DATETIMERANGE = '';
	const IMAGE = '';
	const IMAGES = '';
	const FILE = '';
	const FILES = '';
	const SELECT = '';
	const SELECTS = '';
	const SWITCH = '';
	const CHECKBOX = '';
	const RADIO = '';
	const JSON = '';
	const CITY = '';
	const SELECTPAGE = '';
	const SELECTPAGES = '';
	const CUSTOM = '';
	
	public static function getMap(): array
	{
		return [
			self::STRING 		=> '字符',
			self::TEXT 			=> '文本',
			self::EDITOR 		=> '编辑器',
			self::NUMBER 		=> '数字',
			self::DATE 			=> '日期',
			self::TIME 			=> '时间',
			self::DATETIME 		=> '日期时间',
			self::DATETIMERANGE => '日期时间区间',
			self::IMAGE 		=> '图片',
			self::IMAGES 		=> '图片(多)',
			self::FILE 			=> '文件',
			self::FILES 		=> '文件(多)',
			self::SELECT 		=> '列表',
			self::SELECTS 		=> '列表(多选)',
			self::SWITCH 		=> '开关',
			self::CHECKBOX 		=> '复选',
			self::RADIO 		=> '单选',
			self::JSON 			=> '数组',
			self::CITY 			=> '城市地区',
			self::SELECTPAGE 	=> '关联表',
			self::SELECTPAGES 	=> '关联表(多选)',
			self::CUSTOM 		=> '自定义',
		];
	}
}