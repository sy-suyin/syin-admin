<template>
	<!-- 表格区域 -->
	<el-card>

		<!-- 表格顶部工具栏 -->
		<div slot="header" class="clearfix">
			<slot name="filter"></slot>
			
			<div>
				<!-- 左侧为操作栏 -->
				<div class="table-toolbar">
					<slot name="toolbar">
						<template v-for="(item, index) in toolbar">
							<el-button
								size="mini"
								:type="item.color || 'primary'"
								:icon="item.icon"
								@click="tableBtnClick(item.type, item.access, item.params)"
								:key="index"
							> {{item.name}} </el-button>
						</template>
					</slot>

					<slot name="toolbar_after"></slot>
				</div>

				<!-- 右侧为搜索栏 -->
				<div class="table-features">
					<slot name="table_search">
						<div class="table-search">
							<el-input
								:placeholder="params.search_tip"
								v-model="search_args.keyword"
								size="mini"
							>
								<i slot="suffix" class="el-input__icon el-icon-search search-btn" @click="handle('search', search_args)"></i>
							</el-input>
						</div>
					</slot>

					<el-button-group class="btn-group">
						<el-dropdown>
							<el-button size="mini" icon="el-icon-download">
								<i class="el-icon-arrow-down el-icon--right"></i>
							</el-button>

							<el-dropdown-menu slot="dropdown">
								<el-dropdown-item>JSON</el-dropdown-item>
								<el-dropdown-item>XML</el-dropdown-item>
								<el-dropdown-item>CSV</el-dropdown-item>
								<el-dropdown-item>TXT</el-dropdown-item>
								<el-dropdown-item>MS-Word</el-dropdown-item>
								<el-dropdown-item>MS-Excel</el-dropdown-item>
							</el-dropdown-menu>
						</el-dropdown>
						<el-button size="mini" icon="el-icon-s-operation"></el-button>
					</el-button-group>
				</div>
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
					:label="column.label"
					width="160" 
					:key="index"
				>
					<template v-slot="{row}">
						<!-- 在此处传入一个标志, 此标志判断是否允许修改数据, 如果不允许修改数据, 则数据仅可查看 -->
						<table-switch :row="row" :column="column" :disabled="!checkPermission(column.access)"></table-switch>
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
					<span 
						v-for="(item, index) in actionbar"
						:key="index"
					>
						<template
							v-if="checkPermission(item.access, item.type == 'btn' ? 'data' : 'page' )"
						>
							<el-divider direction="vertical" v-if="index > 0"></el-divider>

							<el-button
								size="mini"
								type="text"
								@click="tableBtnClick(item.type, item.access, {id: scope.row.id, ...item.params})"
							> {{item.name}} </el-button>
						</template>
					</span>
				</template>
			</el-table-column>
		</el-table>

		<!-- 表格底部 -->
		<slot name="footer"></slot>
	</el-card>
</template>

<script>
import permission from '@/directive/permission/index'
import { checkPermission } from '@/libs/util';
import tableSwitch from '@/components/table-switch';

export default {
	name: 'db_table',
	directives: { permission },
	components: { tableSwitch },
	props: {
		data: Array,
		columns: Array,
		actionbar: Array,
		pagination: Object,
		urls: Object,
		pages: Object,
		config: Object,
	},
	data() {
      	return {
			params: null,

			search_args: {
				keyword: '',
			},

			// 权限信息表
			data_access: {},
			page_access: {},

			// 默认表格上方操作
			toolbar: [
				{
					type: 'btn',
					// name: '刷新',
					access: 'reload',
					icon: "el-icon-refresh-left",
					color: 'primary',
				},
				{
					type: 'url',
					name: '添加',
					icon: "el-icon-plus",
					color: 'primary',
					access: 'add',
				},
				{
					type: 'btn',
					name: '排序',
					icon: "el-icon-sort",
					color: 'success',
					access: 'sort',
				},
				{
					type: 'url',
					name: '回收站',
					color: 'warning',
					icon: "el-icon-s-promotion",
					access: 'recycle',
				},
				{
					type: 'btn',
					name: '删除',
					color: 'danger',
					icon: "el-icon-delete",
					access: 'del',
					params: {
						operate: 1,
					}
				},
			],
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

		for(let key in this.urls){
			let url = this.urls[key].split('/');
			if(url[0].length){
				continue;
			}

			let controller = url[1] || 'index';
			let action = url[2] || 'index';

			this.data_access[key] = checkPermission(controller, action, 'data');
		}

		for(let key in this.pages){
			let page = this.pages[key].split('/');

			if(page[0].length){
				continue;
			}

			let controller = page[1] || 'index';
			let action = page[2] || 'index';

			this.page_access[key] = checkPermission(controller, action, 'page');
		}
	},

	methods: {

		/**
		 * 权限判断
		 */
		checkPermission(key, type = 'data'){
			let access = this[type+'_access'];
			return access.hasOwnProperty(key) ? access[key] : true;
		},

		/** 
		 * 事件处理中转
		 */
		handle(...params){
			params[0] != 'handle' && params.unshift('handle');

			this.$emit.apply(this, params);
		},

		tableBtnClick(type, ...params){
			type == 'url' && params.unshift('jump');
			params.unshift('handle');
			this.$emit.apply(this, params);
		}
    }
}
</script>