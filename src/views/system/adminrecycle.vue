<template>
	<lyaout class="table-base">
		<template #breadcrumb-after>
			<div>
				<h2 class="page-title">管理员回收站</h2>
			</div>
		</template>

		<el-card>
			<div slot="header" class="clearfix">
				<div class="table-search">
					<el-input
						placeholder="请输入搜索内容"
						v-model="search_args.keyword"
						size="mini"
					>
						<i slot="suffix" class="el-input__icon el-icon-search search-btn" @click="search"></i>
					</el-input>
				</div>

				<div class="table-toolbar">
					<el-button size="mini" type="primary" icon="el-icon-s-promotion" @click="list">列表</el-button>
					<el-button size="mini" type="success" icon="el-icon-delete" @click="restoreAll">还原</el-button>
				</div>
			</div>

			<el-table
				ref="table"
				:data="results"
				tooltip-effect="dark"
				style="width: 100%"
				@selection-change="selectionChange"
			>
				<el-table-column type="selection" width="46" align="center"></el-table-column>

				<el-table-column prop="id" label="编号" width="60"></el-table-column>

				<el-table-column prop="name" label="名称" width="200"></el-table-column>


				<el-table-column prop="add_time" label="添加时间" width="180"></el-table-column>

				<el-table-column align="right" label="操作">
					<template slot-scope="scope">
						<el-button size="mini" type="text" @click="restore(scope.$index, scope.row)">恢复</el-button>
					</template>
				</el-table-column>
			</el-table>

			<div id="pagination">
				<el-pagination
					@size-change="pageSwitch"
					@current-change="pageSwitch"
					:current-page="pagination.current_page"
					:page-sizes="[10, 20, 30, 50]"
					:page-size="pagination.page_num"
					layout="total, sizes, prev, pager, next, jumper"
					:hide-on-single-page="true"
					:total="pagination.total">
				</el-pagination>
			</div>
		</el-card>
	</lyaout>
</template>

<script>
import Lyaout from "@/components/layout/base-layout.vue";
import Table from '@/libs/Table.js';
import Factory from '@/libs/Factory.js';
import * as util from '@/libs/util.js';import { Loading } from 'element-ui';

export default {
	name: "system_rolelist",
	components: {
		Lyaout
	},
  	data() {
      	return {
			// 搜索参数
			search_args: {
				keyword: '',
			},

			// 筛选参数 
			filter_args: {},

			// 请求数据参数, 取值自 search_args 与 filter_args
			request_args: {},

			// 分页数据
			pagination: {
				current_page: 1,
				page_max: 1,
				page_num: 0,
				total: 0,
			},

			// 表格数据
			results: [],
		}
	},
	mounted(){
		this.getRequestData();

		Factory.get(Table, this);
	},
	methods: {

		// 列表
		list(){
			this.$router.push({path: '/system/adminlist'})
		},

		// 还原
		restore(index, row){
			let deleted = 0;
			Factory.get(Table).delete(row.id, deleted, '/system/admindel');
		},

		// 批量还原
		restoreAll(){
			let deleted = 0;
			Factory.get(Table).delete(-1, deleted, '/system/admindel');
		},

		// 搜索
		search(){
			this.request_args.keyword = this.search_args.keyword;
			this.getRequestData(1);
		},

		// 重置参数
		reset(){
			this.search_args = {};
			this.filter_args = {};
			this.request_args = {};
			this.getRequestData(1);
		},

		// 设置加载中
		loading(is_open = true){
			if(is_open){
				Loading.service({ fullscreen: true });
			}else{
				Loading.service({ fullscreen: true }).close();
			}
		},

		// 获取页面数据
		getRequestData(page=1){
			let args = {...this.request_args};

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

			args['page'] = page;

			this.loading(true);
			util.post('/system/adminrecycle', args).then(res => {
				this.loading(false);

				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					let result = res.result;
					let results = result.results;

					if(!results || results.length < 1){
						results = [];
					}

					this.results = results;
					this.pagination = {
						current_page: result.current_page * 1,
						page_max: result.page_max,
						page_num: result.page_num,
						total: result.total,
					};
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.$message({
						showClose: true,
						message: res.msg,
						type: 'warning'
					});
				}
				else{
					this.$message({
						showClose: true,
						message: '服务器未响应，请稍后重试',
						type: 'warning'
					});
				}
			}).catch(err => {
				this.loading(false);

				this.$message({
					showClose: true,
					message: '网络异常, 请稍后重试',
					type: 'warning'
				});
			}); 
		},

		// 分页点击切换页码
		pageSwitch(page){
			page = +page || 1;

			this.getRequestData(page);
		},

		message(message, type='warning'){
			this.$message({
				showClose: true,
				message: message,
				type: 'warning'
			});
		},

		// 选择框改变
		selectionChange(selected){
			let ids = [];
			selected.forEach(val => {
				ids.push(val.id);
			});

			Factory.get(Table).setIds(ids);
		}
    }
};
</script>

<style lang="scss">
@import "@/assets/style/table-base.scss";

.role-tag{
	margin-right: 4px;
}
</style>