import { Loading } from 'element-ui';
import permission from '@/directive/permission/index.js' // 权限判断指令
import pageHeader from "@/components/page-header";
import {checkPermission} from '@/libs/util.js';

export const common = {
	directives: { permission },

	components: {pageHeader},

	data(){
		return {
			is_loading: false,
			loading_handle: null,
			loading_close: null,
		}
	},

	methods: {

		/**
		 * 设置加载状态
		 * 如果不使用全屏加载, 则需在在使用加载区域的根元素上添加 v-loading="is_loading"
		 * 
		 * @param bool loading 	  是否开启加载状态 
		 * @param bool fullscreen 是否全屏显示加载中
		 * @param int  duration   加载中状态关闭时间, 单位秒, -1表示不自动关闭, is_loading为true时此值才会被用到
		 */
		loading(loading = true, fullscreen = false, duration = -1){
			if(this.loading_close){
				clearTimeout(this.loading_close);
			}

			if(fullscreen){
				if(loading){
					this.loading_handle = Loading.service({ fullscreen: true });
				}else{
					if(this.loading_handle){
						this.loading_handle.close();
						this.loading_handle = null;
					}
				}
			}else{
				this.is_loading = loading;
			}
			
			if(loading && duration > -1){
				this.loading_close = setTimeout(() => {
					this.loading(false, fullscreen);
				}, duration * 1000);
			}
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

		/**
		 * 判断是否有访问权限
		 * 
		 * @param string controller 请求的控制
		 * @param string action		请求的方法
		 * @param string type		page/data 权限类型，默认为data 
		 */
		checkPermission(controller, action, type='data'){
			checkPermission(controller, action, 'data');
		}
	},
}