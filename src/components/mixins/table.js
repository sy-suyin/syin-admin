/**
 * 封装表格页面相关操作
 */

import * as Util from '@/libs/util.js';

export const table = {
	data(){
		return {
			// 各跳转链接
			isShowing: false,

			urls: {},

			// 搜索参数
			search_args: {
				keyword: '',
			},

			// 筛选参数 
			filter_args: {},

			// 请求数据参数, 取值自 search_args 与 filter_args
			request_args: {},
		}
	},
	mounted() {
	},
	methods: {
		init(){
			// this.$router.push({path: `/system/adminedit/${row.id}`})
		},

		/** 
		 * 获取跳转链接
		 */
		getUrl(key, params={}){
			let url = '';
			if(this.urls.hasOwnProperty(key)){
				url = this.urls[key];
			}

			if(url != '' && !Util.isEmpty(params)){

				url = url.replace(/\/:[a-zA-Z0-0_+]+/g, (str)=>{
					str = str.substring(2);

					if(Util.isSet(params, str)){
						str = ('/'+params[str]).trim();
					}else{
						str = '';
					}

					return str;
				});
			}

			return url;
		},

		/** 
		 * 页面跳转
		 */
		jump(key, params={}){
			let url = this.getUrl(key, params);
			this.$router.push({path: url})
		},

		// 重置参数
		reset(){
			this.search_args = {};
			this.filter_args = {};
			this.request_args = {};
			this.getRequestData(1, {}, true);
		},

		// 删除
		del(id = -1){
			let deleted = 1;
			Factory.get(Table).delete(id, deleted, this.getUrl('del'));
		},

		// 批量删除
		delAll(){
			let deleted = 1;
			Factory.get(Table).delete(-1, deleted, this.getUrl('del'));
		},

		// 筛选
		filter(){
			/**
			 * 1. 获取提交的数据
			 * 2. 判断是否需要弹窗提示
			 * 3. 提交数据
			 * 4. 后续处理
			 * 5. 处理返回数据
			 */

			let values = [];
			this.results.forEach(val => {
				values.push({
					id:  val.id,
					val: val.sort
				});
			});
		},

		// 搜索
		search(){
			this.request_args.keyword = this.search_args.keyword.trim();
			this.getRequestData(1, this.request_args);
		},

		// 禁用
		disabled(row, disabled){
			Factory.get(Table).disabled(row.id, disabled, this.getUrl('dis'));
		},
		
	},
} 