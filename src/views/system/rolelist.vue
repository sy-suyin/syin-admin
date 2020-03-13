<template>
	<lyaout class="table-base">
		<template #breadcrumb-after>
			<div>
				<h2 class="page-title">角色列表</h2>
			</div>
		</template>

		<el-card>
			<div slot="header" class="clearfix">
				<div class="table-search">
					<el-input
						placeholder="请输入搜索内容"
						v-model="search.keyword"
						size="mini"
					>
						<i slot="suffix" class="el-input__icon el-icon-search"></i>
					</el-input>
				</div>

				<div class="table-toolbar">
					<el-button size="mini" type="primary" icon="el-icon-plus">添加</el-button>
					<el-button size="mini" type="danger" icon="el-icon-delete" @click="delAll">删除</el-button>
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

				<el-table-column prop="name" label="角色名称" width="200"></el-table-column>

				<el-table-column label="状态" width="120">
					<template slot-scope="scope">

						<el-tag type="success" effect="dark" size="mini" @click="disabled" v-if="scope.row.is_disabled < 1">启用</el-tag>
						<el-tag type="danger" effect="dark" size="mini" @click="disabled" v-else>禁用</el-tag>

					</template>
				</el-table-column>

				<el-table-column prop="add_time" label="添加时间" width="180"></el-table-column>

				<el-table-column align="right" label="操作">
					<template slot-scope="scope">
						<el-button
						size="mini" type="text" 
						@click="edit(scope.$index, scope.row)">修改</el-button>

						<el-divider direction="vertical"></el-divider>

						<el-button size="mini" type="text" @click="del(scope.$index, scope.row)">删除</el-button>
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
import util from '@/libs/util.js';
import { Loading } from 'element-ui';

export default {
	name: "list-basic",
	components: {
		Lyaout
	},
  	data() {
      	return {
			search: {
				keyword: '',
			},
			pagination: {
				current_page: 1,
				page_max: 1,
				page_num: 0,
				total: 0,
			},
			results: [],
			multipleSelection: [],
		}
	},
	mounted(){
		this.getRequestData();

		Factory.get(Table, this);
	},
	methods: {

		// 添加
		add(){

		},

		// 修改
		edit(index, row){
			this.$router.push({path: `/system/roleedit/${row.id}`})
		},

		// 删除
		del(index, row){
			let deleted = 1;
			Factory.get(Table).delete(row.id, deleted, '/system/roledel');
		},

		// 批量删除
		delAll(){
			let deleted = 1;
			Factory.get(Table).delete(-1, deleted, '/system/roledel');
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
			let args = {};

			if(+page < 1){
				page = 1;
			}

			if(page > this.pagination.page_max){
				return this.message('请求页面超过最大页码! ', 'warning');
			}

			args['page'] = page;

			this.loading(true);
			util.post('/system/rolelist', args).then(res => {
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

		disabled(){

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
</style>