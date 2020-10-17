// 数据表格默认配置

module.exports = {
	// 列表配置
	list: {
		actionbar: [
			{
				type: 'url',
				name: '修改',
				access: 'edit',
			},
			{
				type: 'btn',
				name: '删除',
				access: 'del',
				params: {
					operate: 1,
				}
			},
		],
		toolbar: [
			{
				type: 'btn',
				access: 'reload',
				icon: "el-icon-refresh-left",
				color: 'primary',
			},
			{
				type: 'url',
				name: '添加',
				icon: "el-icon-plus",
				color: 'primary',
				access: 'add',
			},
			{
				type: 'btn',
				name: '排序',
				icon: "el-icon-sort",
				color: 'success',
				access: 'sort',
			},
			{
				type: 'url',
				name: '回收站',
				color: 'warning',
				icon: "el-icon-s-promotion",
				access: 'recycle',
			},
			{
				type: 'btn',
				name: '删除',
				color: 'danger',
				icon: "el-icon-delete",
				access: 'del',
				params: {
					operate: 1,
				}
			},
		],
	},
	// 回收站配置
	recycle: {
		actionbar: [
			{
				type: 'btn',
				name: '还原',
				access: 'del',
				params: {
					operate: 0,
				}
			},
		],
		toolbar: [
			{
				type: 'btn',
				access: 'reload',
				icon: 'el-icon-refresh-left',
				color: 'primary',
			},
			{
				type: 'url',
				name: '列表',
				color: 'primary',
				icon: 'el-icon-s-promotion',
				access: 'list',
			},
			{
				type: 'btn',
				name: '还原',
				access: 'del',
				color: 'success',
				icon: 'el-icon-delete',
				params: {
					operate: 0,
				}
			},
		]
	}
};