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
				<ul class="menu-item-group">
					<li class="menu-item" v-for="(item, index) in menus" :key=" 'item' + index" :data-index="index"  @click.stop="menuClick(index)">
						<div class="menu-link">
							<span>
								<i class="el-icon-setting"></i>
								{{item.name}}
							</span>

							<i class="switch el-icon-arrow-up" v-if="item.children.length > 0"></i>
						</div>

						<ul class="menu-item-group" v-if="item.children.length > 0" v-show="item.is_open">
							<li class="menu-item" v-for="(menu, menu_index) in item.children" :key="'menu' + menu_index" :data-index="index+'-'+menu_index" @click.stop="menuClick(index, menu_index)">
								<div class="menu-link">
									<span>
										<i class="el-icon-setting"></i>
										{{menu.is_open ? 'a' : 'b'}}
										{{menu.name}} 
									</span>
								</div>

								<ul class="menu-item-group" v-if="menu.children.length > 0" v-show="menu.is_open">
									<li class="menu-item" v-for="(submenu, submenu_index) in menu.children" :key="'submenu' + submenu_index" :data-index="index+'-'+menu_index+'-'+submenu_index" @click.stop="menuClick(index, menu_index, submenu_index)">
										<div class="menu-link">
											<span>
												<i class="el-icon-setting"></i>
												{{submenu.name}}
											</span>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
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
					<el-breadcrumb class="breadcrumb" separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>活动管理</el-breadcrumb-item>
						<el-breadcrumb-item>活动列表</el-breadcrumb-item>
						<el-breadcrumb-item>活动详情</el-breadcrumb-item>
					</el-breadcrumb>
				</div>

			</el-header>
			<el-main>
				<el-card class="box-card">
					<div slot="header" class="clearfix">
						<span>卡片名称</span>
						<el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>
					</div>
					<div v-for="o in 4" :key="o" class="text item">
						{{'列表内容 ' + o }}
					</div>
				</el-card>

			</el-main>
			<el-footer>Footer</el-footer>
		</el-container>
	</el-container>
</template>

<script>
import menu from '@/libs/menu.js';

export default {
	name: "app",
	data(){
		return {
			menus: [],
		}
	},
	created(){
		let menus = menu.getMenus({
			demo: ['list']
		}).then(menus => {
			console.log(menus);
			this.menus = menus;
		});

		console.log('init');
	},

	methods:{
		menuClick(){
			let indexes = [].slice.call(arguments);
			let menu = {...this.menus[indexes[0]]};
			indexes.splice(0, 1);
			let indexes_len = indexes.length;

			if(indexes_len > 0){
				indexes.forEach(index => {
					menu = menu.children[index];
				});
			}

			if(menu.children.length < 1){
				// 没有子节点, 则直接跳转
			}else{
				// 否则开关该菜单节点

				let is_open = menu.is_open || 0;
				menu.is_open = + !is_open;

				if(indexes_len <= 2){
					if(indexes_len == 2){
						this.menus[arguments[0]].children[indexes[0]].children[indexes[1]] = menu;
					}else if(indexes_len == 1){
						this.menus[arguments[0]].children[indexes[0]] = menu;
					}else{
						this.menus[arguments[0]] = menu;
					}
				}else{
					// TODO: 待优化
					let t_arr = [];
					let t_menu = this.menus[0].children[indexes[0]].children[indexes[1]];

					for(let i = 0, len = indexes_len - 3;$i < indexes_len;i++){
						t_menu = t_menu.children[indexes[2 + i]];
						t_arr.push(t_menu);
					}

					t_arr[t_arr.length - 1].children[indexes[indexes_len - 1]] = menu; 

					for(let i = t_arr.length - 2; i >= 0;i--){
						t_arr[i][indexes]

						t_arr[i].children[ indexes[i + 2]] = t_arr[i + 1];
					}
				}


				
			}

			this.$set(this.menus, arguments[0], this.menus[arguments[0]]);

			console.log(menu);
			console.log(menu.is_open);
		}
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
			background-image: url(../assets/img/sidebar-4.jpg);
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
</style>