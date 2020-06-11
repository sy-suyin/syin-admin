<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					修改管理员
				</div>

				<el-form :ref="this.form_name" :model="form" :rules="rules" label-width="80px">
					<el-form-item label="登录账号" prop="login">
						<el-input v-model="form.login"></el-input>
					</el-form-item>

					<el-form-item label="用户名称" prop="name">
						<el-input v-model="form.name"></el-input>
					</el-form-item>

					<el-form-item label="权限角色" prop="roles">
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

					<el-form-item label="登录密码" prop="password">
						<el-input v-model="form.password" show-password></el-input>
					</el-form-item>

					<el-form-item>
						<el-button type="primary" @click="formSubmit">提交修改</el-button>
						<el-button>取消</el-button>
					</el-form-item>
				</el-form>
			</el-card>
		</div>
	</div>
</template>

<script>
import commonMixin from "@/mixins/common";
import validateMixin from "@/mixins/validate";
import { debounce, requestAll } from '@/libs/util';
import { getAdmin, editAdmin, getRoles } from '@/api/system';

export default {
	name: "system_adminadd",
	mixins: [ commonMixin, validateMixin ],
  	data() {
      	return {
			id: 0,
			roles: [],
        	form: {
				login: '',
          		name:  '',
				password: '',
				roles: []
			},
			rules: {
				login: [
					{ required: true, message: '请输入登录账号名称', trigger: 'blur' },
				],
				name: [
					{ required: true, message: '请输入用户名称', trigger: 'blur' },
				],
				roles: [
					{ required: true, message: '请先选择角色', trigger: 'blur' },
				],
			},
			redirect_url: '/system/adminlist',
		}
	},
	mounted(){
		this.init();
	},
	methods: {
		init(){
			let id = +this.$route.params.id;

			if(!id){
				return this.message('参数异常', 'warning', 3000, this.redirect_url);
			}

			this.loading(true);
			requestAll([getAdmin(id), getRoles()]).then((res)=>{
				let {0: admin, 1: roles} = res;

				// 处理管理员数据
				this.id = id;
				this.form.login = admin.login_name;
				this.form.name = admin.name;
				admin.roles.forEach(val => {
					this.form.roles.push(val.id);
				});

				// 处理角色数据
				this.roles = roles;
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000, this.redirect_url);
			}).finally(()=>{
				this.loading(false);
			});
		},

		submit(params) {
			params.args.id = this.id;

			this.loading(true);
			editAdmin(params.args).then(res => {
				this.$router.push({path: this.redirect_url})
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000);
			}).finally(()=>{
				this.loading(false);
			});
		},
    }
};
</script>