/**
 * 封装分页跳转加载数据相关操作, 需先引入common
 * 注: 将此独立出来主要是考虑到可能会在详情页使用, 以及页面可能会有多个分页, 暂将每个分页用场景表示
 */

import * as Util from '@/libs/util.js';

export const page = {
	data(){
		return {

			/**
			 * 是否使用场景
			 * 在页面有多个分页的情况下, 此值应为true
			 */
			use_scene: false,

			// 当前场景, 当 use_scene 为 true 时才会用到
			current_scene: '',

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
				page_max: 1,

				// 每页显示消息数
				page_num: 0,

				// 总记录数
				total: 0,

				// 请求参数
				args: {},
			}
		}
	},
	methods: {

		 /**
		  * 设置请求链接
		  *
		  * @param {string} url 	场景数据请求地址
		  * @param {string} sence 	场景名称
		  */
		setRequestUrl(url, sence = 'default'){
			if(! this.use_scene){
				sence = 'default';
			}

			this['page_'+sence].url = url;
		},

		/**
		 * 获取分页请求需要的链接
		 */
		getRequestUrl(){
			if(! this.use_scene){
				return this.page_url;
			}else{
				// 设置默认场景
				if(this.current_scene == ''){
					this.current_scene = this.scenes[0];
				}

				let url = this[`${this.current_scene}_url`];
				return url;
			}
		},

		/**
		 * 获取请求参数
		 *
		 * @param {bool}   reset 是否重置请求
		 */
		getRequestParam(reset){
			let target = null;

			if(! this.use_scene){
				target = this.page_default;
			}else{
				// 设置默认场景
				if(this.current_scene == ''){
					this.current_scene = this.scenes[0];
				}

				target = this[`page_${this.current_scene}`];
			}

			// 重置各种数据
			if(reset){
				target.args = {};
				target.page_max = 1;
			}

			console.log(target);
			console.log(this.current_scene);

			return target;
		},

		/**
		 * 获取分页数据
		 * 如果需要重写, 只需要在引入该mixin的组件内使用相同名字的方法即可
		 *
		 * @param {string} page  分页页码
		 * @param {object} args  请求参数
		 * @param {bool}   reset 是否重置请求
		 * @param {bool}   retry 是否在请求失败之后, 重新请求首页数据
		 *
		 */
		getRequestData(page = 1, args = {}, {reset = false, retry = false} = {}){
			let param = this.getRequestParam(reset);
			let url = param.url;

			args = args || {...param.args};
			if(+page < 1){
				page = 1;
			}

			if(page > param.page_max){
				if(page == 1){
					return this.message('暂无数据! ', 'warning');
				}else{
					return this.message('请求页面超过最大页码! ', 'warning');
				}
			}

			args['page'] = page;

			Util.post(url, args).then(res => {
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					let result = res.result;

					// 保存返回的数据
					this.saveRequestResult(result, args);
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					if(retry){
						this.$message(res.msg, 'warning');
					}else{
						// 重新加载首页数据, 服务器异常等问题不会重新加载
						this.getRequestData(1, args, {reset: 1});
					}
				}
				else{
					this.message('服务器未响应，请稍后重试', 'warning');
				}
			}).catch(err => {
				let msg = (err instanceof Error) ? '网络异常, 请稍后重试' : err;
				this.$message(msg, 'warning');
			});
		},

		/**
		 * 处理分页数据请求成功之后的数据
		 *
		 * @param {object} 	result	后端返回的数据
		 * @param {object} 	args 	提交请求时的参数
		 */
		saveRequestResult(result, args){
			let target = null;

			if(! this.use_scene){
				target = this.page_default;
			}else{
				target = this[`page_${this.current_scene}`];
			}

			let results = result.results;

			if(!results || results.length < 1){
				results = [];
			}
			// 保存返回的数据, 此处可考虑映射
			if(target.mapping != ''){
				this[target.mapping] = results;
			}else{
				target.results = results;
			}

			target.args = args;
			target.current = result.current_page * 1;
			target.page_max = result.page_max * 1;
			target.page_num = result.page_num * 1;
			target.total = result.total * 1;
		},

		// 分页点击切换页码
		pageSwitch(page){
			page = +page || 1;

			this.getRequestData(page);
		},

		 /**
		  * 切换场景
		  * 
		  * @param {string} name 场景名称 
		  */
		sceneSwitch(name){
			this.current_scene = scene;
		}
	}
}