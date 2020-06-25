<template>
	<div class="table-base">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">角色列表</h2>
				</div>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
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
						<el-button size="mini" type="primary" icon="el-icon-s-promotion" @click="jump('list')" v-permission:page="['system', 'rolelist']">列表</el-button>
						<el-button size="mini" type="success" icon="el-icon-delete" @click="del(-1, 0)">还原</el-button>
					</div>
				</div>

				<el-table
					ref="page_default.table"
					:data="results"
					tooltip-effect="dark"
					style="width: 100%"
					@selection-change="selectionChange"
				>
					<el-table-column type="selection" width="46" align="center"></el-table-column>

					<el-table-column prop="id" label="编号" width="60"></el-table-column>

					<el-table-column prop="name" label="角色名称" width="200"></el-table-column>

					<el-table-column prop="add_time" label="添加时间" width="180" :formatter="filterTime"></el-table-column>

					<el-table-column align="right" label="操作">
						<template slot-scope="scope">
							<el-button
								size="mini"
								type="text" 
								@click="del(scope.row.id, 0)"
								v-permission:data="['system', 'roledel']"
							>恢复</el-button>
						</template>
					</el-table-column>
				</el-table>

				<div id="pagination">
					<el-pagination
						@size-change="sizeChange"
						@current-change="pageChange"
						:current-page="page_default.current"
						:page-sizes="[10, 20, 30, 50]"
						:page-size="page_default.num"
						layout="total, sizes, prev, pager, next, jumper"
						:total="page_default.total">
					</el-pagination>
				</div>
			</el-card>
		</div>
	</div>
</template>

<script>
import pageMixin from "@/mixins/page";
import tableMixin from "@/mixins/table";
import commonMixin from "@/mixins/common";

export default {
	name: "system_rolerecycle",
	mixins: [pageMixin, tableMixin, commonMixin],
  	data() {
      	return {
			// 各跳转链接
			urls: {
				add: '/system/roleadd',
				del: '/system/roledel',
				dis: '/system/roledis',
				edit: '/system/roleedit/:id',
				list: '/system/rolelist',
				recycle: '/system/rolerecycle',
			},
		}
	},
	created(){
		this.addScene(this.urls.recycle, 'default', { mapping: 'results' });
	},
	mounted(){
		this.getRequestData();
	},
	methods: {
    }
};
</script>

<style lang="scss">
@import "@/assets/style/table-base.scss";
</style>