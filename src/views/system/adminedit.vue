<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					修改管理员
				</div>

				<el-form ref="form" :model="form" :rules="rules" label-width="80px" @validate="formValidatea">
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
						<el-button type="primary" @click="onSubmit">提交修改</el-button>
						<el-button>取消</el-button>
					</el-form-item>
				</el-form>
			</el-card>
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common.js";
import {detail as detailMixin} from "@/mixins/detail.js";
import { debounce, requestAll } from '@/libs/util';
import { getAdmin, editAdmin, getRoles } from '@/api/system';

export default {
	name: "system_adminadd",
	mixins: [commonMixin, detailMixin],
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

		/**
		 * 提交前进行数据检查
		 */
		validate(form_name, cbfunc){
			this.$refs[form_name].validate((valid) => {
				if(!valid){
					return false;
				}
			});

			cbfunc();
		},

		onSubmit() {
			let args = {...this.form};
			args.id = this.id;

			this.validate('form', ()=>{
				this.loading(true);

				editAdmin(args).then(res => {
					this.$router.push({path: this.redirect_url})
				}).catch(e => {
					let msg = e.message || '网络异常, 请稍后重试';
					this.message(msg, 'warning', 3000);
				}).finally(()=>{
					this.loading(false);
				});
			});
		},

		resetForm(formName) {
			this.$refs[formName].resetFields();
		},
		
		/**
		 * 表单验证消息提示, 在页面数据较多时, 默认的的提示方式用户可能会看不见(不在可是区域)
		 * 此方法会获取表单检验中第一个检验不合格的字段, 并进行提示
		 *
		 */
		formValidatea(field, valid = true, msg = ''){
			!valid && this.validationTips(msg);
		},

		/**
		 * 验证提示
		 * 150毫秒内, 仅能提示一次
		 */
		validationTips: debounce(150, function(msg){
			this.message(msg);
		})
    }
};
</script>