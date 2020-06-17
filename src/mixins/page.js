/**
 * 封装分页跳转加载数据相关操作, 需先引入common
 * 注: 将此独立出来主要是考虑到可能会在详情页使用, 以及页面可能会有多个分页, 暂将每个分页用场景表示
 */

import { post } from '@/libs/api';
import Chain from '@/libs/Chain';

export default {
	data(){
		return {

			/**
			 * 是否使用场景
			 * 在页面有多个分页的情况下, 此值应为true
			 */
			use_scene: false,

			// 当前场景, 当 use_scene 为 true 时才会用到
			current_scene: 'default',

			// 表格数据, 通过程序映射后存至此处
			results: [],

			/**
			 * 当 use_scene 为 true 时, 应给每个分页各设置一个场景名, 第一个值为默认场景
			 * 进行各种请求操作应先切换场景, 切换方法此处设两种,
			 * 1. 在调用方法的html元素上设置属性 scene="场景名"
			 * 2. 调用方法 , 切换默认场景
			 */
			scenes: [],

			/**
			 * 如果有多个场景, 则用 'page_' + 场景名 作为键, 复制下面的配置
			 */
			page_default: {

				// 页面请求地址
				url: '',

				// 表格数据存储映射
				mapping: 'results',

				// 表格数据, 如果 mapping 不为空, 数据将不存于此处, 而是外层映射对应名称的变量
				results: [],

				// 当前分页
				current: 1,

				// 最大页码
				maxpage: 1,

				// 每页显示消息数
				num: 0,

				// 总记录数
				total: 0,

				// 默认参数
				params: {},

				// 请求参数
				args: {},
			}
		}
	},
	methods: {

		/**
		 * 变更场景
		 *
		 * @param {string} name 场景名称
		 */
		changeScene(name){
			this.current_scene = name;
		},

		/**
		 * 添加场景, 当添加多个场景时会开启多场景模式
		 * (除默认场景外, 添加其他场景均会开启多场景模式)
		 * 除默认场景外, 添加其他场景前需在调用该mixin的组件中添加属性 page_场景名
		 *
		 * @param {string} url 		请求地址
		 * @param {string} mapping  存储返回列表数据的根级响应式属性
		 * @param {string} scene    场景名称
		 * @param {object} params   请求时附带的默认参数
		 */
		addScene(url, scene = 'default', { mapping = '', params = {} } = {}){
			if(scene == 'default'){
				this.page_default.url = url;

				if(mapping != ''){
					this.page_default.mapping = mapping;
					this.page_default.params = params;
				}
			}else{
				let key = `page_${scene}`;
				this.use_scene = true;

				// 添加场景
				if(this.scenes.indexOf(scene) == -1){
					this.scenes.push(scene);
					this[key]= {
						url: url,
						mapping: mapping,
						results: [],
						current: 1,
						maxpage: 1,
						num: 0,
						total: 0,
						params: {},
						args: {},
					};
				}else{
					this[key].url = url;

					if(mapping != ''){
						this[key].mapping = mapping;
					}
				}
			}
		},

		/**
		 * 重置请求
	 	 */
		requestReset(params){
			let {
				scene = '',
				reset = false,
			} = params;

			let scene_name = `page_${scene}`;
			let target = this[scene_name];

			// 重置各种数据
			if(reset){
				target.args = {};
				target.maxpage = 1;
				target.current = 1;

				if(target.mapping != ''){
					target.results.splice(0);
				}else{
					this[target.mapping].splice(0);
				}
			}

			return params;
		},

		/**
		 * 获取分页数据
		 */
		requestParams(params){
			let {
				scene = 'default',
			} = params;

			let scene_name = `page_${scene}`;

			params.original = {...this[scene_name]};

			params.url = params.original.url;
			params.args = params.original.args;

			return params;
		},

		/**
		 * 检查数据
		 */
		requestCheck(params){
			if(! params.hasOwnProperty('original') || ! params.original.hasOwnProperty('maxpage')){
				return Promise.reject(new Error('请求数据有误'));
			}

			if(params.page > 1 && params.page > params.original.maxpage){
				return Promise.reject(new Error('请求页面超过最大页码'));
			}

			return params;
		},

		/**
		 * 提交请求数据
		 */
		requestPost(params){
			let url = params.original.url;
			let args = params.original.url;

			return post(url, args, false).then(res => {
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					let result = res.result;

					return result;
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					if(retry){
						params.retry = false;
						return this.step3(params);
					}else{
						return Promise.reject(new Error(res.msg));
					}
				}
				else{
					return Promise.reject(new Error('服务器未响应，请稍后重试'));
				}
			}).catch(e => {
				return Promise.reject(e);
			});
		},

		/**
		 * 拼接请求链
		 */
		requestChain(params = {}){
			let instance = new Chain;

			// 切换场景
			if(! params.hasOwnProperty('scene')){
				params.scene = 'default';
			}

			// 重置数据
			if(params.hasOwnProperty('reset') && params.reset){
				instance.add('reset', this.requestReset);
			}

			// 获取分页数据
			instance.add('requestParams', this.requestParams);

			// 获取其他参数
			instance.add('requestCheck', this.requestCheck);

			// 发送请求
			instance.add('requestPost', this.requestPost);

			return instance.commit(params).then(result => {
				this.current_scene = params.scene;

				return result;
			}).catch(e => {
				return Promise.reject(e);
			});
		},

		/**
		 * 获取分页数据
		 * 如果需要重写, 只需要在引入该mixin的组件内使用相同名字的方法即可
		 *
		 * @param {string} page   分页页码
		 * @param {string} scene  分页场景
		 * @param {object} args   请求数据
		 * @param {bool}   reset  是否重置请求, 与 retry reload 不可同时使用
		 * @param {bool}   retry  是否在请求失败之后, 重新请求首页数据
		 *
		 */
		getRequestData(params){
			this.loading(true);

			this.requestChain(params).then(result => {
				let target = null;
				let results = result.data;

				if(!results || results.length < 1){
					results = [];
				}

				if(! this.use_scene){
					target = this.page_default;
				}else{
					target = this[`page_${this.current_scene}`];
				}
	
				// 保存返回的数据, 此处可考虑映射
				if(target.mapping != ''){
					this[target.mapping] = results;
				}else{
					target.results = results;
				}
	
				// target.args = args;
				target.current = args.page;
				target.num = result.num * 1;
				target.total = result.total * 1;
				target.maxpage = Math.ceil(result.total / result.num);
			}).catch(e => {
				if(e){
					let msg = e.message || '网络异常, 请稍后重试';
					this.$message(msg, 'warning');
				}
			}).finally(()=>{
				this.loading(false);
			});
		},

		/**
		 * 重新请求数据(刷新当前分页)
		 *
		 * @param {bool}   retry 是否在请求失败之后, 重新请求首页数据
		 */
		reRequestData(retry = false){
			this.getRequestData({
				reload: true,
				retry,
			});
		},

		// 每页加载数改变
		sizeChange(size){
			size = +size || 1;
			let param = this.getRequestParam(false);
			param.args.num = size;

			this.getRequestData({
				page: 1,
				args: param.args,
				reset: true
			});
		},

		// 分页点击切换页码
		pageChange(page){
			page = +page || 1;

			this.getRequestData({
				page
			});
		},
	}
}