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
						<el-button type="primary" @click="submit">提交修改</el-button>
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
import { debounce, requestAll } from '@/libs/util';
import api from '@/api/admin';
import formItem from '@/components/form-item';
import config from "@/config/model/admin/edit";

export default {
	name: "admin_edit",
	components: {formItem},
	mixins: [ commonMixin, validateMixin ],
  	data() {
      	return {
			args: {},
			data: {
				roles: [],
			},
			title: config.title || '',
			// items: config.items,
			items: [],
			rules: config.rules,
			redirect_url: config.redirect_url,
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
			api.edit({id}).then(res => {
				this.args = {...res.result};
				this.args.login = res.result.login_name;
				this.data.roles = res.roles;
				this.args.roles = [];
				res.result.roles.forEach(val => {
					this.args.roles.push(val.id);
				});

				// 开始渲染页面
				this.items = config.items;
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000, this.redirect_url);
			}).finally(()=>{
				this.loading(false);
			});
		},

		submit() {
			this.submitChain().then(params => {

				this.loading(true);
				api.update(params.args).then(res => {
					this.$router.push({path: this.redirect_url})
				}).catch(e => {
					let msg = e.message || '网络异常, 请稍后重试';
					this.message(msg, 'warning', 3000);
				}).finally(()=>{
					this.loading(false);
				});
			});
		},
    }
};
</script>