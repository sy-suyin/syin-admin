/**
 * 封装分页跳转加载数据相关操作, 需先引入common
 */

import { isEmpty } from '@/libs/util';
import Chain from '@/libs/Chain';

export default {
	data(){
		return {

			// 当前场景
			current_scene: 'default',

			/**
			 * 场景列表
			 */
			scenes: [],

			/**
			 * 如果有多个场景, 则用 'page_' + 场景名 作为键, 复制下面的配置
			 */
			page_default: null,
		}
	},
	methods: {

		/**
		 * 添加场景, 数据存储在 page_场景名 的根级响应式属性中
		 *
		 * @param {func}   api 		请求接口
		 * @param {string} mapping  存储返回列表数据的根级响应式属性
		 * @param {string} scene    场景名称
		 * @param {object} params   请求时附带的默认参数
		 */
		addScene(api, scene = 'default', { mapping = '', params = {} } = {}){
			let scene_name = `page_${scene}`;

			// 添加场景
			if(this.scenes.indexOf(scene) == -1){
				this.scenes.push(scene);
				this[scene_name]= {
					// 页面请求api
					api: api,
					// 表格数据存储映射
					mapping: mapping,
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
					default: params,
					// 请求参数
					args: params,
					// 请求状态
					status: 'loadmore',
				};
			}else{
				this[scene_name].api = api;
				this.page_default.default = params;

				if(mapping != ''){
					this[scene_name].mapping = mapping;
				}
			}

			// 设置当前分页
			if(this.scenes.length == 1){
				this.current_scene = scene;
			}
		},

		/**
		 * 重置请求
	 	 */
		requestReset(params){
			let {
				scene = false,
				reset = false,
			} = params;

			// 重置各种数据
			if(reset){
				this.setSceneData({
					args: {},
					maxpage: 1,
					current: 1,
					results: []
				}, scene, true);
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

			params.original = this.getSceneData(scene);
			params.api = params.original.api;

			// 组合请求参数
			if( !params.hasOwnProperty('args') || !isEmpty(params.args)){
				params.args = params.original.args;
			}else{
				params.args = { ...params.original.default, ...params.args };
			}

			// 获取加载页面, 如果不传分页数, 默认请求下一页
			if( params.hasOwnProperty('page') ){
				params.args.page = params.page = +params.page;
			}else{
				params.args.page = params.page = params.original.current;
			}

			// 设置每页请求数, 不影响其他参数
			if( params.hasOwnProperty('num') ){
				params.args.num = params.num;
			}

			return params;
		},

		/**
		 * 检查数据
		 */
		requestCheck(params){
			if( !params.hasOwnProperty('original') || ! params.original.hasOwnProperty('maxpage') ){
				this.setSceneStatus(params.scene, 'loadmore');
				return Promise.reject(new Error('请求数据有误'));
			}

			if(params.page > 1 && params.page > params.original.maxpage){
				this.setSceneStatus(params.scene, 'nomore');
				return Promise.reject(new Error('请求页面超过最大页码'));
			}

			return params;
		},

		/**
		 * 提交请求数据
		 */
		requestPost(params){
			let {
				api,
				args
			} = params;

			return api(args).then(res => {
				params.result = res;
				return params;
			}).catch(e => {
				return Promise.reject(e);
			});
		},

		/**
		 * 拼接请求链, 编排执行顺序
		 */
		requestChain(params = {}){
			let instance = new Chain;

			// 切换场景
			if( !params.hasOwnProperty('scene') || !params.scene ){
				params.scene = this.current_scene || this.scenes[0];
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

			this.setSceneStatus(params.scene, 'loading');

			return instance.commit(params).then(params => {
				this.current_scene = params.scene;
				return params;
			});
		},

		/**
		 * 获取分页数据
		 * 如果需要重写, 只需要在引入该mixin的组件内使用相同名字的方法即可
		 *
		 * @param {string} page   分页页码
		 * @param {string} scene  分页场景
		 * @param {object} args   请求数据
		 * @param {bool}   reset  是否重置请求
		 *
		 */
		getRequestData(params){
			this.loading(true);

			return this.requestChain(params).then(params => {
				let result = params.result;
				let results = result.data;
				let maxpage = Math.ceil(result.total / result.num);
				let status 

				if(!results || results.length < 1){
					results = [];
				}

				this.setSceneData({
					results,
					status: maxpage >= params.page ? 'nomore' : 'loadmore',
					args: params.args,
					current: params.page,
					num: result.num,
					total: result.total,
					maxpage,
				}, params.scene);

				return result;
			}).catch(e => {
				if(e){
					let msg = e.message || '网络异常, 请稍后重试';
					this.message(msg, 'warning');
				}

				return Promise.reject(e);
			}).finally(()=>{
				this.loading(false);
			});
		},

		/**
		 * 获取场景数据
		 *
		 * @param {*}      scene 场景
		 */
		getSceneData(scene = false){
			scene = scene || this.current_scene;
			let scene_name = `page_${scene}`;
			let data = this[scene_name];
			return {...data};
		},

		/**
		 * 设置场景数据
		 *
		 * @param {*}      scene 场景
		 * @param {bool}      scene 场景
		 */
		setSceneData(params, scene = false, reset = false){
			scene = scene || this.current_scene;
			let scene_name = `page_${scene}`;
			let old_data = this.getSceneData(scene);

			if(reset){
				params.args = {...old_data.default};
			}else{
				// 保存表格数据
				if(old_data.mapping && params.hasOwnProperty('results') ){
					this[old_data.mapping] = params.results;
					delete params.results;
				}

			}

			this[scene_name] = { ...old_data, ...params };
		},

		/**
		 * 设置场景请求状态
		 * 
		 * @param {*}      scene  场景
		 * @param {string} status 状态:loadmore, loading, nomore
		 * 
		 */
		setSceneStatus(scene, status){
			let scene_name = `page_${scene}`;
			this[scene_name].status = status;
		},

		/**
		 * 重新请求数据(刷新当前分页)
		 *
		 * @param {*}      scene 场景
		 */
		reRequestData(scene = false){
			this.getRequestData({
				scene
			});
		},

		/**
		 * 每页加载数改变
		 * 
		 * @param {*} size 		每页显示消息数
		 * @param {*} scene 	场景
		 */
		sizeChange(size, scene = false){
			size = +size || 10;
			scene = scene || this.scenes[0];

			this.getRequestData({
				page: 1,
				num: size,
				scene
			});
		},

		/**
		 * 分页点击切换页码
		 * 
		 * @param {*} page 	 新页码
		 * @param {*} scene  分页场景
		 */
		pageChange(page, scene = false){
			page = +page || 1;
			scene = scene || this.scenes[0];

			this.getRequestData({
				page,
				scene
			});
		},

		/**
		 * 请求下一页
		 */
		nextPage(){
			let params = this.getSceneData();

			if(params.current >= params.maxpage){
				return;
			}

			this.getRequestData({
				page: params.current + 1
			});
		}
	}
}