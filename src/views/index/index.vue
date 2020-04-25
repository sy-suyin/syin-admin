<template>
	<div>
		<div class="content-container">
			<el-row>
				<el-col :span="6">
					<el-card>
						<el-divider></el-divider>
						日销售额￥12,423
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<el-divider></el-divider>
						日销售额￥12,423
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<el-divider></el-divider>
						日销售额￥12,423
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<el-divider></el-divider>
						日销售额￥12,423
					</el-card>
				</el-col>
			</el-row>
		</div>
	</div>
</template>

<script>
import * as util from '@/libs/util.js';
import {common as commonMixin} from "@/mixins/common.js";
export default {
	name: "home",
	mixins: [commonMixin],
	mounted(){
	},
  	data() {
      	return {
        	form: {
          		name: '',
				region: '',
				date1: '',
				date2: '',
				delivery: false,
				type: [],
				resource: '',
				desc: ''
			}
		}
    },
};
</script>
