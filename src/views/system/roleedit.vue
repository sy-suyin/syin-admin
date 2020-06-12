<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					修改角色
				</div>

				<el-form :ref="this.form_name" :model="form" :rules="rules" label-width="80px">
					<el-form-item label="角色名称">
						<el-input v-model="form.name"></el-input>
					</el-form-item>

					<el-form-item label="数据权限">
						<permissions :data="permissions.data" :blocklist="blocklist.data" @confirm="confirm('data', $event)" />
					</el-form-item>

					<el-form-item label="页面权限">
						<permissions :data="permissions.page" :blocklist="blocklist.page" @confirm="confirm('page', $event)" />
					</el-form-item>

					<el-form-item label="备注说明">
						<el-input type="textarea" v-model="form.desc"></el-input>
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
import {menus} from '@/config/menu';
import { requestAll } from '@/libs/util';
import { getRole, editRole, getAccessData } from '@/api/system';
import permissions from './components/permissions';

export default {
	name: "system_roleedit",
	mixins: [ commonMixin, validateMixin ],
	components: { permissions },
  	data() {
      	return {
			form: {
			  id: 0,
          		name: '',
				desc: '',
				blocklist_data_edit: false,
				blocklist_page_edit: false,
				blocklist: {
					data: [],
					page: [],
				},
			},

			blocklist: {
				data: [],
				page: [],
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

		// 初始化
		init(){
			let id = +this.$route.params.id;

			if(!id){
				return this.message('参数异常', 'warning', 3000, this.redirect_url);
			}

			this.loading(true);
			requestAll([getRole(id), getAccessData(id)]).then((res)=>{
				let {0: role, 1: access_data} = res;

				// 处理角色数据
				this.form.id = id;
				this.form.name = role.name;
				this.form.desc = role.description;

				// 设置禁止名单
				this.blocklist = access_data.blocklist,

				// 处理后台返回的数据
				this.permissions.data = Object.values(access_data.config);
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
			this.loading(true);
			editRole(params.args).then(res => {
				this.$router.push({path: this.redirect_url})
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000);
			}).finally(()=>{
				this.loading(false);
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