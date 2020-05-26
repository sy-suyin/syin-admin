<template>
	<div class="table-base">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">管理员列表</h2>
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
						<el-button
							size="mini" 
							type="primary" 
							icon="el-icon-plus"
							@click="jump('add')"
							v-permission:page="['system', 'adminadd']"
						>添加</el-button>

						<el-button size="mini" type="success" icon="el-icon-sort" @click="sort">排序</el-button>

						<el-button 
							size="mini"
							type="warning" 
							icon="el-icon-s-promotion" 
							@click="jump('recycle')" 
							v-permission:page="['system', 'adminrecycle']"
						>回收站</el-button>

						<el-button 
							size="mini" 
							type="danger" 
							icon="el-icon-delete" 
							@click="del(-1, 1)"
							v-permission:page="['system', 'admindel']"
						>删除</el-button>
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

					<el-table-column label="排序" width="86">
						<template slot-scope="{row}">
							<el-input v-model="row.sort" type="number" size="mini" max="99" min="0"/>
						</template>
					</el-table-column>

					<el-table-column prop="id" label="编号" width="60"></el-table-column>

					<el-table-column prop="name" label="名称" width="200"></el-table-column>

					<el-table-column prop="login_name" label="登录账号"></el-table-column>

					<el-table-column label="状态" width="120">
						<template slot-scope="scope">

							<div v-if="checkPermission('system', 'admindis', 'data')">
								<el-tag 
									class="disabled-btn" 
									type="success" 
									effect="dark" 
									size="mini" 
									@click="disabled(scope.row.id, 1)" 
									v-if="scope.row.is_disabled < 1"
								>启用</el-tag>

								<el-tag 
									class="disabled-btn" 
									type="danger" 
									effect="dark" 
									size="mini" 
									@click="disabled(scope.row.id, 0)" 
									v-else
								>禁用</el-tag>
							</div>
							<div v-else>
								
								<el-tag 
									class="disabled-btn" 
									type="success" 
									effect="dark" 
									size="mini" 
									v-if="scope.row.is_disabled < 1"
								>启用</el-tag>

								<el-tag 
									class="disabled-btn" 
									type="danger" 
									effect="dark" 
									size="mini" 
									v-else
								>禁用</el-tag>

							</div>

						</template>
					</el-table-column>

					<el-table-column label="角色">
						<template slot-scope="scope">
							<el-tag class="role-tag" effect="plain" type="info" size="mini" v-for="item in scope.row.roles" :key="item.id">{{item.name}}</el-tag>
						</template>
					</el-table-column>

					<el-table-column prop="add_time" label="添加时间" width="180" :formatter="filterTime"></el-table-column>

					<el-table-column align="right" label="操作">
						<template slot-scope="scope">
							<el-button
								size="mini" type="text" 
								@click="jump('edit', {id: scope.row.id})"
								v-permission:page="['system', 'adminedit']"
							>修改</el-button>

							<el-divider direction="vertical"></el-divider>

							<el-button
								size="mini"
								type="text" 
								@click="del(scope.row.id, 1)"
								v-permission:page="['system', 'admindel']"
							>删除</el-button>
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
import {page as pageMixin} from "@/mixins/page.js";
import {table as tableMixin} from "@/mixins/table.js";
import {common as commonMixin} from "@/mixins/common.js";
import * as Util from '@/libs/util.js';

export default {
	name: "system_adminlist",
	mixins: [commonMixin, pageMixin, tableMixin],
  	data() {
      	return {
			// 各跳转链接
			urls: {
				add: '/system/adminadd',
				del: '/system/admindel',
				dis: '/system/admindis',
				edit: '/system/adminedit/:id',
				list: '/system/adminlist',
				recycle: '/system/adminrecycle',
				sort: '/system/adminsort'
			},
		}
	},
	mounted(){
		this.addScene(this.urls.list);
		this.getRequestData();
	},
	methods: {
    }
};
</script>

<style lang="scss">
@import "@/assets/style/table-base.scss";

.role-tag{
	margin-right: 4px;
}
</style>