<template>
	<el-container>
		<el-aside class="sidebar" width="200px">
			<div class="user-info">
				<div class="user-avatar">
					<img src="@/assets/avatar/82.png" alt="">
				</div>

				<div class="user-name">syin</div>
			</div>

			<!-- 侧边导航栏 -->
			<div class="nav">
				<sy-menu></sy-menu>
			</div>

			<div class="background"></div>
		</el-aside>
		<el-container style="background: #eee;height: 100vh;overflow: hidden;">
			<el-header class="container-header">

				<!-- 此处为顶部导航栏内容 -->
				<div class="navbar">
					<i class="el-icon-s-unfold"></i>

					<div>
						<i class="el-icon-s-unfold"></i>
						<i class="el-icon-s-unfold"></i>
						<i class="el-icon-s-unfold"></i>
					</div>
				</div>

				<!-- 此处为顶部下面的面包屑 -->
				<div class="expand">
					<slot name="breadcrumb">
						<el-breadcrumb class="breadcrumb" separator-class="el-icon-arrow-right">
							<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
							<el-breadcrumb-item>活动管理</el-breadcrumb-item>
							<el-breadcrumb-item>活动列表</el-breadcrumb-item>
							<el-breadcrumb-item>活动详情</el-breadcrumb-item>
						</el-breadcrumb>
					</slot>
				</div>
			</el-header>
			<el-main class="content-box">
				<slot></slot>
			</el-main>
			<!-- <el-footer><slot name="footer">Footer</slot></el-footer> -->
		</el-container>
	</el-container>
</template>

<script>
import syMenu from "@/components/layout/sy-menu.vue";

export default {
	name: "app",
	components: {
		syMenu
	},
	data(){
		return {
			menus: [],
		}
	},
	created(){
		// this.menus = this.$store.state.access.menus;
	},

	methods:{
	}
};
</script>

<style lang="scss">
	html, body{
		margin: 0;
	}

	.sidebar{
		background: #000;
		// overflow: hidden !important;
		position: relative;

		ul, li{
			padding:0;
			margin:0;
			list-style:none;
		}

		.user-avatar{
			border-radius: 50%;
			position: relative;
			overflow: hidden;
			z-index: 5;
			width: 32px;
			height: 32px;

			img{
				width: 32px;
				height: 32px;
			}
		}

		.nav{
			z-index: 999;
			color: #fff;
			position: relative;
			font-size: 14px;

			.menu-item-group{	
				.menu-item{
					margin: 0 15px;
					padding: 8px 10px;

					.menu-link{
						display: flex;

						justify-content: space-between;
						align-items: center;
					}

					/* .switch{
						float: right;
					} */

					.menu-item-group{
						margin-top: 10px;

						.menu-item{
							// margin: 0 6px 0;
							margin-left: 6px;
							margin-right: 0;
							padding-right: 0;

							&:last-child{
								padding-bottom: 0;
							}
						}
					}
				}
			}
		}

		.background{
			position: absolute;
			background-image: url(../../assets/img/sidebar-4.jpg);
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

	.container-header{
		background: #fff;
		height: auto !important;
	}

	.container-header .navbar{
		height: 64px;
		box-shadow: 0 1px 4px rgba(0,21,41,.08);
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.container-header .expand .breadcrumb{
		padding: 12px 16px;
		padding-left: 0;
	}

	.content-box{
		margin-top: 20px;
    	padding-top: 0 !important;
	}
</style>