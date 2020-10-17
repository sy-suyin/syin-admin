<template>
	<!-- 表格区域 -->
	<el-card>

		<!-- 表格顶部工具栏 -->
		<div slot="header" class="clearfix">
			<table-filter
				v-if="params.filters && params.filters.length"
				:fields="params.filters"
				@handle="handle"
			></table-filter>

			<div>
				<!-- 左侧为操作栏 -->
				<div class="table-toolbar">
					<template v-for="(item, index) in params.toolbar">
						<el-button
							size="mini"
							:type="item.color || 'primary'"
							:icon="item.icon"
							@click="tableBtnClick(item.type, item.access, item.params)"
							:key="index"
							v-if="checkPermission(item.access, item.type == 'btn' ? 'data' : 'page' )"
						> {{item.name}} </el-button>
					</template>

					<slot name="toolbar_after"></slot>
				</div>

				<!-- 右侧为搜索栏 -->
				<div class="table-features">
					<slot name="table_search">
						<div class="table-search">
							<el-input
								:placeholder="params.config.search_tip"
								v-model="search_args.key"
								size="mini"
							>
								<i slot="suffix" class="el-input__icon el-icon-search search-btn" @click="handle('search', search_args)"></i>
							</el-input>
						</div>
					</slot>

					<!-- 数据导出功能 -->
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
			:ref="params.config.ref"
			:class="params.config.class"
			@selection-change="handle('selectionChange', $event)"
			tooltip-effect="dark"
			style="width: 100%"
		>
			<template v-for="(column, index) in params.columns">
				<!-- 复选框 -->
				<el-table-column
					v-if="column.type == 'selection'"
					:width="column.width || '48'" 
					:key="column.type"
					type="selection"
					align="center"
				>
				</el-table-column>

				<!-- 自定义插槽 -->
				<slot v-else-if="column.type == 'slot'" :name="column.slot"></slot>

				<!-- 修改 -->
				<el-table-column
					v-else-if="column.type == 'edit'"
					:width="column.width"
					:label="column.label"
					:key="index"
				>
					<template v-slot="{row}">
						<el-input
							v-model="row.sort"
							:type="column.input || 'text'"
							size="mini"
							min="0"
							:max="column.max"
							:disabled="!checkPermission(column.access)"
							@change="editVal($event, column.handle, row)"
						/>
					</template>
				</el-table-column>

				<!-- 开关 -->
				<el-table-column
					v-else-if="column.type == 'switch'"
					:label="column.label"
					width="160" 
					:key="index"
				>
					<template v-slot="{row}">
						<!-- 在此处传入一个标志, 此标志判断是否允许修改数据, 如果不允许修改数据, 则数据仅可查看 -->
						<table-switch
							:row="row"
							:column="column"
							:disabled="!checkPermission(column.access)"
							@switch="editVal($event, column.handle, row)"
						></table-switch>
					</template>
				</el-table-column>

				<!-- 标签 -->
				<el-table-column
					v-else-if="column.type == 'label'"
					:label="column.label"
					:key="index"
				>
					<template v-slot="{row}">
						<el-tag
							class="table-tag"
							effect="plain"
							type="info"
							size="mini"
							v-for="item in row[column.prop]"
							:key="column.prop + item.id"
						>{{item.name}}</el-tag>
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
			<template v-if="params.config.slot_append" slot="append">
				<slot name="append"></slot>
			</template>

			<!-- 操作栏 -->
			<el-table-column
				align="right" 
				v-if="params.actionbar.length"
				:label="params.config.actionbar_name" 
				:width="params.config.actionbar_width || null"
			>
				<template slot-scope="scope">
					<span 
						v-for="(item, index) in params.actionbar"
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
		<slot name="footer">
			<table-page
				:pagination="pagination"
				@handle="handle"
			></table-page>
		</slot>
	</el-card>
</template>

<script>
import { debounce, checkPermission } from '@/libs/util';
import permission from '@/directive/permission/index'
import tableSwitch from '@/components/table-switch';
import tableToolbar from "@/components/table-toolbar";
import tableFilter from "@/components/table-filter";
import tablePage from "@/components/table-page";

export default {
	name: 'db_table',
	directives: { permission },
	components: { tableFilter, tableSwitch, tableToolbar, tablePage },
	props: {
		data: Array,
		pagination: Object,
		config: Object,
	},
	data() {
      	return {
			params: {
				urls: [],
				pages: [],
				columns: [],
				actionbar: [],
				filters: [],
				// 默认配置
				config: {
					ref: '',
					class: '',
					actionbar_width: null,
					actionbar_name: '操作',
					slot_append: false,
					search_tip: '请输入搜索内容',
				}
			},

			search_args: {
				key: '',
			},

			// 权限信息表
			data_access: {},
			page_access: {},
		}
	},

	created(){
		this.params = {...this.params, ...this.config};
		this.initPermission();
	},

	methods: {

		/**
		 * 初始化权限信息
		 */
		initPermission(){
			for(let key in this.params.urls){
				let url = this.params.urls[key].split('/');
				if(url[0].length){
					continue;
				}

				let controller = url[1] || 'index';
				let action = url[2] || 'index';

				this.data_access[key] = checkPermission(controller, action, 'data');
			}

			for(let key in this.params.pages){
				let page = this.params.pages[key].split('/');

				if(page[0].length){
					continue;
				}

				let controller = page[1] || 'index';
				let action = page[2] || 'index';

				this.page_access[key] = checkPermission(controller, action, 'page');
			}
		},

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

		/**
		 * 数据表格中按钮点击
		 */
		tableBtnClick(type, ...params){
			type == 'url' && params.unshift('jump');
			params.unshift('handle');
			this.$emit.apply(this, params);
		},

		/**
		 * 数据值修改
		 * 延迟100ms提交, 以防止input数值修改时频繁提交
		 */
		editVal: debounce(100, function(val, handle, row){
			if(!handle) return;

			let params = ['handle', handle, {
				id: row.id,
				val: val
			}];
			this.$emit.apply(this, params);
		}),
    }
}
</script>