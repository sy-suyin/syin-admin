<template>
	<div>
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">一般列表</h2>
				</div>
			</template>
		</page-header>

		<div class="content-container">
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
					</div>
				</div>

				<el-table
					ref="multipleTable"
					:data="tableData"
					tooltip-effect="dark"
					style="width: 100%"
				>
					<el-table-column type="selection" width="46" align="center"></el-table-column>

					<el-table-column prop="id" label="编号" width="60"></el-table-column>

					<el-table-column prop="name" label="项目名称" width="200"></el-table-column>

					<el-table-column label="状态" width="120">
						<template slot-scope="scope">

							<el-tag type="success" effect="dark" size="mini" @click="disabled" v-if="scope.row.is_disabled < 1">启用</el-tag>
							<el-tag type="danger" effect="dark" size="mini" @click="disabled" v-else>禁用</el-tag>

						</template>
					</el-table-column>

					<el-table-column prop="add_time" label="添加时间" width="120"></el-table-column>

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
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/components/mixins/common.js";

export default {
	name: "list_basic",
	mixins: [commonMixin],
  	data() {
      	return {
			search: {
				keyword: '',
			},
			tableData: [{
				id: 1,
				name: '测试项目 0-01',
				is_disabled: 0,
				add_time: '2016-05-03',
			}, {
				id: 2,
				name: '测试项目 0-02',
				is_disabled: 0,
				add_time: '2016-05-02',
			}, {
				id: 3,
				name: '测试项目 0-03',
				is_disabled: 1,
				add_time: '2016-05-04',
			}, {
				id: 4,
				name: '测试项目 0-04',
				is_disabled: 0,
				add_time: '2016-05-01'
			}, {
				id: 5,
				name: '测试项目 0-05',
				is_disabled: 1,
				add_time: '2016-05-08'
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

		disabled(){

		}
    }
};
</script>

<style lang="scss">
@import "../../assets/style/table-base.scss";
</style>