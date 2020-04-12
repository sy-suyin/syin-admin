<template>
	<layout class="table-filter">
		<template #breadcrumb-after>
			<div>
				<h2 class="page-title">筛选列表</h2>
			</div>
		</template>

		<el-card class="filter-box">
			<el-row>
				<el-form ref="filter_form" :model="filter" label-width="80px">
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

				</el-form>
			</el-row>
		</el-card>

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
					<el-button size="mini" type="danger" icon="el-icon-delete">删除</el-button>
					<!-- <el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button> -->
				</div>

			</div>

			<el-table
				ref="multipleTable"
				:data="tableData"
				tooltip-effect="dark"
				style="width: 100%"
			>
				<el-table-column type="selection" width="46" align="center"></el-table-column>

				<el-table-column prop="id" label="编号" width="60">

				</el-table-column>
					<el-table-column label="日期" width="120">
					<template slot-scope="scope">{{ scope.row.date }}</template>
				</el-table-column>

				<el-table-column prop="name" label="姓名" width="120"></el-table-column>

				<el-table-column prop="address" label="地址" show-overflow-tooltip></el-table-column>

				<el-table-column align="right" label="操作">
					<template slot-scope="scope">
						<el-button
						size="mini" type="text" 
						@click="handleEdit(scope.$index, scope.row)">修改</el-button>

						<el-divider direction="vertical"></el-divider>

						<el-button
						size="mini" type="text" 
						@click="handleDelete(scope.$index, scope.row)">删除</el-button>
					</template>
				</el-table-column>
			</el-table>

			<div id="pagination">
				<el-pagination
					@size-change="handleSizeChange"
					@current-change="handleCurrentChange"
					:current-page="currentPage"
					:page-sizes="[10, 20, 30, 50]"
					:page-size="100"
					layout="total, sizes, prev, pager, next, jumper"
					:total="400">
				</el-pagination>
			</div>
		</el-card>
	</layout>
</template>

<script>
// @ is an alias to /src
import Layout from "@/components/layout/base-layout.vue";

export default {
	name: "list-filter",
	components: {
		Layout
	},
  	data() {
      	return {
			search: {
				keyword: '',
			},
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
      	}
    }
};
</script>

<style lang="scss">
@import "../../assets/style/table-filter.scss";
</style>