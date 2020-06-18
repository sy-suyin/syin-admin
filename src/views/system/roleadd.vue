<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					添加角色
				</div>

				<el-form :ref="this.form_name" :model="form" :rules="rules" label-width="80px">
					<el-form-item label="角色名称">
						<el-input v-model="form.name"></el-input>
					</el-form-item>

					<el-form-item label="数据权限">
						<permissions :data="permissions.data" @confirm="confirm('data', $event)" />
					</el-form-item>

					<el-form-item label="页面权限">
						<permissions :data="permissions.page"  @confirm="confirm('page', $event)" />
					</el-form-item>

					<el-form-item label="备注说明">
						<el-input type="textarea" v-model="form.desc"></el-input>
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
import { menus } from '@/config/menu';
import { addRole, getAccessData } from '@/api/system';
import permissions from './components/permissions';

export default {
	name: "system_roleadd",
	mixins: [ commonMixin, validateMixin ],
	components: { permissions },
  	data() {
      	return {
        	form: {
          		name: '',
				desc: '',
				blocklist: {
					data: [],
					page: [],
				},
			},

			permissions: {
				data: [],
				page: menus,
			},

			rules: {
				name: [
					{ required: true, message: '请输入用户名称', trigger: 'blur' },
				],
			},

			redirect_url: '/system/rolelist',
		}
	},

	mounted(){
		this.init();
	},

	methods: {
		init(){
			this.loading(true);
			getAccessData().then(res => {
				this.permissions.data = Object.values(res.config);
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000, this.redirect_url);
			}).finally(()=>{
				this.loading(false);
			});
		},

		/**
		 * 提交数据
		 */
		submit(params) {
			this.submitChain().then(params => {

				this.loading(true);
				addRole(params.args).then(res => {
					this.$router.push({path: this.redirect_url})
				}).catch(e => {
					let msg = e.message || '网络异常, 请稍后重试';
					this.message(msg, 'warning', 3000);
				}).finally(()=>{
					this.loading(false);
				});
			});
		},

		/**
		 * 权限确认选择
		 */
		confirm(type, unselected){
			this.form.blocklist[type] = unselected;
			this.form[`blocklist_${type}_edit`] = true;
		},
    }
};
</script>