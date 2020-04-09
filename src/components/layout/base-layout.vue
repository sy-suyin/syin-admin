<template>
	<div class="layout">
		<layout-aside/>

		<div class="layout-container">
			<div class="layout-container-header">
				<!-- 此处为顶部导航栏内容 -->
				<div class="navbar">
					<i class="el-icon-s-unfold" @click="toggleSidebar"></i>
					<div>
						<ul class="navbar-right">
							<li>
								<i class="el-icon-s-unfold"></i>
							</li>
							<li>
								<i class="el-icon-s-unfold"></i>
							</li>
							<li>
								<el-dropdown @command="userCommand">
									<span class="el-dropdown-link">
										<i class="el-dropdown-icon el-icon-user-solid"></i>
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
// import syMenu from "@/components/layout/sy-menu.vue";
import settingPanel from "@/components/layout/setting-panel.vue";

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
			this.$store.commit('access/active',meta);

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
			this.is_mini = !this.is_mini;
		}
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

	.layout-aside{
		background: #000;
		position: relative;
		height: 100vh;
		flex-basis: 260px;
		transition: all .3s ease;

		.layout-aside-scroll{
			height: 100%;
			overflow: hidden;
		}

		.slider{
			position: absolute;
			top: 0;
			bottom: 0;
			width: 260px;
			transition: width .3s ease;
			z-index: 99;
		}

		.user-info{
			position: relative;
			z-index: 5;
			color: #fff;
			padding: 20px 0;
			font-size: 14px;
			display: flex;
			align-items: center;

			.user-avatar{
				border-radius: 50%;
				position: relative;
				overflow: hidden;
				height: 32px;
				width: 32px;
				min-width: 32px;
				display: inline-block;
				margin-left: 24px;
				margin-right: 10px;

				img{
					width: 32px;
					height: 32px;
				}
			}

			&:after{
				content: "";
				position: absolute;
				bottom: 0;
				right: 15px;
				height: 1px;
				width: calc(100% - 30px);
				background-color: hsla(0,0%,71%,.3);
			}
		}

		.menu-logo{
			position: relative;
			z-index: 2;
			display: flex;
			align-items: center;
			justify-content: left;
			padding: 6px 24px;

			.logo-img{
				height: 32px;
				display: inline-block;
				margin-right: 12px;
			}

			.logo-title{
				color: #fff;
				display: inline-block;
			}

			&:after{
				content: "";
				position: absolute;
				bottom: 0;
				right: 15px;
				height: 1px;
				width: calc(100% - 30px);
				background-color: hsla(0,0%,71%,.3);
			}
		}

		.nav{
			z-index: 999;
			color: #fff;
			position: relative;
			font-size: 14px;
		}

		.background{
			position: absolute;
			background-image: url('http://127.0.0.1:8000/static/api/sidebar/bg-1.jpg');
			z-index: 1;
			height: 100%;
			width: 100%;
			display: block;
			top: 0;
			left: 0;
			background-size: cover;
			background-position: 50%;

			/* .sidebar[data-background-color=black] .sidebar-background:after {
				background: #000;
				opacity: .8;
			} */

			&:after {
				background: #000;

				position: absolute;
				z-index: 3;
				width: 100%;
				height: 100%;
				content: "";
				display: block;
				// background: #fff;
				// opacity: .93;
				opacity: .7;

			}
		}
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

			.navbar{
				padding: 0 20px;
				height: 64px;
				box-shadow: 0 1px 4px rgba(0,21,41,.08);
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding-right: 30px;
				font-size: 18px;

				.navbar-right{
					display: flex;
					align-items: center;

					li{
						padding: 0 4px;
					}

					.el-dropdown-icon{
						font-size: 16px;
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