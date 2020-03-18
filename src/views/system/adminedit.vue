<template>
	<lyaout>
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				修改管理员
			</div>

			<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="登录账号">
					<el-input v-model="form.login"></el-input>
				</el-form-item>

				<el-form-item label="用户名称">
					<el-input v-model="form.name"></el-input>
				</el-form-item>

				<el-form-item label="权限角色">
					<el-select v-model="form.roles" placeholder="请选择活动区域" multiple>

						<el-option
							v-for="item in roles"
							:key="item.id"
							:label="item.name"
							:value="item.id">
						</el-option>

					</el-select>
				</el-form-item>

			    <el-divider content-position="left">如果不修改密码, 下面留空</el-divider>

				<el-form-item label="登录密码">
					<el-input v-model="form.password" show-password></el-input>
				</el-form-item>

				<el-form-item>
					<el-button type="primary" @click="onSubmit" :loading="loading">立即创建</el-button>
					<el-button>取消</el-button>
				</el-form-item>
			</el-form>
		</el-card>
	</lyaout>
</template>

<script>
// @ is an alias to /src
import Lyaout from "@/components/layout/base-layout.vue";
import util from '@/libs/util.js';

export default {
	name: "system-adminadd",
	components: {
		Lyaout
	},
  	data() {
      	return {
			id: 0,

			roles: [],

			loading: false,

        	form: {
				login: '',
          		name:  '',
				password: '',
				roles: []
			}
		}
	},
	mounted(){
		this.init();
	},
	methods: {
		init(){
			let id = +this.$route.params.id || 0;

			if(id < 1){
				return this.message('未找到相关数据, 请检查后重试', 'warning', 3000, '/system/adminlist');
			}

			this.loading = true;
			util.get('/system/admindetail/id/'+id).then(res => {
				this.loading = false;
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					this.id = id;

					this.form.login = res.result.login_name;
					this.form.name = res.result.name;

					res.result.roles.forEach(val => {
						this.form.roles.push(val.id);
					});

					this.getRoles();
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.message(res.msg, 'warning', 3000, '/system/adminlist');
				}
				else{
					this.message('服务器未响应，请稍后重试', 'warning', 3000, '/system/adminlist');
				}
			}).catch(err => {
				this.loading = false;

				this.message('网络异常, 请稍后重试', 'warning', 3000, '/system/adminlist');
			});
		},
		getRoles(){
			util.get('/system/getallroles').then(res => {
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					this.roles = res.result;
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.message(res.msg, 'warning', 3000, '/system/adminlist');
				}
				else{
					this.message('服务器未响应，请稍后重试', 'warning', 3000, '/system/adminlist');
				}
			}).catch(err => {
				this.message('网络异常, 请稍后重试', 'warning', 3000, '/system/adminlist');
			});
		},
		onSubmit(formName) {
			let args = {...this.form};
			args.id = this.id;

			if(args.login == ''){
				return this.message('登录账号不能为空');
			}

			if(args.name == ''){
				return this.message('角色名称不能为空');
			}

			if(args.roles.length < 1){
				return this.message('请先选择角色权限');
			}

			this.loading = true;
			util.post('/system/adminedit', args).then(res => {
				this.loading = false;
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					this.$router.push({path: '/system/adminlist'})
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.message(res.msg);
				}
				else{
					this.message('服务器未响应，请稍后重试');
				}
			}).catch(err => {
				this.loading = false;
				this.message('网络异常, 请稍后重试');
			});
		},

		resetForm(formName) {
			this.$refs[formName].resetFields();
		},

		/** 
		 * 提示消息
		 * 
		 * @param msg  消息内容
		 * @param type 消息类型
		 * @param duration 消息显示时间, 单位: 毫秒
		 * @param path 消息关闭后跳转路径, 为空不跳转
		 */
		message(msg, type='warning', duration=3000, path=''){
			return this.$message({
				showClose: true,
				message: msg,
				type: type,
				onClose: ()=>{
					if(path != ''){
						this.$router.push({path});
					}
				}
			});
		},
    }
};
</script>