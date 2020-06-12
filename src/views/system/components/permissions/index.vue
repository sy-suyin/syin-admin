<template>
	<div>
		<el-button size="small" @click="visible=true">设置权限</el-button>

		<el-dialog
			title="页面权限"
			:visible.sync="visible"
			width="50%">

			<el-tree
				ref="permission_tree"
				:data="results"
				:props="props"
				show-checkbox
				node-key="key"
				class="permission-tree"
				:default-checked-keys="checked"
				label="name"
			/>

			<div slot="footer">
				<el-button type="danger" size="small" @click="visible=false">取消</el-button>
				<el-button type="primary" size="small" @click="confirm">确认</el-button>
			</div>

		</el-dialog>
	</div>
</template>

<script>
export default {
	name: 'permissions',
	props: {
		data: {
			type: Array,
			default: () => {},
		},
		blocklist: {
			type: Array,
			default: () => [],
		}
	},
	data() {
      	return {
			results: [],
			props: {
				children: 'children',
				label: 'name'
			},
			visible: false,
			checked: [],
		}
	},
	mounted(){
	},
	methods: {

		/**
		 * 初始化
		 */
		init(blocklist){
			let checked = [];
			let page_checked = [];

			// 处理数据权限
			let data = this.checkTreeKey(this.data);
			let allowlist = this.getUnselected(blocklist, data);

			Object.keys(allowlist).forEach(controller => {
				let actions = allowlist[controller];
				actions.forEach(action => {
					let key = this.keyRevise(`${controller}_${action}`);
					checked.push(key);
				});
			});

			this.checked = checked;
		},

		/**
		 * 确认选择
		 */
		confirm(){
			this.visible = false;
			let selected = this.$refs.permission_tree.getCheckedNodes();
			let unselected = this.getUnselected(selected, this.data);

			this.$emit('confirm', unselected);
		},

		/**
		 * 获取未选择的选项
		 */
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
		 * 检查tree数据key
		 */
		checkTreeKey(data){
			if(!data){
				return data;
			}

			data.forEach((val, index) => {
				if(!val.hasOwnProperty('children') || val.children.length < 1){
					if(!val.hasOwnProperty('key')){
						data[index]['key'] = val.controller + '_' + val.action;
					}

					data[index]['key'] = this.keyRevise(data[index]['key']);
				}else{
					data[index].children = this.checkTreeKey(val.children);
				}
			});

			return data;
		},

		/**
		 * 将 key - 转换为 _
		 */
		keyRevise(key){
			return key.replace('-', '_')
		}
	},
	watch: {
		blocklist(val, old_val){
			if(val.length > 0){
				this.init(val);
			}
		},
		data: {
			handler(val, old_val){
				console.log(val);

				if(val.length){
					this.results = this.checkTreeKey(val);
				}else{
					this.results = val;
				}

				console.log(this.results);
			},
			immediate: true
		}
	}
}
</script>

<style lang="scss">

</style>