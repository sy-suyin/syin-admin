<template>
	<div>
		<!-- 数据字典 主要用于测试 pageMixin 多分页效果 -->

		<el-pagination
			@size-change="test('debug', $event)"
			@current-change="test('debug', $event)"
 	   		layout="prev, pager, next"
    		:total="1000">
		</el-pagination>

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
			
		}
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

		test: sence('xxx'),

		debug(){
			console.log('yyy');
		}
	}
}
</script>

<style>

</style>