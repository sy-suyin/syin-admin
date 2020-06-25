/**
 * 封装表格页面相关操作
 */

import {isEmpty, isSet, debounce, throttle, timestampToTime} from '@/libs/util';
import Table from '@/libs/Table.js';

export default {
	data(){
		return {

			// 表格相关跳转链接, 在被混入的组件重写
			urls: {},

			// 表格数据
			results: [],

			// 搜索参数
			search_args: {
				keyword: '',
			},

			// 表格操作类实例
			instance: null,

			// 表格选中列的id
			selected: [],
		}
	},
	mounted() {
		// this.instance = new Table(this);
	},
	methods: {

		///////////////////////////////////////////////////////////
		// 表格页面的常用方法
		///////////////////////////////////////////////////////////

		/**
		 * 获取跳转链接
		 */
		buildUrl(key, params = false){
			let url = '';
			if(this.urls.hasOwnProperty(key)){
				url = this.urls[key];
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
			let url = this.buildUrl(key, params);
			this.$router.push({path: url})
		},

		/**
		 * 重置参数
		 */
		reset(){
			this.search_args = {};
			this.getRequestData({reset: true});
		},

		/**
		 * 搜索
		 */
		search(){
			this.request_args.keyword = this.search_args.keyword.trim();
			this.getRequestData({
				args: this.request_args,
				reset: true
			});
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
				this.is_loading = false;
			});
		},

		/**
		 * 恢复/删除数据
		 *
		 * @param {int} id 			需要 恢复/删除 数据的ID. 如果为-1, 则选取表格所有被选中项的id
		 * @param {int} operate		操作标识, 0: 恢复, 1: 删除
		 */
		del({id = -1, operate = 1} = {}){
			let url = this.urls['del'];
			let operate_msg = operate == 1 ? '删除' : '恢复';
			let params = {
				url,
				is_confirm: true,
				mark: {operate}
			};

			if(id == -1){
				params.confirm_msg = `你确认要批量${operate_msg}数据吗?`;
				params.id = this.selected;
			}else{
				params.confirm_msg = `你确认要${operate_msg}数据吗?`;
				params.id = id;
			}

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
		 * @param {int} operate		操作标识, 0: 启用, 1: 禁用
		 */
		disabled(id = -1, operate = 1){
			let url = this.urls['dis'];
			id = id == -1 ? this.selected : [ id ];
			let params = {
				id,
				url,
				mark: {operate}
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
			let url = this.urls['sort'];
			let data = this.extract('id, sort');

			this.execute({
				url,
				data
			}).then(res => {
				this.message('操作成功', 'success');
			});
		},

		/**
		 * 提取表格数据, 默认会获取id
		 *
		 * @param {string}   fields  需要提取数据的字段, 可不传. 当不传数据时返回数据为包含id的一维数组
		 * @param {function} filter  自定义过滤器, 当返回false时, 该项数据将不会加入提取数据中, 传入过滤器的数据格式为 filter( { 字段名: 字段值 } )
		 *
		 * @returns {array}
		 */
		extract(fields = '', filter = false){
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

					if(filter){
						if(false !== filter(data)){
							results.push(data);
						}
					}else{
						results.push(data);
					}
				}
			});

			return results;
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

		///////////////////////////////////////////////////////////
		// 表格常用的过滤器
		///////////////////////////////////////////////////////////

		/**
		 * 时间过滤器
		 *
		 * 应传入10位长度的时间戳
		 */
		filterTime(row, column){
			let time = row[column.property] || '';
			let time_str = '';
			if(time){
				time_str = timestampToTime(time);
			}

			return time_str ? time_str : '';
		}
	},
}