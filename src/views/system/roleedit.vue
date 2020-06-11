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
						<el-button size="small" @click="dialog.visible.data=true">设置权限</el-button>
					</el-form-item>

					<el-form-item label="访问权限">
						<el-button size="small" @click="dialog.visible.page=true">设置权限</el-button>
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

			<el-dialog
				title="页面权限"
				:visible.sync="dialog.visible.page"
				width="50%">

				<el-tree
					ref="page_tree"
					:data="dialog.data.page"
					:props="dialog.props.page"
					show-checkbox
					node-key="key"
					class="permission-tree"
					:default-checked-keys="dialog.default_checked.page"
					label="name"
				/>

				<div slot="footer">
					<el-button type="danger" size="small" @click="dialog.visible.page=false">取消</el-button>
					<el-button type="primary" size="small" @click="pageConfirm">确认</el-button>
				</div>

			</el-dialog>

			<el-dialog
				title="数据权限"
				:visible.sync="dialog.visible.data"
				width="50%">

				<el-tree
					ref="data_tree"
					:data="dialog.data.data"
					:props="dialog.props.data"
					show-checkbox
					node-key="key"
					class="permission-tree"
					:default-checked-keys="dialog.default_checked.data"
					label="name"
				/>

				<div slot="footer">
					<el-button type="danger" size="small" @click="dialog.visible.data=false">取消</el-button>
					<el-button type="primary" size="small" @click="dataConfirm">确认</el-button>
				</div>

			</el-dialog>
		</div>
	</div>
</template>

<script>
// @ is an alias to /src
import commonMixin from "@/mixins/common";
import validateMixin from "@/mixins/validate";
import {menus} from '@/config/menu';
import { requestAll } from '@/libs/util';
import { getRole, editRole, getAccessData } from '@/api/system';

export default {
	name: "system_roleedit",
	mixins: [ commonMixin, validateMixin ],
  	data() {
      	return {
			id: 0,

        	form: {
          		name: '',
				desc: '',
				data_forbid_edit: false,
				page_forbid_edit: false,
			},

			dialog:{
				props: {
					data: {
						children: 'children',
						label: 'name'
					},
					page: {
						children: 'children',
						label: 'name'
					}
				},
				visible: {
					data: false,
					page: false
				},
				data: {
					data: [],
					page: []
				},
				selected: {
					data: [],
					page: []
				},
				default_checked: {
					data: [],
					page: []
				}
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
		this.optimget = 'getParams';
		this.dialog.data.page = menus;
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
				this.id = id;
				this.form.name = role.name;
				this.form.desc = role.description;

				// 处理后台返回的数据
				this.dialog.data.data = Object.values(access_data.config);
				this.treeInit(access_data.forbid);
			}).catch(e => {
				let msg = e.message || '网络异常, 请稍后重试';
				this.message(msg, 'warning', 3000, this.redirect_url);
			}).finally(()=>{
				this.loading(false);
			});
		},

		getParams(params){
			if(params.args.data_forbid_edit){
				params.args['data_forbid'] = this.getUnselected(this.dialog.selected.data, this.dialog.data.data);
			}

			if(params.args.page_forbid_edit){
				params.args['page_forbid'] = this.getUnselected(this.dialog.selected.page, this.dialog.data.page);
			}
			return params;
		},

		submit(params) {
			params.args.id = this.id;

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

		dataConfirm(){
			this.dialog.visible.data = false;
			this.dialog.selected.data = this.$refs.data_tree.getCheckedNodes();
			this.form.data_forbid_edit = true;
 		},

		pageConfirm(){
			this.dialog.visible.page = false;
			this.dialog.selected.page = this.$refs.page_tree.getCheckedNodes();
			this.form.page_forbid_edit = true;
		},

		// 权限树初始化
		treeInit(forbid){
			let data_checked = [];
			let page_checked = [];

			// 处理数据权限
			this.dialog.data.data = this.checkTreeKey(this.dialog.data.data);
			let data_access = this.getUnselected(forbid.data_forbid, this.dialog.data.data);

			Object.keys(data_access).forEach(controller => {
				let actions = data_access[controller];
				actions.forEach(action => {
					let key = this.keyRevise(`${controller}_${action}`);
					data_checked.push(key);
				});
			});

			// 处理页面权限
			this.dialog.data.page = this.checkTreeKey(this.dialog.data.page);
			let page_access = this.getUnselected(forbid.page_forbid, this.dialog.data.page);

			Object.keys(page_access).forEach(controller => {
				let actions = page_access[controller];
				actions.forEach(action => {
					let key = this.keyRevise(`${controller}_${action}`);
					page_checked.push(key);
				});
			});

			this.dialog.default_checked.data = data_checked;
			this.dialog.default_checked.page = page_checked;
		},

		// 获取未选择的选项
		getUnselected(selected, data){
			let unselected = [];

			do{
				var next = [];
				data.forEach((val, key)=> {

					if(val.hasOwnProperty('children') && val.children.length > 0){
						next.push(...val.children);
					}else{
						let is_selected = false;
						selected.forEach((item, key) => {
							if(item.controller == val.controller && item.action == val.action){
								is_selected = true;
								return false;
							}
						});

						if(!is_selected){
							if(! unselected.hasOwnProperty(val.controller)){
								unselected[val.controller] = [];
							}

							unselected[val.controller].push(val.action);
						}
					}
				});

				data = next; 
			}while(next.length > 0);

			return unselected;
		},

		// 检查tree数据key
		checkTreeKey(data){
			if(!data){
				return data;
			}

			data.forEach((val, key) => {
				if(!val.hasOwnProperty('children') || val.children.length < 1){
					if(!val.hasOwnProperty('key')){
						data[key]['key'] = val.controller + '_' + val.action;
					}

					data[key]['key'] = this.keyRevise(data[key]['key']);
				}else{
					data[key].children = this.checkTreeKey(val.children);
				}
			});

			return data;
		},

		// 将key中 - 转换为 _
		keyRevise(key){
			return key.replace('-', '_')
		}
    }
};
</script>