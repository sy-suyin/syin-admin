// 工具类
import store from '@/vuex/store'

/**
 * 判断数据类型
 * 
 * @param mixed 需要进行判断的数据
 * 
 */
export function getType(data) {
	let type = Object.prototype.toString.call(data).slice(8, -1).toLowerCase()
	return type;
}

/**
 * 判断是否有访问权限
 * 
 * @param string controller 请求的控制
 * @param string action		请求的方法
 * @param string type		page/data 权限类型，默认为data 
 */
export function checkPermission(controller, action, type='data'){
	let user = store.getters['auth/user'];
	let forbid_list = store.getters[`access/${type}_forbid`];

	if(!user){
		return false;
	}

	if(!!user.is_admin){
		return true;
	}

	if(isEmpty(forbid_list)){
		return true;
	}

	if(! isSet(forbid_list, controller)){
		return true;
	}

	if(-1 == forbid_list[controller].findIndex(val=>{return val==action;})){
		return true;
	}

	return false;
}

/**
 * 判断数据是否为空
 * 
 * @param mixed 需要进行判断的数据
 * 
 */
export function isEmpty(data) {
	let type = getType(data);

	if(!data){
		return true;
	}

	let bool = false;
	switch(type){
		case 'string': {
			bool = data.length < 1;
		}
		case 'array': {
			bool = data.length < 1;
		}
		case 'object': {
			bool = Object.keys(data).length < 1;
		}
	}

	return bool;
}

/**
 * 字符串渲染方法
 */
export function strReplace() {

}

/**
 * 判断对象中是否有某个方法
 */
export function isSet(obj, key) {
	if(!obj){
		return false;
	}

	return obj.hasOwnProperty(key);
}

/**
 * 获取当前时间戳
 */
export function now() {
	let time = ~~+(Date.now()/1000)
	return time;
}

/**
 * 批量执行多个请求, 同一返回, 只有当多个请求都成功时才能成功
 * @description 已查过axios源码, 故此处直接使用axios封装的代码
 * 
 * @param {*} 	promises 多个 Promise 实例
 */
export function requestAll(promises){
	return Promise.all(promises).then((res)=>{
		return res;
	});
}

/**
 * 调试方法 - 断言 by vuex
 * @param {bool} 	condition 调试语句, 执行后返回的结果
 * @param {string}	msg		  断言不成功时的提示消息
 * 
 */
export function assert(condition, msg) {
	if (!condition) {
		throw new Error(("[assert] " + msg)) 
	}
}

/**
 * 输出输入时间距离当前时间友好的时间格式
 * 
 * @param string timestamp 时间戳(10位数字或)
 * @use support '2020-03-20 12:00:21' and '1584676821'
 * 
 */
export function timeAgo (timestamp) {
	timestamp = +timestamp ? +timestamp : new Date(timestamp).getTime()/1000;
	let current = now();
	let interval = 0;
	let text = '';

	if(!timestamp){
		return false;
	}

	interval = current - timestamp;

	if(interval < 1){
		return false;
	}

	// 注: 此处为方便计算, 一个月按30.417天计算, 故周也按此计算
	if(interval < 3600){
		text =  ~~(compare / 60) + '分钟前';
	}else if(interval < 3600 * 24){
		text =  ~~(compare / (60 * 24)) + '小时前';
	}else if(interval < 3600 * 24 * 7){
		text =  ~~(compare / (60 * 24 * 7)) + '天前';
	}else if(interval < 3600 * 24 * 30.417){
		text =  ~~(compare / (60 * 24 * 7)) + '周前';
	}else{
		text =  ~~(compare / (60 * 24 * 30.417)) + '月前';
	}

	return text;
}


/**
 * 时间戳转换为时间
 * @link https://qishaoxuan.github.io/js_tricks/date/
 *
 * @param string timestamp 时间戳
 * @param bool   isMs	   isMs为时间戳是否为毫秒
 *
 */
