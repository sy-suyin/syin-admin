// 工厂类, 生成相关类实例

let Factory = {

	// 存储相关类实例
	instances: {},

	// 获取类实例
	get(Class){
		let class_name = Class.name;

		if(this.instances.hasOwnProperty(class_name)){
			return this.instances[class_name];
		}else{	
			let params = [].slice.call(arguments);
			params.shift();

			let instance = new Class(...params);
			this.instances[class_name] = instance;
			return instance;
		}
	}
}

export default Factory;