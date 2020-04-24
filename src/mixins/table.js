/**
 * 封装表格页面相关操作
 */

import * as Util from '@/libs/util.js';
import Table from '@/libs/Table.js';

let TableInstance = null; 
export const table = {
	data(){
		return {
			// 各跳转链接
			isShowing: false,

			// 表格相关跳转链接, 在被混入的组件重写
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
		TableInstance = new Table(this);
	},
	methods: {

		///////////////////////////////////////////////////////////
		// 表格页面的常用方法
		///////////////////////////////////////////////////////////

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
			this.getRequestData(1, {}, {reset: true});
		},

		// 搜索
		search(){
			this.request_args.keyword = this.search_args.keyword.trim();
			this.getRequestData(1, this.request_args);
		},

		// 筛选
		filter(){
		},

		///////////////////////////////////////////////////////////
		// 封装表格的基础操作功能
		///////////////////////////////////////////////////////////

		/**
		 * 恢复/删除数据
		 * 
		 * @param {int} id 			需要 恢复/删除 数据的ID. 如果为-1, 则选取表格所有被选中项的id
		 * @param {int} operate		操作标识, 0: 恢复, 1: 删除
		 */
		del(id = -1, operate = 1){
			let url = this.getUrl('del');
			let msg = '你确认要' + 
				(id == -1 ? '批量' : '') + ['恢复', '删除'][operate] +
				'数据吗?';

			TableInstance.tip(msg, {
				url,
				data: id,
				mark: {operate}
			}).then(res => {
				// 重新加载数据，如果没有该请求方法，则应在相应页面实现或者替换成对应的数据加载方法
				// 此处采用的的是重新加载页面数据, 当获取当前页面数据失败时, 则重新加载数据
				this.getRequestData(1, {}, {reset: true});
			}).catch((e)=>{});
		},

		/**
		 * 启用/禁用数据
		 * 
		 * @param {int} id 			需要 启用/禁用 数据的ID. 如果为-1, 则选取表格所有被选中项的id
		 * @param {int} operate		操作标识, 0: 启用, 1: 禁用
		 */
		disabled(id = -1, operate = 1){
			let url = this.getUrl('dis');
			TableInstance.execute(url, id, {operate}).then(res => {
				id = (id == -1 ? res.resquset.id : [res.resquset.id]);
				this.results.forEach((item, key)=>{
					if(id.indexOf(item.id) != -1){
						this.results[key].is_disabled = operate;
					}
				});
			}).catch((e)=>{});
		},

		/**
		 * 排序, 需表格数据中有sort与id字段
		 */
		sort(){
			let url = this.getUrl('sort');
			let data = this.extract('id, sort');
			TableInstance.execute(url, data).then(res => {
				this.message('操作成功', 'success');
			}).catch((e)=>{});
		},

		/**
		 * 提取表格数据, 默认会获取id
		 *
		 * @param {string}   fields  需要提取数据的字段, 可不传. 当不传数据时返回数据为包含id的一维数组
		 * @param {function} filter  自定义过滤器, 当返回false时, 该项数据将不会加入提取数据中, 传入过滤器的数据格式为 filter( { 字段名: 字段值 } , 索引 )
		 *
		 * @returns {array}
		 */
		extract(fields = '', filter = null){
			fields = fields.split(',');
			let field_len = fields.length;
			let results = [];

			this.results.forEach(item => {
				let id = item.id || 0;

				if(field_len < 1 ){
					id && results.push(id);
				}else{
					// 此处不对id进行判断, 主要为有些时候主键名不一定为id

					let data = {};
					fields.forEach(field => {
						field = field.trim();
						if(item.hasOwnProperty(field)){
							data[field] = item[field];
						}
					});

					results.push(data);
				}
			});

			return results;
		},

		/**
		 * 表格复选框改变
		 */
		selectionChange: Util.debounce(500, function(selected){
			let ids = [];
			selected.forEach(val => {
				ids.push(val.id);
			});

			TableInstance.setIds(ids);
		}),
	},
}