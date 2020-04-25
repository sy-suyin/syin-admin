// pageMinxins 辅助方法

/** 
 * 切换场景
 * 在被调用时切换分页场景, 以实现根据不同分页模块, 请求不同页面数据
 * 
 * @param {string} 	name  场景名称
 */
export function sence(name){
	return function() {
		let args = Array.from(arguments);
		const func_name = args.shift();

		// 切换场景
		this.sceneSwitch(name);

		// 调用方法
		this[func_name].apply(this, args);
	}
}