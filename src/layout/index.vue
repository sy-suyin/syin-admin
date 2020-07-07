<template>
	<div class="layout">
		<layout-aside :user="user"/>

		<div class="layout-container">
			<container-head :user="user"></container-head>

			<div class="layout-container-main">
				<error-page v-if="is_error" :info="error_info"></error-page>
				<router-view v-show="!is_error"></router-view>
			</div>
		</div>
		<setting-panel></setting-panel>
	</div>
</template>

<script>
import layoutAside from "./components/aside";
import settingPanel from "./components/setting-panel";
import containerHead from "./components/container-head";
import errorPage from "./components/error-page";
import { config } from '@/libs/util';

export default {
	name: "base-layout",
	components: {
		layoutAside, settingPanel, containerHead, errorPage
	},
	data(){
		return {
			is_error: false,
			error_info: {},
			user: {}
		}
	},
	created(){
		this.init();
	},
	methods:{

		/**
		 * 初始化
		 */
		init(){
			// 获取用户信息
			let user = this.$store.getters['auth/user'];

			// 验证用户信息
			if(!user){
				return this.$store.commit('auth/logout');
			}

			// 当为锁屏状态时, 不允许访问其他页面
			if(user.hasOwnProperty('is_lock') && user.is_lock){
				return this.$router.replace({path: '/lock'})
			}

			// 保存用户信息
			this.user = user;
			// 处理路由信息
			this.routeChange(this.$route.name, this.$route.meta);

			// 绑定页面切换处理
			this.$event.on('routeChange', (name, meta)=>{
				this.routeChange(name, meta);
			});
		},

		/**
		 * 路由改变
		 * 
		 * @param {string} name 页面名称. 如需加载相关异常页面, 需传入以下名字之一 (miss_page, 403, 404, 500)
		 * @param {object} meta 页面相关数据
		 */
		routeChange(name, meta){
			const error_pages = ['miss_page', 403, 404, 500];

			// 处理路由信息
			if(-1 == error_pages.indexOf(name)){
				this.is_error = false;

				// 设置浏览器标题
				window.document.title = meta.title;

				// 激活路由
				this.$store.commit('access/active', meta);
			}else{
				this.is_error = true;

				if(name == 'miss_page'){
					meta.title = '404';
				}

				this.error_info = {
					type: name,
					title: meta.title || name,
					msg: meta.msg || ''
				};
			}
		}
	},

	// 监听,当路由发生变化的时候执行
	watch:{
		$route({name, meta}, from){
			this.routeChange(name, meta);
		}
	},
};
</script>

<style lang="scss">
@import "@/assets/style/layout.scss";
</style>