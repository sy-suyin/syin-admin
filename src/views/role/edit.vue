<template>
	<div>
		<page-header></page-header>

		<div class="content-container" v-loading="is_loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					修改角色
				</div>

				<el-form :ref="this.form_name" :model="args" :rules="rules" label-width="80px">

					<template v-for="item in items">
						<!-- 输入分隔 -->
						<el-divider content-position="left" v-if="item.type == 'divider'" :key="item.name">{{item.name}}</el-divider>

						<el-form-item :label="item.name" :prop="item.value" :key="item.name" v-else-if="item.type == 'custom' && item.value == 'data'">
							<form-item v-model="args[item.value]" :type="item.type" :options="item.target ? data[item.target] : item.data" :propValue="item.propValue">
								<permissions :data="data.data"  @confirm="confirm('data', $event)" :blocklist="blocklist.data"/>
							</form-item>
						</el-form-item>

						<el-form-item :label="item.name" :prop="item.value" :key="item.name" v-else-if="item.type == 'custom' && item.value == 'page'">
							<form-item v-model="args[item.value]" :type="item.type" :options="item.target ? data[item.target] : item.data" :propValue="item.propValue">
								<permissions :data="data.page"  @confirm="confirm('page', $event)" :blocklist="blocklist.page" />
							</form-item>
						</el-form-item>

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
import {menus} from '@/config/menu';
import { requestAll } from '@/libs/util';
import systemApi from '@/api/system';
import permissions from './components/permissions';
import api from '@/api/role';
import formItem from '@/components/form-item';
import config from "@/config/model/role/edit";

export default {
	name: "system_roleedit",
	mixins: [ commonMixin, validateMixin ],
	components: { formItem, permissions },
  	data() {
      	return {
			args: {
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

			data: {
				data: [],
				page: menus,
			},

			title: config.title || '',
			items: [],
			rules: config.rules,
			redirect_url: config.redirect_url
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
			requestAll([systemApi.getRole(id), systemApi.getAccessData(id)]).then((res)=>{
				let [role, access_data] = res;

				// 处理角色数据
				this.args.id = id;
				this.args.name = role.name;
				this.args.desc = role.description;

				// 设置禁止名单
				this.blocklist = access_data.blocklist,

				// 处理后台返回的数据
				this.data.data = Object.values(access_data.config);

				// 开始渲染页面
				this.items = config.items;
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

				systemApi.editRole(params.args).then(res => {
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