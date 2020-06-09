import Storage from '@/libs/Storage.js';

// 加载各种配置
import request from '@/config/reuqest';

const state = {
	configs: {}
}

const getters = {
	configs: state=>state.configs,
}

const mutations = {

	/**
	 * 添加修改配置
	 * 
	 * @param {*} key 	配置键
	 * @param {*} value 配置数据
	 */
	set(state, {key, value}){
		state.configs[key] = value;

		this.commit('config/archive');
	},

	/**
	 * 删除配置
	 * 
	 * @param {*} key 	配置键
	 */
	remove(state, key){
		delete state.configs[key];
	},

	/**
	 * 存储到本地
	 */
	archive(state){
		Storage.set('config', state.configs, false)
	},

	/**
	 * 初始化
	 */
	init(state){
		let configs = Storage.get('config', {
			json: true,
			decrypt: false
		});

		if(configs){
			if(configs){
				state.configs = configs;
			}
		}else{
			// 将基础样式加入配置中

			this.commit('loadConfig', request);
		}
	},

	/**
	 * 读取其他配置文件
	 */
	loadConfig(state, configs){
		if(!request){
			return false;
		}

		for(key of configs){
			state.configs[key] = configs[key];
		}
	}
}

export default {
	namespaced: true,
    state,
    getters,
	mutations
}