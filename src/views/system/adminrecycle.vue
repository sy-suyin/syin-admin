<template>
	<div class="table-base">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">管理员回收站</h2>
				</div>
			</template>
		</page-header>

		<div class="content-container">
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
						<el-button size="mini" type="primary" icon="el-icon-s-promotion" @click="jump('list')" v-permission:page="['system', 'adminlist']">列表</el-button>
						<el-button size="mini" type="success" icon="el-icon-delete" @click="del(-1, 0)">还原</el-button>
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
							<el-button size="mini" type="text" @click="del(scope.row.id, 0)">恢复</el-button>
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
		</div>
	</div>
</template>

<script>
import {page as pageMixin} from "@/components/mixins/page.js";
import {table as tableMixin} from "@/components/mixins/table.js";
import {common as commonMixin} from "@/components/mixins/common.js";
import pageHeader from "@/components/page-header";
import Table from '@/libs/Table.js';
import Factory from '@/libs/Factory.js';
import * as util from '@/libs/util.js';import { Loading } from 'element-ui';

export default {
	name: "system_adminrecycle",
	mixins: [pageMixin, tableMixin, commonMixin],
	components: {pageHeader},
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
			},
		}
	},
	mounted(){
		this.page_url = this.urls.recycle;
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