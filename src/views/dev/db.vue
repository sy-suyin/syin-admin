<template>
	<div class="content-container" v-loading="is_loading">
		<!-- 数据字典 主要用于测试 pageMixin 多分页效果 -->
		<el-row>
			<el-col :span="8">
				<el-card class="box-card">
					<div slot="header" class="clearfix">
						数据表列表

						<button class="btn">添加</button>
					</div>
					<!-- <el-table
						ref="table"
						:data="page_default.results"
						border
						tooltip-effect="dark"
						style="width: 100%"
						:show-header="false"
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
						<table-page 
							:pagination="page_default"
							:config="list_page"
						 	@handle="handle"
						>
						</table-page>
					</div> -->

					<div>
						<div v-for="(table, key) in tables"	:key="key">
							{{table}}
						</div>
					</div>
				</el-card>
			</el-col>
			<el-col :span="16">
				<el-card class="box-card">
					<div slot="header" class="clearfix">
						字典数据
					</div>
					<!-- <el-table
						ref="table"
						border
						:data="page_data.results"
						tooltip-effect="dark"
						style="width: 100%"
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
						<table-page 
							:pagination="page_data"
							:config="data_page"
						 	@handle="handle"
						>
						</table-page>
					</div> -->
				</el-card>
			</el-col>
		</el-row>
	</div>
</template>
<script>
import pageMixin from "@/mixins/page";
import tableMixin from "@/mixins/table";
import commonMixin from "@/mixins/common";
import tablePage from "@/components/table-page";
import api from "@/api/develop";

export default {
	name: "system_adminlist",
	mixins: [commonMixin, pageMixin, tableMixin],
	components: { tablePage },
  	data() {
		return {
			tables: [],
		}
	},
	created(){
		this.init();
	},
	mounted(){
	},
	methods: {
		init(){
			api.getTables().then(res => {
				console.log(res);
				this.tables = res.result;
				console.log(this.tables);
			}).catch(e => {
				console.log(e);
			})
		},
		show(row){
			let args = {id: row.id};
			this.getRequestData({
				args,
				scene: 'data',
				reset: true
			});
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