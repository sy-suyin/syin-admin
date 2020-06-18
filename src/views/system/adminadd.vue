<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">		
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					添加管理员
				</div>

				<el-form :ref="this.form_name" :model="form" :rules="rules" label-width="80px">
					<el-form-item label="登录账号" prop="login">
						<el-input v-model="form.login"></el-input>
					</el-form-item>

					<el-form-item label="用户名称" prop="name" >
						<el-input v-model="form.name"></el-input>
					</el-form-item>

					<el-form-item label="登录密码" prop="password">
						<el-input v-model="form.password" show-password></el-input>
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

					<el-form-item>
						<el-button type="primary" @click="submit">立即创建</el-button>
						<el-button @click="$router.back(-1)">取消</el-button>
					</el-form-item>
				</el-form>
			</el-card>
		</div>
	</div>
</template>

<script>
import commonMixin from "@/mixins/common";
import validateMixin from "@/mixins/validate";
import { debounce } from '@/libs/util';
import { addAdmin, getRoles } from '@/api/system';

export default {
	name: "system_adminadd",
	mixins: [ commonMixin, validateMixin ],
  	data() {
      	return {
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
				password: [
					{ required: true, message: '请输入登录密码', trigger: 'blur' },
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
			getRoles().then(res => {
				this.roles = res;
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000, this.redirect_url);
			});
		},

		submit(){
			this.submitChain().then(params => {

				this.loading(true);
				addAdmin(params.args).then(res => {
					this.$router.push({path: this.redirect_url})
				}).catch(e => {
					let msg = e.message || '网络异常, 请稍后重试';
					this.$message(msg, 'warning');
				}).finally(()=>{
					this.loading(false);
				});
			});

		},
	},
};
</script>