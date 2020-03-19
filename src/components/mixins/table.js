import * as Util from '@/libs/util.js';

export const table = {
	data(){
		return {
			// 各跳转链接
			isShowing: false,
			urls: {}
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
				for(let key in params){
					url += '/'+params[key];
				}
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
			this.getRequestData(1);
		},

		// 删除
		del(id = -1){
			let deleted = 1;
			Factory.get(Table).delete(id, deleted, '/system/admindel');
		},

		// 批量删除
		delAll(){
			let deleted = 1;
			Factory.get(Table).delete(-1, deleted, '/system/admindel');
		},

		// 搜索
		search(){
			this.request_args.keyword = this.search_args.keyword;
			this.getRequestData(1);
		},

		// 禁用
		disabled(row, disabled){
			Factory.get(Table).disabled(row.id, disabled, '/system/admindis');
		},
		
	},
} 