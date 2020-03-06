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
					<el-button size="small">设置权限</el-button>
				</el-form-item>

				<el-form-item label="访问权限">
					<el-button size="small">设置权限</el-button>
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
			width="50%"
			:before-close="handleClose">

			<el-tree
				ref="tree"
				:data="menus"
				:props="dialog.props.page"
				show-checkbox
				node-key="path"
				class="permission-tree"
				label="name"
			/>

			<div style="float:right">
				<el-button type="danger" @click="dialog.visible.page=false">取消</el-button>
				<el-button type="primary" @click="conmfirmRole">确认</el-button>
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
				region: '',
				date1: '',
				date2: '',
				delivery: false,
				type: [],
				resource: '',
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
				}
			},

			menus: [],
		}
	},

	mounted(){
		this.menus = menus;
		console.log(menus);

		
		util.get('/system/getaccessdata').then(res => {
			// this.is_loading = false;
			console.log(res);
			return;

			if(res && typeof(res.status) != 'undefined' && res.status > 0){
				// 此处添加相关登录代码
				this.$store.commit('auth/set_login',res.result.user);
				this.$store.commit('access/set', {
					data_forbid: [],	// 数据权限黑名单
					page_forbid: [],	// 页面权限黑名单
				});

				let redirect = localStorage.getItem('user_redirect');
				localStorage.removeItem('user_redirect');

				let redirect_path = redirect ? redirect : this.$store.getters['access/routers'][0].path;
				this.$router.push({path: redirect_path})
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
			this.is_loading = false;

			this.$message({
				showClose: true,
				message: '网络异常, 请稍后重试',
				type: 'warning'
			});
		});
	},

	methods: {
		onSubmit(formName) {
			this.$refs[formName].validate((valid) => {
				if (valid) {
					alert('submit!');
				} else {
					console.log('error submit!!');
					return false;
				}
			});
		},
		resetForm(formName) {
			this.$refs[formName].resetFields();
		},

		conmfirmRole(){
			this.dialog.visible.page = false;

			let keys = this.$refs.tree.getCheckedKeys();
			console.log(keys);

			let nodes = this.$refs.tree.getCheckedNodes();
			console.log(nodes);
		}
    }
};
</script>