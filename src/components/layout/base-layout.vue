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
				<!-- 此处为顶部下面的面包屑 -->
				<div class="expand" v-if="!is_error">
					<slot name="breadcrumb">
						<el-breadcrumb class="breadcrumb" separator-class="el-icon-arrow-right">
							<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>

							<el-breadcrumb-item v-for="(breadcrumb, index) in breadcrumbs" :key="'breadcrumb-'+index">{{breadcrumb.name}}</el-breadcrumb-item>
						</el-breadcrumb>
					</slot>

					<slot name="breadcrumb-after">
					</slot>
				</div>
			</div>

			<div class="layout-container-main">
				<slot></slot>
			</div>
		</div>
		<setting-panel></setting-panel>
	</div>
</template>

<script>
import layoutAside from "@/components/layout/layout-aside.vue";
import settingPanel from "@/components/layout/setting-panel.vue";
import { mapState } from 'vuex'

export default {
	name: "base-layout",
	components: {
		layoutAside, settingPanel
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

			// 设置面包屑数据
			this.breadcrumbs = this.$store.getters['access/breadcrumb'];
		}else{
			// 设置浏览器标题
			window.document.title = 404;

			let meta = {
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
	}
};
</script>

<style lang="scss">
	html, body{
		margin: 0;
	}

	.layout{
		display: flex;
		overflow: hidden;
	}

	.logo-title, .user-name, .menu-name, .menu-switch-icon{
		transition: all .3s ease;
		white-space: nowrap;
	}

	.layout-aside-mini{
		flex-basis: 80px;

		.slider{
			width: 80px;
		}

		.logo-title, .user-name, .menu-name, .menu-switch-icon{
			opacity: 0;
			transform: translate3d(-20px,0,0);
		}

		.menu-item-icon{
			margin-left: 6px;
			margin-right: 6px;
		}

		&:hover{
			.slider{
				width: 260px;
			}

			.logo-title, .user-name, .menu-name, .menu-switch-icon{
				opacity: 1;
				transform: translate3d(0,0,0);
			}

			.menu-item-icon{
				margin-left: 0;
				margin-right: 12px;
			}
		}
	}

	.layout-container{
		flex-grow: 1;
		position: relative;
		height: 100vh;
		background: #eee;
		height: 100vh;
		overflow-y: auto;

		.layout-container-header{
			padding: 0 !important;
			background: #fff;
			height: auto !important;

			.icon{
				font-size: 25px;
			}

			.navbar{
				padding: 0 20px;
				height: 64px;
				box-shadow: 0 1px 4px rgba(0,21,41,.08);
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding-right: 30px;

				.navbar-right{
					display: flex;
					align-items: center;

					li{
						padding: 0 4px;
					}

					.el-dropdown-icon{
						font-size: 20px;
					}
				}
			}

			.expand{
				padding: 0 20px;

				.breadcrumb{
					padding: 12px 16px;
					padding-left: 0;
				}
			}
		}

		.layout-container-main{
			margin-top: 20px;
			padding-top: 0 !important;
			padding: 20px;
		}

	}
</style>