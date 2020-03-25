/**
 * 封装分页跳转加载数据相关操作, 需先引入common
 * 注: 单独封装, 主要是考虑后续实际项目可能会需要在详情页进行分页展示数据
 */

import * as Util from '@/libs/util.js';

export const page = {
	data(){
		return {

			// 表格数据
			results: [],

			// 分页数据
			pagination: {
				current_page: 1,
				page_max: 1,
				page_num: 0,
				total: 0,
				args: {},
			},
		}
	},
	methods: {

		/**
		 * 获取分页数据
		 * 如果需要重写, 只需要在引入该mixin的组件内使用相同名字的方法即可
		 * 
		 * @param {string} page  分页页码
		 * @param {object} args  请求参数
		 * @param {bool}   reset 是否重置请求
		 * 
		 */
		getRequestData(page=1, args={}, reset=false){
			if(reset){
				this.pagination.args = {};
				this.pagination.page_max = 1;
			}

			args = args || {...this.pagination.args};
			if(+page < 1){
				page = 1;
			}

			if(page > this.pagination.page_max){
				if(page == 1){
					return this.message('暂无数据! ', 'warning');
				}else{
					return this.message('请求页面超过最大页码! ', 'warning');
				}
			}

			this.pagination.args = args;
			args['page'] = page;
			this.loading(true);

			Util.post('/system/adminlist', args).then(res => {
				this.loading(false);

				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					let result = res.result;
					let results = result.results;

					if(!results || results.length < 1){
						results = [];
					}

					// 保存返回的数据
					this.saveResults(results);
					this.pagination = {
						current_page: result.current_page * 1,
						page_max: result.page_max,
						page_num: result.page_num,
						total: result.total,
					};
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.$message(res.msg, 'warning');
				}
				else{
					this.message('服务器未响应，请稍后重试', 'warning');
				}
			}).catch(err => {
				this.loading(false);
				this.$message('网络异常, 请稍后重试', 'warning');
			}); 
		},

		/**
		 * 当分页数据保存成功时, 使用该方法把后端返回的数据写入组件
		 * 如果不想仅使用 results 存储返回数据, 可在组件中重写该方法
		 * 
		 * @param {*} results 
		 */
		saveResults(results){
			this.results = results;
		},

		// 分页点击切换页码
		pageSwitch(page){
			page = +page || 1;

			this.getRequestData(page);
		},
	}
}