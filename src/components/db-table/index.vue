<template>
	<!-- 表格区域 -->
	<el-card>
		<!-- 表格顶部工具栏 -->
		<div slot="header" class="clearfix">
			<div class="table-search">
				<slot name="table_search">
					<el-input
						:placeholder="params.search_tip"
						v-model="search_args.keyword"
						size="mini"
					>
						<i slot="suffix" class="el-input__icon el-icon-search search-btn" @click="handle('search', search_args)"></i>
					</el-input>
				</slot>
			</div>

			<div class="table-toolbar">
				<slot name="toolbar_before"></slot>
				<slot name="toolbar">
					<template v-for="(item, index) in toolbar">
						<el-button
							v-if="item.type == 'btn'"
							size="mini"
							:type="item.color || 'primary'"
							:icon="item.icon"
							@click="handle(item.target, item.params)"
							v-permission:data="item.access"
							:key="index"
						> {{item.name}} </el-button>

						<el-button
							v-else
							size="mini"
							:type="item.color || 'primary'"
							:icon="item.icon"
							@click="handle('jump', item.target, item.params)"
							v-permission:page="item.access"
							:key="index"
						> {{item.name}} </el-button>
					</template>
				</slot>
			</div>
		</div>

		<!-- 表格内容区域 -->
		<el-table
			:data="data"
			:ref="params.ref || ''"
			:class="params.class || ''"
			@selection-change="handle('selectionChange', $event)"
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
				<slot v-else-if="column.prop == 'slot'" :name="column.slot"></slot>

				<!-- 标签栏 -->
				<el-table-column
					v-else-if="column.prop == 'tag'"
					:label="column.label" width="120" 
					:key="index"
				>
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
								{{ (tag.data[row[tag.prop]] || tag.data[0]).val }}
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
			<template v-if="params.slot_append" slot="append">
				<slot name="append"></slot>
			</template>

			<!-- 操作栏 -->
			<el-table-column
				align="right" 
				v-if="actionbar.length"
				:label="params.actionbar_name" 
				:width="params.actionbar_width || null"
			>
				<template slot-scope="scope">
					<span v-for="(item, index) in actionbar" :key="index">
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
							size="mini"
							type="text"
							@click="handle('jump', item.target, {id: scope.row.id, ...item.params})"
							v-permission:page="item.access"
						> {{item.name}} </el-button>
					</span>
				</template>
			</el-table-column>
		</el-table>

		<!-- 分页 -->
		<slot name="pagination">
			<table-page
				:pagination="pagination"
				:config="params.page_config"
				@handle="handle"
			></table-page>
		</slot>
	</el-card>
</template>

<script>
import permission from '@/directive/permission/index'
import { checkPermission } from '@/libs/util';
import tablePage from "@/components/table-page";

export default {
	name: 'db_table',
	directives: { permission },
	components: { tablePage },
	props: {
		data: Array,
		columns: Array,
		actionbar: Array,
		toolbar: Array,
		pagination: Object,
		config: Object,
	},
	data() {
      	return {
			params: null,

			search_args: {
				keyword: '',
			},
		}
	},

	created(){
		let def_config = {
			ref: '',
			class: '',
			page_config: null,
			actionbar_width: null,
			actionbar_name: '操作',
			slot_append: false,
			search_tip: '请输入搜索内容',
		};

		this.params = { ...def_config, ...this.config};
	},

	methods: {

		/**
		 * 权限判断
		 */
		checkPermission({controller = '', action = '', type='data'} = {}){
			return checkPermission(controller, action, type);
		},

		/** 
		 * 事件处理中转
		 */
		handle(...params){
			params[0] != 'handle' && params.unshift('handle');

			this.$emit.apply(this, params);
		}
    }
}
</script>