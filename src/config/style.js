module.exports = {
	
	// 侧边栏最小化
	sidebar_mini: false,

	// 侧边栏菜单选中项背景可选颜色
	sidebar_filters: [
		'#9368e9',
		'#2ca8ff',
		'#0bf',
		'#18ce0f',
		'#f44336',
		'#e91e63',
	],

	// 侧边栏菜单选中项背景颜色
	sidebar_filters_color: '',

	// 侧边栏背景方案
	sidebar_background_projects: {
		black: {
			name: 'black',
			color: '#000',
			class: 'black',
		},
		white: {
			name: 'white',
			color: 'hsla(0,0%,78%,.2)',
			class: 'white',
		},
		red: {
			name: 'red',
			color: '#f44336',
			class: 'red',
		}
	},

	// 侧边栏背景颜色
	sidebar_background_color: '',

	// 侧边栏背景图片
	sidebar_background_img: '',

	// 侧边栏背景图片
	sidebar_background_imgs: [
		'http://127.0.0.1:8000/static/api/sidebar/bg-1.jpg',
		'http://127.0.0.1:8000/static/api/sidebar/bg-2.jpg',
		'http://127.0.0.1:8000/static/api/sidebar/bg-3.jpg',
		'http://127.0.0.1:8000/static/api/sidebar/bg-4.jpg',
	],

	// 顶部固定
	fixed_header: false,
}
