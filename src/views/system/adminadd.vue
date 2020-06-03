<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">		
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					添加管理员
				</div>

				<el-form ref="form" :model="form" :rules="rules" label-width="80px" @validate="formValidatea">
					<el-form-item label="登录账号" prop="login">
						<el-input v-model="form.login"></el-input>
					</el-form-item>

					<el-form-item label="用户名称" prop="name">
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
						<el-button type="primary" @click="onSubmit">立即创建</el-button>
						<el-button>取消</el-button>
					</el-form-item>
				</el-form>
			</el-card>
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common.js";
import { debounce } from '@/libs/util';
import Chain from '@/libs/Chain.js';
import { addAdmin, getRoles } from '@/api/system';

let instance = new Chain();

export default {
	name: "system_adminadd",
	mixins: [commonMixin],
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

		// console.log(Form);
		instance.sort(['verify', 'submit']);

		instance.bind('verify', ()=>{
			console.log(this);
			let args = this.form;
			return {...args};
		});

		instance.bind('submit', (args)=>{
			console.log(this);
			console.log(args);
			// let args = this.form;
			// return args;
		});
		// this.test();

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

		/**
		 * 提交前进行数据检查
		 */
		validate(form_name, cbfunc){
			this.$refs[form_name].validate((valid) => {
				if(!valid){
					return false;
				}

				cbfunc();
			});
		},

		onSubmit(formName) {
			console.log(instance.commit());
			return false;

			let form_name = 'form';
			let args = {...this.form};

			this.validate('form', ()=>{
				this.loading(true);

				addAdmin(args).then(res => {
					this.loading(false);
					this.$router.push({path: this.redirect_url})
				}).catch(e => {
					this.loading(false);
					let msg = e.message || '网络异常, 请稍后重试';
					this.$message(msg, 'warning');
				});
			});
		},

		/**
		 * 重置表单
		 */
		resetForm(form_name) {
			this.$refs[form_name].resetFields();
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
	},
	
};
</script>