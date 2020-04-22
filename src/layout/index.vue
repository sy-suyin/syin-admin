<template>
	<div class="layout">
		<layout-aside/>

		<div class="layout-container">
			<div class="layout-container-header">
				<!-- 此处为顶部导航栏内容 -->
				<div class="navbar">
					<i class="icon el-icon-s-unfold" @click="toggleSidebar" v-if="sidebar_mini"></i>
					<i class="icon el-icon-s-fold" @click="toggleSidebar" v-else></i>

					<div>
						<ul class="navbar-right">
							<li>
								<i class="icon el-icon-s-unfold"></i>
							</li>
							<li>
								<i class="icon el-icon-s-unfold"></i>
							</li>
							<li>
								<el-dropdown @command="userCommand">
									<span class="el-dropdown-link">
										<i class="icon el-dropdown-icon el-icon-user-solid"></i>
									</span>
									<el-dropdown-menu slot="dropdown">
										<el-dropdown-item command="profile">个人中心</el-dropdown-item>
										<el-dropdown-item command="setting">系统设置</el-dropdown-item>
										<el-dropdown-item command="logout">退出登录</el-dropdown-item>
									</el-dropdown-menu>
								</el-dropdown>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="layout-container-main">
				<error-page v-if="is_error"></error-page>
				<router-view v-else></router-view>
			</div>
		</div>
		<setting-panel></setting-panel>
	</div>
</template>

<script>
import layoutAside from "./components/aside";
import settingPanel from "./components/setting-panel";
import errorPage from "./components/error-page";
import { mapState } from 'vuex'

export default {
	name: "base-layout",
	components: {
		layoutAside, settingPanel, errorPage
	},
	data(){
		return {
			is_error: false,
			breadcrumbs: [],
		}
	},
	created(){
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
		userCommand(command){
			// 退出登录
			if(command == 'logout'){
				this.$store.commit('auth/logout');
			}
		},

		toggleSidebar(){
			this.$store.dispatch('settings/changeSetting', {
				key: 'sidebar_mini',
				value: !this.sidebar_mini
			})
		}
	},
	computed: {
		...mapState('settings', {
			sidebar_mini: state =>state.sidebar_mini,
		}),
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