<template>
	<lyaout>
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				添加角色
			</div>

			<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="角色名称">
					<el-input v-model="form.name"></el-input>
				</el-form-item>

				<el-form-item label="数据权限">
					<el-button size="small" @click="dialog.visible.page=true">设置权限</el-button>
				</el-form-item>

				<el-form-item label="访问权限">
					<el-button size="small" @click="dialog.visible.data=true">设置权限</el-button>
				</el-form-item>

				<el-form-item label="备注说明">
					<el-input type="textarea" v-model="form.desc"></el-input>
				</el-form-item>

				<el-form-item>
					<el-button type="primary" @click="onSubmit">立即创建</el-button>
					<el-button>取消</el-button>
				</el-form-item>
			</el-form>
		</el-card>

		<el-dialog
  			title="提示"
			:visible.sync="dialog.visible.page"
			width="50%">

			<el-tree
				ref="page_tree"
				:data="dialog.data.page"
				:props="dialog.props.page"
				show-checkbox
				node-key="path"
				class="permission-tree"
				label="name"
			/>

			<div style="float:right">
				<el-button type="danger" @click="dialog.visible.page=false">取消</el-button>
				<el-button type="primary" @click="pageConfirm">确认</el-button>
			</div>

		</el-dialog>

		<el-dialog
  			title="提示"
			:visible.sync="dialog.visible.data"
			width="50%">

			<el-tree
				ref="data_tree"
				:data="dialog.data.data"
				:props="dialog.props.data"
				show-checkbox
				node-key="path"
				class="permission-tree"
				label="name"
			/>

			<div style="float:right">
				<el-button type="danger" @click="dialog.visible.data=false">取消</el-button>
				<el-button type="primary" @click="dataConfirm">确认</el-button>
			</div>

		</el-dialog>
	</lyaout>
</template>

<script>
// @ is an alias to /src
import Lyaout from "@/components/layout/base-layout.vue";
import {menus} from '@/config/menu';
import util from '@/libs/util.js';

export default {
	name: "roleadd",
	components: {
		Lyaout
	},
  	data() {
      	return {
        	form: {
          		name: '',
				desc: ''
			},

			dialog:{
				props: {
					data: {
						children: 'children',
						label: 'name'
					},
					page: {
						children: 'children',
						label: 'name'
					}
				},
				visible: {
					data: false,
					page: true
				},
				data: {
					data: [],
					page: []
				},
				selected: {
					data: [],
					page: []
				}
			},
		}
	},

	mounted(){
		this.dialog.data.page = menus;
		
		util.get('/system/getaccessdata').then(res => {
			if(res && typeof(res.status) != 'undefined' && res.status > 0){
				this.dialog.data.data = Object.values(res.result.config);
			}
			else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
				this.$message({
					showClose: true,
					message: res.msg,
					type: 'warning'
				});
			}
			else{
				this.$message({
					showClose: true,
					message: '服务器未响应，请稍后重试',
					type: 'warning'
				});
			}
		}).catch(err => {
			// this.is_loading = false;
			console.log(err);

			this.$message({
				showClose: true,
				message: '网络异常, 请稍后重试',
				type: 'warning'
			});
		});
	},

	methods: {
		onSubmit(formName) {
			let args = {...this.form};
			console.log(args);
			console.log(this.dialog.selected);
		},
		resetForm(formName) {
			this.$refs[formName].resetFields();
		},

		dataConfirm(){
			this.dialog.visible.data = false;
			this.dialog.selected.data = this.$refs.data_tree.getCheckedNodes();
		},

		pageConfirm(){
			this.dialog.visible.page = false;
			this.dialog.selected.page = this.$refs.page_tree.getCheckedNodes();
			this.getUnselected(this.dialog.selected.page, this.dialog.data.page);
		},

		// 获取未选择的选项
		getUnselected(selected, data){

			do{
				let next = [];
				data.forEach((val, key)=> {
					console.log(val);
					console.log(key);
				})
			}while(next.length > 0);

			console.log(data);
			console.log(selected);
		}
    }
};
</script>