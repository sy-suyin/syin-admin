/**
 * 封装表格页面相关操作
 */

import {isEmpty, isSet, debounce} from '@/libs/util';
import Table from '@/libs/Table';
import dbTable from '@/components/db-table';
import pageMixin from '@/mixins/page';
import commonMixin from '@/mixins/common';

export default {
	components: { dbTable },
	mixins: [commonMixin, pageMixin],
	data(){
		return {
			config: {
				// 表格相关跳转链接, 在被混入的组件重写
				urls: {},
	
				pages: {},
			},

			// 表格数据
			results: [],

			// 搜索参数
			search_args: {},

			// 筛选参数
			filter_args: {},

			// 表格选中列的id
			selected: [],
		}
	},
	methods: {

		///////////////////////////////////////////////////////////
		// 表格页面的常用方法
		///////////////////////////////////////////////////////////

		/**
		 * 获取跳转链接
		 */
		buildUrl(key, type = 'url', params = false){
			let url = '';
			let urls = this.config[type == 'page' ? 'pages' : 'urls'];

			if(urls.hasOwnProperty(key)){
				url = urls[key];
			}

			if(url != '' && !isEmpty(params)){
				url = url.replace(/\/:[a-zA-Z0-0_+]+/g, (str)=>{
					str = str.substring(2);

					if(isSet(params, str)){
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
		jump(key, params = false){
			let url = this.buildUrl(key, 'page', params);
			this.$router.push({path: url})
		},

		/**
		 * 重置参数
		 */
		reset(){
			this.search_args = {};
			this.filter_args = {};
			this.getRequestData({reset: true});
		},

		/**
		 * 重新加载
		 */
		reload(){
			this.getRequestData();
		},

		/**
		 * 搜索
		 */
		search(args){
			this.search_args = args;
			this.getRequestData({
				args: args,
				reset: true
			});
		},

		/**
		 * 筛选
		 */
		filter(args){
			// 筛选时, 应包含搜索的结果
			args = { ...args, ...this.search_args };

			this.filter_args = args;
			this.getRequestData({
				args,
				reset: true
			});
		},

		/**
		 * 事件处理中转
		 *
		 * @param {*} func 方法名
		 * @param  {...any} params 传给方法的参数
		 */
		handle(func, ...params){
			this[func] && this[func].apply(this, params);
		},

		///////////////////////////////////////////////////////////
		// 封装表格的基础操作功能
		///////////////////////////////////////////////////////////

		/**
		 * 执行表格操作
		 *
		 * @param {*} params
		 */
		execute(params){
			this.loading(true);

			return Table.execute(params).then(result => {
				return result;
			}).catch((e)=>{
				if(! e) return;

				let msg = e.message || '服务器异常, 请稍后重试';
				this.message(msg, 'warning');

				return Promise.reject(e);
			}).finally(()=>{
				this.loading(false);
			});
		},

		/**
		 * 恢复/删除数据
		 *
		 * @param {int} id 			需要 恢复/删除 数据的ID. 如果为-1, 则选取表格所有被选中项的id
		 * @param {int} operate		操作标识, 0: 恢复, 1: 删除
		 */
		del({id = -1, operate = -1} = {}){
			let url = this.config.urls['del'];
			let operate_msg = operate == 1 ? '删除' : '恢复';
			let params = {
				url,
				is_confirm: true,
				args: {
					operate
				}
			};

			if(id == -1){
				operate_msg = '批量' + operate_msg;
				id = this.selected;
			}

			params.args.id = id;
			params.confirm_msg = `你确认要${operate_msg}数据吗?`;
			this.execute(params).then(res => {
				this.getRequestData({
					retry: true
				});
			});
		},

		/**
		 * 启用/禁用数据
		 *
		 * @param {int} id 			需要 启用/禁用 数据的ID. 如果为-1, 则选取表格所有被选中项的id
		 * @param {int} is_disabled	操作标识, 0: 启用, 1: 禁用
		 */
		disabled({id = -1, val = 0}){
			let url = this.config.urls['dis'];
			id = id == -1 ? this.selected : [ id ];

			let params = {
				url,
				args: {
					id,
					data: val
				}
			};

			this.execute(params).then(res => {
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
			let url = this.config.urls['sort'];

			this.mulitEdit(url, 'sort');
		},

		/**
		 * 辅助方法 - 批量修改
		 * 此方法对应前端数据表格中edit模板, 后端multi方法
		 */
		mulitEdit(url, field){
			let args = {
				id: [],
				data: [],
			};
			this.results.forEach(item => {
				args.id.push(item.id);
				args.data.push(item[field]);
			});

			return this.execute({
				url,
				args
			}).then(res => {
				this.message('操作成功', 'success');
			});
		},

		/**
		 * 表格复选框改变
		 */
		selectionChange: debounce(500, function(selected){
			let ids = [];
			selected.forEach(val => {
				ids.push(val.id);
			});

			this.selected = ids;
		}),
	},
}