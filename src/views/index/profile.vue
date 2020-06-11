<template>
	<div>
		<page-header></page-header>

		<div class="content-container">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					个人中心
				</div>

				<el-row>
					<el-col :span="12">
						<el-form ref="form" :model="form" :rules="rules" label-width="80px">
							<el-form-item label="登录账号" prop="login">
								<el-input v-model="form.login"></el-input>
							</el-form-item>

							<el-form-item label="用户名称" prop="name">
								<el-input v-model="form.name"></el-input>
							</el-form-item>

							<el-divider content-position="left">如不修改密码, 下面请留空</el-divider>

							<el-form-item label="登录密码">
								<el-input v-model="form.password"></el-input>
							</el-form-item>

							<el-form-item label="确认密码">
								<el-input v-model="form.confirmpwd"></el-input>
							</el-form-item>

							<el-form-item label="原始密码">
								<el-input v-model="form.oldpwd"></el-input>
							</el-form-item>

							<el-divider></el-divider>

							<el-form-item>
								<el-button type="primary" @click="onSubmit('form')">提交</el-button>
								<el-button>取消</el-button>
							</el-form-item>
						</el-form>
					</el-col>
					<el-col :span="12">
						<div class="avatar-box">
							<div>
								用户头像
							</div>
							<div class="avatar">
								<img :src="form.avatar_url" alt="">
							</div>
							<div>
								<el-button @click="show_dialog=true">更换头像</el-button>
							</div>
						</div>
					</el-col>
				</el-row>
			</el-card>
		</div>

		<el-dialog
			:visible.sync="show_dialog"
			width="800px"
			center>
			<div>
				<ul class="user-avatar">
					<li v-for="i in 110" @click="updateAvatar(i)" :key="i">
						<img :src="getAvatarUrl(i)" alt="">
					</li>
				</ul>

			</div>
		</el-dialog>
	</div>
</template>

<script>
import { config } from '@/libs/util';
import commonMixin from "@/mixins/common";
import { updateProfile } from '@/api/user';

export default {
	name: "home",
	mixins: [commonMixin],
	mounted(){
	},
  	data() {
      	return {
			domain: '',
			user:{
				login_name: '',
				name: '',
				avatar: '',
			},
        	form: {
				login: '',
				name: '',
				password: '',
				confirmpwd: '',
				oldpwd: '',
				avatar: '',
				avatar_url: '',
			},
			rules: {
				login: [
					{ required: true, message: '请输入登录账号名称', trigger: 'blur' },
				],
				name: [
					{ required: true, message: '请输入用户名称', trigger: 'blur' },
				],
			},
			show_dialog: false
		}
	},
	mounted(){
		let user = this.$store.getters['auth/user'];

		this.form.login = user.login_name;
		this.form.name = user.name;
		this.form.avatar_url = user.avatar;
		this.user = user;
		this.domain = config('domain');
	},
	methods: {

		/**
		 * 提交数据
		 */
		onSubmit(formName){
			let args = {...this.form};
			args.password = args.password.trim();
			args.oldpwd = args.oldpwd.trim();
			delete args.avatar_url;

			this.$refs[formName].validate((valid) => {
				if(!valid){
					return false;
				}

				if(args.password.trim() != ''){
					if(args.password !== args.confirmpwd){
						return this.message('确认密码不一致');
					}

					if(args.oldpwd == ''){
						return this.message('原始密码不能为空');
					}

					if(args.oldpwd == args.password){
						return this.message('新密码和原始密码不能一样');
					}
				}

				this.loading(true);
				updateProfile(args).then(res => {
					this.updateUser(args);
					this.message('数据更新成功', 'success');
				}).catch(e => {
					let msg = e.message || '网络异常, 请稍后重试';
					this.message(msg, 'warning', 3000);
				}).finally(()=>{
					this.loading(false);
				});
			});
		},

		/**
		 * 更新本地数据
		 */
		updateUser(args){
			this.user.login_name = args.login;
			this.user.name = args.name;

			if(args.avatar){
				this.user.avatar = this.getAvatarUrl(args.avatar);
			}

			this.$store.commit('auth/setLogin', this.user);
		},

		/**
		 * 获取头像地址
		 */
		getAvatarUrl(id){
			let url = this.domain+'/static/api/avatar/' + id + '.png';
			return url;
		},

		/**
		 * 修改用户头像
		 */
		updateAvatar(id){
			this.form.avatar = id;
			this.form.avatar_url = this.getAvatarUrl(id); 
			this.show_dialog = false;
		}
	}
};
</script>

<style lang="scss" scoped>
.avatar-box{
	padding-left: 20px;

	img{
		width: 100px;
		height: 100px;
		margin: 10px 0;
	}
}

.user-avatar{
	display: flex;
    flex-wrap: wrap;

	li{
		display: inline-block;
		flex: 1 1 12.5%;
		padding: 2px;
	}
}
</style>