export function timestampToTime(timestamp = Date.parse(new Date()), isMs = false) {
	const date = new Date(timestamp * (isMs ? 1 : 1000));
	if(date == 'Invalid Date'){
		return false;
	}
	return `${date.getFullYear()}-${date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
}

/**
 * 将驼峰字符串转成-间隔且全小写的Dash模式
 * @link https://github.com/saqqdy/common/blob/master/src/modules/camel2Dash.js
 * 
 * @param string str 需要转换的字符串
 * 
 */
export function camel2Dash(str) {
	return str
		.replace(/([A-Z]{1,1})/g, '-$1')
		.replace(/^-/, '')
		.toLocaleLowerCase()
}

/**
 * 将-间隔且全小写的Dash模式转成驼峰字符串
 * 
 * @param string str 需要转换的字符串
 */
export function dash2Camel(str){
	return str.replace(/[\-]{1,1}([a-z]{1,1})/g, function () {
		return arguments[1].toLocaleUpperCase()
	})
}

/**
 * 设为首页
 * 
 * @param string url 访问链接
 */
export function setHomepage(url) {
	if (document.all) {
		document.body.style.behavior = 'url(#default#homepage)';
		document.body.setHomePage(homeurl);
	} else if (window.sidebar) {

		if (window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
			} catch (e) {
				return false;
				// alert('该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入about:config,然后将项 signed.applets.codebase_principal_support 值该为true');
			}
		}
	}
	return true;
}

/**
 * 加入收藏夹
 * 
 * @param string url   网站链接
 * @param string title 收藏标题
 * 
 * @return bool
 */
export function addFavorite(url, title) {
	try {
		window.external.addFavorite(url, title);
	} catch (e) {
		try {
			window.sidebar.addPanel(title, url, '');
		} catch (e) {
			// alert('加入收藏失败，请使用Ctrl+D进行添加');
			return false;
		}

		return true;
	}

	return true;
}

/** 
 * 防抖函数
 * @link [参考] https://www.cnblogs.com/TigerZhang-home/p/11812386.html
 * 在事件被触发n秒后再执行回调，如果在这n秒内又被触发，则重新计时
 * 使用方法, 在 methdos添加代码:  你的方法名:debounce(延迟时间, function(你需要的参数){你的代码}),
 * 
 * @param int delay   延迟时间
 * @param function fn 函数 
 */
export function debounce(delay, fn){
	// 存储返回句柄
	let handle = null;
	delay = delay || 200;

	return function() {
		let args = arguments;
		// 清除上一次延时器
		handle && clearTimeout(handle);
		handle = setTimeout(() => {
			fn.apply(this, args)
		}, delay);
	}
}

/**
 * 节流函数
 * 规定在一个单位时间内，只能触发一次函数。如果这个单位时间内触发多次函数，只有一次生效。
 * 使用方法, 在 methdos添加代码:  你的方法名:throttle(延迟时间, function(你需要的参数){你的代码}),
 * vue 示例: 	mouseleave: throttle(2000, function(){})
 *
 * @param int delay   延迟时间
 * @param function fn 函数 
 */
export function throttle(delay, fn){
	// 存储返回句柄
	let handle = null;
	let last_time = 0;
	delay = delay || 200;

	return function(){
		// 获取当前时间, 毫秒级
		let current_time = Date.now();
		let args = arguments;

		if(last_time && current_time < last_time + delay){
			// 清除上一次延时器
			handle && clearTimeout(handle);

			handle = setTimeout(()=>{
				last_time = current_time;
				fn.apply(this, args);
			}, delay);
		}else{
			handle && clearTimeout(handle);
			fn.apply(this, args);
			last_time = current_time;
		}
	}
}

/**
 * 获取两个数组的差集
 */
export const difference = (arr1, arr2) => {
	return arr1.filter(v => !arr2.includes(v))
}

/**
 * 获取两个数组的交集
 *
 */
export function intersection (arr1, arr2) {
	return arr2.filter(v => arr1.includes(v))
}

/**
* 过滤器
*/
export function filter (arr, fn) {
	if (!IS.isArray(arr)) {
		throw new Error('The first argument to myFilter method must be an array')
	}
	const len = arr.length
	const res = []
	for (let i = 0; i < len; i++) {
		if (fn(i, arr[i], arr)) {
			res.push(arr[i])
		}
	}
	return res
}

/**
 * 具体使用参考第二个链接
 * 
 * @link https://github.com/tc39/proposal-optional-chaining
 * @link https://www.mmxiaowu.com/article/5b18d19f2f52003e4d38c639
 * @link https://github.com/BiYuqi/js-wheels/blob/master/src/object/object.js
 * 
 * @param array  target 源数据
 * @param string props
 * @param mixed  def 	默认数据
 * chaining(data, 'name.age.go', '加载中...') // 27
 * chaining(data, 'name.g.g.b.c', '加载中...') // 加载中...
 * chaining(data, 'age', '暂无数据') // 38
 * 
 */
export function chaining (target, props, def = '') {
	if (!props || getType(props) != 'string') {
	  	return target
	}
	const spl = props.split('.')
	const returnVal = spl.reduce((prev, curr) => {
	  return prev && prev[curr]
	}, target)
	return returnVal || def
}