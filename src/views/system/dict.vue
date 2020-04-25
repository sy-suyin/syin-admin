<template>
	<div class="content-container">
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
						tooltip-effect="dark"
						style="width: 100%"
						@selection-change="selectionChange"
					>

						<el-table-column label="字典">
							<template slot-scope="scope">
								{{scope.row.name}} - {{scope.row.key}}
							</template>
						</el-table-column>

						<el-table-column prop="add_time" label="添加时间" width="180"></el-table-column>

						<el-table-column align="right" label="操作">
							<template slot-scope="scope">
								<el-button
									size="mini" type="text" 
									@click="add"
									v-permission:page="['dict', 'dataadd']"
								>添加</el-button>

								<el-divider direction="vertical"></el-divider>
							</template>
						</el-table-column>
					</el-table>

					<div id="pagination">
						<el-pagination
							@size-change="def('pageSwitch', $event)"
							@current-change="def('pageSwitch', $event)"
							:current-page="page_default.current_page"
							:page-sizes="[10, 20, 30, 50]"
							:page-size="page_default.page_num"
							layout="total, sizes, prev, pager, next, jumper"
							:hide-on-single-page="true"
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
			use_scene: true,

			scenes: ['default', 'data'],

			page_data: {

				// 页面请求地址
				url: '',

				// 表格数据存储映射
				mapping: '',
	
				// 表格数据, 如果 mapping 不为空, 数据将不存于此处, 而是外层映射对应名称的变量
				results: [],
	
				// 当前分页
				current: 1,

				// 最大页码
				page_max: 1,

				// 每页显示消息数
				page_num: 0,

				// 总记录数
				total: 0,

				// 请求参数
				args: {},
			}
		}
	},
	mounted(){
		this.setRequestUrl('/dict/list');
		this.setRequestUrl('/dict/dictdata', 'data');
		this.getRequestData();
	},
	methods: {
		pageSwitch(page){
			console.log('switch');
			console.log(page);
		},

		dict(){
			
		},

		sence(){
			return () => {

			}
		},

		def: sence('default'),
		data: sence('data'),
	}
}
</script>

<style>

</style>