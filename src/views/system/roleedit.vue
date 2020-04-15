<template>
	<div>
		<page-header></page-header>

		<div class="content-container">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					修改角色
				</div>

				<el-form ref="form" :model="form" label-width="80px">
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
						<el-button type="primary" @click="onSubmit" :loading="loading">提交修改</el-button>
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
import {common as commonMixin} from "@/components/mixins/common.js";
import {menus} from '@/config/menu';
import * as util from '@/libs/util.js';
export default {
	name: "system_roleedit",
	mixins: [commonMixin],
  	data() {
      	return {
			id: 0,

			loading: false,

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
		}
	},

	mounted(){
		this.dialog.data.page = menus;

		this.init();
	},

	methods: {

		// 初始化
		init(){
			let id = +this.$route.params.id || 0;

			if(id < 1){
				return this.message('未找到相关数据, 请检查后重试', 'warning', 3000, '/system/rolelist');
			}

			this.loading = true;
			util.get('/system/roledetail/id/'+id).then(res => {
				this.loading = false;
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					this.id = id;
					this.form.name = res.result.name;
					this.form.desc = res.result.description;
					this.getAccessData();
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.message(res.msg, 'warning', 3000, '/system/rolelist');
				}
				else{
					this.message('服务器未响应，请稍后重试', 'warning', 3000, '/system/rolelist');
				}
			}).catch(err => {
				this.loading = false;
				this.message('网络异常, 请稍后重试', 'warning', 3000, '/system/rolelist');
			});
		},

		// 获取权限数据
		getAccessData(){
			this.loading = true;

			util.get('/system/getaccessdata/id/'+this.id).then(res => {
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					this.dialog.data.data = Object.values(res.result.config);
					this.treeInit(res.result.forbid);
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.message(res.msg, 'warning', 3000, '/system/rolelist');
				}
				else{
					this.message('服务器未响应，请稍后重试', 'warning', 3000, '/system/rolelist');
				}
				this.loading = false;
			}).catch(err => {
				this.loading = false;
				this.message('网络异常, 请稍后重试', 'warning', 3000, '/system/rolelist');
			});
		},

		onSubmit(formName) {
			let args = {...this.form};
			args.id = this.id;
			args.data_forbid_edit = +args.data_forbid_edit;
			args.page_forbid_edit = +args.page_forbid_edit;

			if(args.data_forbid_edit){
				args['data_forbid'] = this.getUnselected(this.dialog.selected.data, this.dialog.data.data);
			}

			if(args.page_forbid_edit){
				args['page_forbid'] = this.getUnselected(this.dialog.selected.page, this.dialog.data.page);
			}

			if(args.name == ''){
				return this.message('角色名称不能为空');
			}

			this.loading = true;
			util.post('/system/roleedit', args).then(res => {
				this.loading = false;
				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					this.$router.push({path: '/system/rolelist'})
				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.message(res.msg);
				}
				else{
					this.message('服务器未响应，请稍后重试');
				}
			}).catch(err => {
				this.loading = false;
				this.message('网络异常, 请稍后重试');
			});
		},

		resetForm(formName) {
			this.$refs[formName].resetFields();
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

		/** 
		 * 提示消息
		 * 
		 * @param msg  消息内容
		 * @param type 消息类型
		 * @param duration 消息显示时间, 单位: 毫秒
		 * @param path 消息关闭后跳转路径, 为空不跳转
		 */
		message(msg, type='warning', duration=3000, path=''){
			return this.$message({
				showClose: true,
				message: msg,
				type: type,
				onClose: ()=>{
					if(path != ''){
						this.$router.push({path});
					}
				}
			});
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