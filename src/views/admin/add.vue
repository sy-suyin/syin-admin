<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">		
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					{{title}}
				</div>

				<el-form :ref="this.form_name" :model="args" :rules="rules" label-width="80px">

					<template v-for="item in items">
						<!-- 输入分隔 -->
						<el-divider content-position="left" v-if="item.type == 'divider'" :key="item.name">{{item.name}}</el-divider>

						<el-form-item :label="item.name" :prop="item.value" :key="item.name" v-else>
							<form-item v-model="args[item.value]" :type="item.type" :options="item.target ? data[item.target] : item.data" :propValue="item.propValue"></form-item>
						</el-form-item>
					</template>

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
import api from '@/api/admin';
import formItem from '@/components/form-item';
import config from "@/config/model/admin/add";

export default {
	name: "admin_add",
	components: {formItem},
	mixins: [ commonMixin, validateMixin ],
  	data() {
      	return {
			args: {},
			data: {
				roles: [],
			},
			title: config.title || '',
			items: config.items,
			rules: config.rules,
			redirect_url: config.redirect_url,
		}
	},
	mounted(){
		this.init();
	},
	methods: {

		init(){
			this.loading(true);
			api.create().then(res => {
				this.data.roles = res.roles;
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000, this.redirect_url);
			}).finally(()=>{
				this.loading(false);
			});
		},

		submit(){
			this.submitChain().then(params => {
				api.addAdmin(params.args).then(res => {
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