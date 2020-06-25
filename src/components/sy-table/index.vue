<template>
	<div>

		<!-- 筛选 -->
		<el-card class="filter-box">
			<el-form ref="filter_form" :model="filter" label-width="80px">
				<el-row>
					<el-col :xs="12" :sm="8" :xl="6">
						<el-form-item label="筛选输入" prop="name">
							<el-input v-model="filter.name" size="mini"></el-input>
						</el-form-item>
					</el-col>

					<el-col :xs="12" :sm="8" :xl="6">
						<el-form-item label="筛选下拉" prop="region">
							<el-select v-model="filter.region" placeholder="请选择活动区域" size="mini">
								<el-option label="区域一" value="shanghai"></el-option>
								<el-option label="区域二" value="beijing"></el-option>
							</el-select>
						</el-form-item>
					</el-col>

					<el-col :xs="12" :sm="8" :xl="6">
						<el-form-item label="筛选时间" prop="time">
							<el-date-picker
								v-model="filter.time"
								type="daterange"
								range-separator="至"
								start-placeholder="开始日期"
								end-placeholder="结束日期" size="mini"
							>
							</el-date-picker>
						</el-form-item>
					</el-col>

					<el-col :xs="12" :sm="8" :xl="6">
						<el-form-item label="筛选输入" prop="name2">
							<el-input v-model="filter.name2" size="mini"></el-input>
						</el-form-item>
					</el-col>

					<el-col class="filter-toolbar" :xs="{offset: 12,span: 12}" :sm="{offset: 16,span: 8}" :xl="{offset: 18,span: 6}">
						<el-button size="mini" type="primary">查询</el-button>
						<el-button size="mini" type="danger" @click="resetForm('filter_form')">重置</el-button>
					</el-col>
				</el-row>
			</el-form>
		</el-card>

		<!-- 表格区域 -->
		<el-card>

			<!-- 表格顶部工具栏 -->
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
					<el-button size="mini" type="danger" icon="el-icon-delete">删除</el-button>
					<!-- <el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button> -->
				</div>

			</div>

			<!-- 表格内容区域 -->
			<el-table
				ref="multipleTable"
				:data="data"
				tooltip-effect="dark"
				style="width: 100%"
			>

				<template v-for="column in columns">
					<!-- 复选框 -->
					<el-table-column type="selection" :width="column.width || '48'" align="center" v-if="column.prop == 'selection'" :key="column.prop"></el-table-column>

					<!-- 自定义插槽 -->
					<slot v-else-if="column.slot" :name="column.slot"></slot>

					<!-- 标签栏 -->
					<template v-else-if="column.prop == 'label'">
					</template>

					<!-- 内容区域 -->
					<el-table-column
						v-else 
						:prop="column.prop" 
						:label="column.label" 
						:width="column.width" 
						:fixed="column.fixed"
						:formatter="column.formatter || null"
						:key="column.prop"
					>
					</el-table-column>
				</template>

				<!-- 拓展 -->
				<template v-if="slot_append" slot="append">
					<slot name="append"></slot>
				</template>

				<!-- 操作栏 -->
				<el-table-column v-if="operates.length" align="right" label="操作">
					<template slot-scope="scope">
						<span v-for="(item, index) in operates" :key="index">
							<el-divider direction="vertical" v-if="index > 0"></el-divider>

							<el-button
								v-if="item.type == 'btn'"
								size="mini"
								type="text"
								@click="handle(item.target, {id: scope.row.id, ...item.params})"
								v-permission:data="item.permission"
							> {{item.name}} </el-button>

							<el-button
								v-else
								size="mini" type="text"
								@click="handle('jump', item.target, {id: scope.row.id, ...item.params})"
								v-permission:page="item.permission"
							> {{item.name}} </el-button>
						</span>
					</template>
				</el-table-column>
			</el-table>

			<!-- 分页 -->
			<div id="pagination">
				<el-pagination
					@size-change="handle('sizeChange', $event)"
					@current-change="handle('pageChange', $event)"
					:current-page="pagination.current"
					:page-sizes="[10, 20, 30, 50]"
					:page-size="pagination.num"
					layout="total, sizes, prev, pager, next, jumper"
					:total="pagination.total">
				</el-pagination>
			</div>
		</el-card>

	</div>
</template>

<script>
import permission from '@/directive/permission/index'
import {checkPermission} from '@/libs/util';

export default {
	name: 'sy_table',
	directives: { permission },
	props: {
		data: Array,
		columns: Array,
		operates: Array,
		pagination: Object,
	},
	data() {
      	return {
			search: {
				keyword: '',
			},

			slot_append: true,

        	filter: {
          		name: '',
				region: '',
				time: '',
				name2: ''
			},
			tableData: [{
				id: 1,
				name: '王小虎',
				address: '上海市普陀区金沙江路 1518 弄',
				date: '2016-05-03',
			}, {
				id: 2,
				name: '王小虎',
				address: '上海市普陀区金沙江路 1518 弄',
				date: '2016-05-02',
			}, {
				id: 3,
				name: '王小虎',
				address: '上海市普陀区金沙江路 1518 弄',
				date: '2016-05-04',
			}, {
				id: 4,
				name: '王小虎',
				address: '上海市普陀区金沙江路 1518 弄',
				date: '2016-05-01'
			}, {
				id: 5,
				name: '王小虎',
				address: '上海市普陀区金沙江路 1518 弄',
				date: '2016-05-08'
			}],
			multipleSelection: [],

			currentPage: 5,
		}
	},
	mounted(){
		console.log(this.columns);
	},
	methods: {
		onSubmit(formName) {
			this.$refs[formName].validate((valid) => {
				if (valid) {
					alert('submit!');
				} else {
					console.log('error submit!!');
					return false;
				}
			});
		},
		resetForm(formName) {
			this.$refs[formName].resetFields();
		},
		handleSizeChange(val) {
			console.log(`每页 ${val} 条`);
		},
		handleCurrentChange(val) {
			console.log(`当前页: ${val}`);
		},

		resetForm(formName) {
        	this.$refs[formName].resetFields();
		},
		  
		handle(...params){
			console.log(params);
			params.unshift('handle');
			// this.$emit('handle', params);
			
			this.$emit.apply(this, params);
		}
    }
}
</script>

<style lang="scss">
@import "@/assets/style/table-filter.scss";
</style>