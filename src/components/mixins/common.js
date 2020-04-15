import { Loading } from 'element-ui';
import permission from '@/directive/permission/index.js' // 权限判断指令
import pageHeader from "@/components/page-header";

export const common = {
	directives: { permission },

	components: {pageHeader},

	data(){
		return {
			is_loading: false,
			loading_handle: null,
		}
	},

	methods: {

		/**
		 * 设置加载状态
		 * @param bool is_loading 是否开启加载状态 
		 */
		loading(is_loading = true){
			// if(is_loading){
			// 	this.loading_handle = Loading.service({ fullscreen: true });
			// }else{
			// 	this.loading_handle && this.loading_handle.close();
			// }

			// this.is_loading = is_loading;
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
	},
}