<template>
	<div class="layout">
		<layout-aside :user="user"/>

		<div class="layout-container">
			<container-head :user="user"></container-head>

			<div class="layout-container-main">
				<error-page v-if="is_error" :user="user"></error-page>
				<router-view v-else></router-view>
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

export default {
	name: "base-layout",
	components: {
		layoutAside, settingPanel, containerHead, errorPage
	},
	data(){
		return {
			is_error: false,
			breadcrumbs: [],
			user: {}
		}
	},
	created(){
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

		this.user = user;

		// 处理路由信息
		if(this.$route.name != 'not_fonund'){
			let meta = this.$route.meta;

			// 设置浏览器标题
			window.document.title = meta.title;
	
			// 激活路由
			this.$store.commit('access/active',meta);

			this.is_error = false;
		}else{
			// 设置浏览器标题
			window.document.title = 404;

			let meta = {
				name: this.$route.name,
				controller: 'errorpage',
				action: 404
			};

			// 激活路由
			this.$store.commit('access/active', meta);

			this.is_error = true;
		}
	},
	methods:{
	},
	computed: {
		page_title: {
			get() {
				return this.$store.state.access.page_title;
			}
		},
	},
	watch: {
		page_title(val){
			if(this.page_title && this.page_title != 'not_fonund'){
				// 设置浏览器标题
				window.document.title = this.page_title;

				this.is_error = false;
			}else{
				this.is_error = true;
			}
		}
	}
};
</script>

<style lang="scss">
@import "@/assets/style/layout.scss";
</style>