<template>
	<div>
		<div class="right-panel" :class="{show: is_show}">
			<div class="show-panel" @click="is_show=!is_show">
				<i class="icon el-icon-setting"></i>
			</div>

			<div class="panel" @click.prevent="showb">
				<h4 class="header-title">SIDEBAR FILTERS</h4>

				<div class="sidebar-filter color-badge">
					<span class="badge active" style="background-color: #9368e9"></span>
					<span class="badge" style="background-color: #2ca8ff"></span>
					<span class="badge" style="background-color: #0bf"></span>
					<span class="badge" style="background-color: #18ce0f"></span>
					<span class="badge" style="background-color: #f44336"></span>
					<span class="badge" style="background-color: #e91e63"></span>
				</div>

			    <el-divider></el-divider>

				<h4 class="header-title">SIDEBAR BACKGROUND</h4>

				<div class="sidebar-filter color-badge">
					<span class="badge active" style="background-color: #000"></span>
					<span class="badge" style="background-color: hsla(0,0%,78%,.2)"></span>
					<span class="badge" style="background-color: #f44336"></span>
				</div>

			    <el-divider></el-divider>

				<div class="switch-box">
					Sidebar Mini

					<el-switch
						v-model="is_mini"
						active-color="#13ce66"
						inactive-color="#ff4949">
					</el-switch>
				</div>

			    <el-divider></el-divider>

				<h4 class="header-title">IMAGES</h4>

				<ul class="imgs">
					<li v-for="(img, index) in background_images" :key="index" :class="{active: index==selected_index.bg_image}">
						<img :src="img">
					</li>
				</ul>
			</div>

			<div class="mask" @click="is_show=!is_show"></div>
		</div>
	</div>
</template>

<script>
export default {
	data(){
		return {
			is_show: true,
			is_mini: false,

			selected_index: {
				silder_filter: 0,
				bg_color: 0,
				bg_image: 0,
			},

			background_images: [
				'http://127.0.0.1:8000/static/api/sidebar/bg-1.jpg',
				'http://127.0.0.1:8000/static/api/sidebar/bg-2.jpg',
				'http://127.0.0.1:8000/static/api/sidebar/bg-3.jpg',
				'http://127.0.0.1:8000/static/api/sidebar/bg-4.jpg',
			]
		}
	},
	methods:{
		show(){
			console.log('show');
		},
		showa(){
			console.log('showa');
		},
		showb(){
			console.log('showb');
		},
		showc(){
			console.log('showc');
		},
	}
}
</script>

<style lang="scss">
.right-panel{
	position: fixed;
	right: 0;
	top: 0;
	bottom: 0;

	.show-panel{
		position: absolute;
		top: 215px;
		width: 48px;
		height: 48px;
		background: #1890FF;
		text-align: center;
		border-radius: 6px 0 0 6px;
		left: -48px;

		-moz-user-select:none;/*火狐*/
		-webkit-user-select:none;/*webkit浏览器*/
		-ms-user-select:none;/*IE10*/
		-khtml-user-select:none;/*早期浏览器*/
		user-select:none;

		.icon{
			font-size: 24px;
			color: #fff;
			line-height: 48px;
		}
	}

	.panel{
		display: none;

		.header-title{
			font-size: 12px;
			font-weight: 600;
			text-transform: uppercase;
			text-align: center;
			height: 30px;
			line-height: 25px;
		}

		.color-badge{
			display: flex;
			justify-content: center;

			.badge{
				border: 3px solid #fff;
				border-radius: 50%;
				cursor: pointer;
				display: inline-block;
				height: 23px;
				margin-right: 5px;
				position: relative;
				width: 23px;
				padding: 8px;
			}

			.active, .badge:hover{
				border-color: #0bf;
			}
		}

		.switch-box{
			display: flex;
			justify-content: space-between;
			padding: 0 10px;
		}

		.imgs{
			li{
				width: 25%;
				float: left;
				padding: 6px;

				img{
					width: 100%;
					height: 100%;
					border: 3px solid #fff;
					border-radius: 10px;
				}
			}

			.active{
				img{
					background-color: #fff;
					border-color: #0bf;
				}
			}
		}
	}

	&.show{
		z-index: 999;
		width: 300px;

		.panel{
			display: block;
			position: absolute;
			top: 0;
			bottom: 0;
			background: #fff;
			padding: 20px 20px 20px 10px;

			ul{
				overflow: hidden;
			}
		}

		.mask{
			position: fixed;
			left: 0;
			top: 0;
			bottom: 0;
			// background: fuchsia;
			background: rgba(0,0,0,.2);
			right: 300px;
		}

		.show-panel{
			z-index: 1000;
		}
	}
}
</style>