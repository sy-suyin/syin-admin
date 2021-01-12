<?php

namespace builder\enums;

use builder\common\BaseEnum;

/**
 * 表单控件类型
 */
class ItemTypeEnum extends BaseEnum {

	const STRING = 'string';
	const TEXT = 'text';
	const EDITOR = 'editor';
	const NUMBER = 'number';
	const DATE = 'data';
	const TIME = 'time';
	const DATETIME = 'datetime';
	const DATETIMERANGE = 'datetimerange';
	const IMAGE = 'image';
	const IMAGES = 'images';
	const FILE = 'file';
	const FILES = 'files';
	const SELECT = 'select';
	const SELECTS = 'selects';
	const SWITCH = 'switch';
	const CHECKBOX = 'checkbox';
	const RADIO = 'raido';
	const JSON = 'json';
	const CITY = 'city';
	const SELECTPAGE = 'selectpage';
	const SELECTPAGES = 'selectpages';
	const CUSTOM = 'cutsom';
	
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