<template>
	<div>
		<page-header></page-header>

		<div class="content-container">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					添加角色
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
						<el-button type="primary" @click="onSubmit">立即创建</el-button>
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
					node-key="path"
					class="permission-tree"
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
					node-key="path"
					class="permission-tree"
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
import {common as commonMixin} from "@/components/mixins/common.js";
import {menus} from '@/config/menu';
import * as util from '@/libs/util.js';
export default {
	name: "system_roleadd",
	mixins: [commonMixin],
  	data() {
      	return {
        	form: {
          		name: '',
				desc: ''
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
				}
			},
		}
	},

	mounted(){
		this.dialog.data.page = menus;

		util.get('/system/getaccessdata').then(res => {
			if(res && typeof(res.status) != 'undefined' && res.status > 0){
				this.dialog.data.data = Object.values(res.result.config);
			}
			else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
				this.$message({
					showClose: true,
					message: res.msg,
					type: 'warning'
				});
			}
			else{
				this.$message({
					showClose: true,
					message: '服务器未响应，请稍后重试',
					type: 'warning'
				});
			}
		}).catch(err => {
			this.$message({
				showClose: true,
				message: '网络异常, 请稍后重试',
				type: 'warning'
			});
		});
	},

	methods: {
		onSubmit(formName) {
			let args = {...this.form};
			args['data_forbid'] = this.getUnselected(this.dialog.selected.data, this.dialog.data.data);
			args['page_forbid'] = this.getUnselected(this.dialog.selected.page, this.dialog.data.page);

			if(args.name == ''){
				return this.message('角色名称不能为空');
			}

			this.loading = true;
			util.post('/system/roleadd', args).then(res => {
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
 		},

		pageConfirm(){
			this.dialog.visible.page = false;
			this.dialog.selected.page = this.$refs.page_tree.getCheckedNodes();
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
				})

				data = next; 
			}while(next.length > 0);

			return unselected;
		},
    }
};
</script>