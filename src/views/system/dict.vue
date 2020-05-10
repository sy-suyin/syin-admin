<template>
	<div class="content-container" v-loading="is_loading">
		<!-- 数据字典 主要用于测试 pageMixin 多分页效果 -->
		<el-row>
			<el-col :span="8">
				<el-card class="box-card">
					<div slot="header" class="clearfix">
						字典目录
					</div>

					<el-table
						ref="table"
						:data="results"
						border
						tooltip-effect="dark"
						style="width: 100%"
						:show-header="false"
						@selection-change="selectionChange"
						@cell-click="show"
						class="dict-table"
					>
						<el-table-column label="字典" @row-click="show(scope.row.id)">
							<template slot-scope="scope" @row-click="show(scope.row.id)">
								<span class="dir-name">
									{{scope.row.name}}
								</span>
								<span class="dir-key">
									{{scope.row.key}}
								</span>
							</template>
						</el-table-column>

						<el-table-column align="right" label="操作" width="80">
							<template slot-scope="scope">
								<el-button
									size="mini"
									@click.stop.prevent="add(scope.row.id)"
									v-permission:page="['dict', 'dataadd']"
								>添加</el-button>
							</template>
						</el-table-column>
					</el-table>

					<div class="pagination">
						<el-pagination
							@size-change="defaultPage('sizeChange', $event)"
							@current-change="defaultPage('pageChange', $event)"
							:current-page="page_default.current"
							:page-sizes="[5, 10, 20, 30, 50]"
							:page-size="page_default.page_num"
							layout="total, sizes, prev, pager, next"
							:total="page_default.total">
						</el-pagination>
					</div>
				</el-card>
			</el-col>

			<el-col :span="16">
				<el-card class="box-card">
					<div slot="header" class="clearfix">
						字典数据
					</div>

					<el-table
						ref="table"
						border
						:data="page_data.results"
						tooltip-effect="dark"
						style="width: 100%"
						@selection-change="selectionChange"
					>
						<el-table-column prop="data" label="数据名称"></el-table-column>
						<el-table-column align="center" label="是否内置" width="80">
							<template slot-scope="scope">
								{{scope.row.is_system ? '是' : '否'}}
							</template>
						</el-table-column>
						<el-table-column prop="description" label="备注说明"></el-table-column>

						<el-table-column align="right" label="操作" width="150">
							<template slot-scope="scope">
								<el-button
									size="mini"
									@click.stop.prevent="edit(scope.row.id)"
									v-permission:page="['dict', 'dataedit']"
								>修改</el-button>

								<el-button
									size="mini"
									@click.stop.prevent="del(scope.row.id)"
									v-permission:page="['dict', 'datadel']"
								>删除</el-button>
							</template>
						</el-table-column>
					</el-table>

					<div class="pagination">
						<el-pagination
							@size-change="dataPage('sizeChange', $event)"
							@current-change="dataPage('pageChange', $event)"
							:current-page="page_data.current"
							:page-sizes="[5, 10, 20, 30, 50]"
							:page-size="page_data.page_num"
							layout="total, sizes, prev, pager, next, jumper"
							:hide-on-single-page="true"
							:total="page_data.total">
						</el-pagination>
					</div>
				</el-card>
			</el-col>
		</el-row>
	</div>
</template>

<script>
import {page as pageMixin} from "@/mixins/page.js";
import {table as tableMixin} from "@/mixins/table.js";
import {common as commonMixin} from "@/mixins/common.js";
import * as Util from '@/libs/util.js';
import {sence} from '@/libs/page.js';

export default {
	name: "system_adminlist",
	mixins: [commonMixin, pageMixin, tableMixin],
  	data() {
		return {
			page_data: null
		}
	},
	created(){
		this.addScene('/dict/list');
		this.addScene('/dict/dictdata', '', 'data');
	},
	mounted(){
		this.getRequestData();
	},
	methods: {
		show(row){
			let args = {id: row.id};
			this.changeScene('data');
			this.getRequestData({
				args,
				reset: true
			});
		},

		defaultPage: sence('default'),
		dataPage: sence('data'),

		// 目前暂不考虑实现这三个按钮的前后端交互效果
		add(id){
		},

		edit(id){
		},

		del(id){
		},
	}
}
</script>

<style lang="scss">
.box-card{
	margin: 10px;
}

.table td, .table th {
    border-top: none;
    border-bottom: 1px solid #dee2e6;
}


.dict-table{
	td{
		border-right: none;
	}

	.dir-key{
		font-size: 10px;
		color: #909399;
		margin-left: 4px;
	}
}

.pagination{
	margin-top: 10px;
}
</style>