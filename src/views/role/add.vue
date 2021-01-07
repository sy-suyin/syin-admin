<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					添加角色
				</div>

				<el-form :ref="this.form_name" :model="args" :rules="rules" label-width="80px">

					<template v-for="item in items">
						<!-- 输入分隔 -->
						<el-divider content-position="left" v-if="item.type == 'divider'" :key="item.name">{{item.name}}</el-divider>

						<el-form-item :label="item.name" :prop="item.value" :key="item.name" v-else-if="item.type == 'custom' && item.value == 'data'">
							<form-item v-model="args[item.value]" :type="item.type" :options="item.target ? data[item.target] : item.data" :propValue="item.propValue">
								<permissions :data="data.data"  @confirm="confirm('data', $event)" />
							</form-item>
						</el-form-item>

						<el-form-item :label="item.name" :prop="item.value" :key="item.name" v-else-if="item.type == 'custom' && item.value == 'page'">
							<form-item v-model="args[item.value]" :type="item.type" :options="item.target ? data[item.target] : item.data" :propValue="item.propValue">
								<permissions :data="data.page"  @confirm="confirm('page', $event)" />
							</form-item>
						</el-form-item>

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
import { menus } from '@/config/menu';
import systemApi from '@/api/system';
import permissions from './components/permissions';
import api from '@/api/role';
import formItem from '@/components/form-item';
import config from "@/config/model/role/add";

export default {
	name: "role_add",
	components: {formItem, permissions},
	mixins: [ commonMixin, validateMixin ],
  	data() {
      	return {
        	args: {
				blocklist: {
					data: [],
					page: [],
				},
			},

			data: {
				data: [],
				page: menus,
			},

			title: config.title || '',
			items: config.items,
			rules: config.rules,
			redirect_url: config.redirect_url
		}
	},

	mounted(){
		this.init();
	},

	methods: {
		init(){
			this.loading(true);
			systemApi.getAccessData().then(res => {
				this.data.data = Object.values(res.config);
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
		submit() {
			this.submitChain().then(params => {

				this.loading(true);
				systemApi.addRole(params.args).then(res => {
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
			this.args.blocklist[type] = unselected;
			this.args[`blocklist_${type}_edit`] = true;
		},
    }
};
</script>