<template>
	<div>

		<!-- 筛选 -->
		<slot name="filter"></slot>

		<!-- 表格区域 -->
		<el-card>
			<!-- 表格顶部工具栏 -->
			<div slot="header" class="clearfix">
				<div class="table-search">
					<el-input
						placeholder="请输入搜索内容"
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

					<el-button
						size="mini"
						type="success"
						icon="el-icon-sort" 
						@click="sort"
					>排序</el-button>

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

			<!-- 表格内容区域 -->
			<el-table
				ref="multipleTable"
				:data="data"
				tooltip-effect="dark"
				style="width: 100%"
			>

				<template v-for="(column, index) in columns">
					<!-- 复选框 -->
					<el-table-column
						v-if="column.prop == 'selection'"
						:width="column.width || '48'" 
						:key="column.prop"
						type="selection"
						align="center"
					>
					</el-table-column>

					<!-- 自定义插槽 -->
					<slot v-else-if="column.slot" :name="column.slot"></slot>

					<!-- 标签栏 -->
					<template v-else-if="column.tags">
						<el-table-column :label="column.label" width="120" :key="index">
							<template v-slot="{row}">
								<div class="tag-group" v-for="tag in column.tags" :key="tag.disabled">
									<span v-if="checkPermission(tag.access)" :key="tag.prop">
										<el-tag
											class="tag-btn"
											:class="tag.class"
											:type=" (tag.data[row[tag.prop]] || tag.data[0]).type || 'success'"
											effect="dark"
											size="mini"
											@click="handle(tag.handle, row)"
										>
										{{ (tag.data[row[tag.prop]] || tag.data[0]).val || tag.optimget(row) }}
										</el-tag>
									</span>
									<span v-else-if="tag.no_access_show" :key="tag.prop">
										<el-tag
											class="tag-btn"
											:class="tag.class"
											:type=" (tag.data[row[tag.prop]] || tag.data[0]).type || 'success'"
											effect="dark"
											size="mini"
										>
										{{ (tag.data[row[tag.prop]] || tag.data[0]).val }}
										</el-tag>
									</span>
								</div>
							</template>
						</el-table-column>
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
								v-permission:data="item.access"
							> {{item.name}} </el-button>

							<el-button
								v-else
								size="mini" type="text"
								@click="handle('jump', item.target, {id: scope.row.id, ...item.params})"
								v-permission:page="item.access"
							> {{item.name}} </el-button>
						</span>
					</template>
				</el-table-column>
			</el-table>

			<!-- 分页 -->
			<slot name="pagination">
				<table-page :pagination="pagination" @handle=handle></table-page>
			</slot>
		</el-card>

	</div>
</template>

<script>
import permission from '@/directive/permission/index'
import {checkPermission} from '@/libs/util';
import tablePage from "@/components/table-page";

export default {
	name: 'db_table',
	directives: { permission },
	components: { tablePage },
	props: {
		data: Array,
		columns: Array,
		operates: Array,
		pagination: Object,
		slot_append: {
			type: Boolean,
			default: false
		}
	},
	data() {
      	return {
			search: {
				keyword: '',
			},
		}
	},
	mounted(){
	},
	methods: {
		checkPermission({controller = '', action = '', type='data'} = {}){
			return checkPermission(controller, action, type);
		},

		handle(...params){
			params[0] != 'handle' && params.unshift('handle');

			this.$emit.apply(this, params);
		}
    }
}
</script>

<style lang="scss">
@import "@/assets/style/table-filter.scss";
</style>