<template>
	<div>
		<div class="setting-panel" :class="{show: is_show}">
			<div class="show-panel" @click="is_show=!is_show">
				<i class="icon el-icon-setting"></i>
			</div>

			<div class="panel">
				<h4 class="header-title">SIDEBAR FILTERS</h4>

				<div class="sidebar-filter color-badge">
					<span class="badge" :style="'background-color: '+ val" v-for="(val, index) in sidebar_filters" :key="index" :class="{active: index==selected_index.filter}" @click="filterClick(index)"></span>
				</div>

				<el-divider></el-divider>

				<h4 class="header-title">SIDEBAR BACKGROUND</h4>

				<div class="sidebar-filter color-badge">
					<span class="badge" :style="'background-color: '+ val" v-for="(val, index) in background_colors" :key="index" :class="{active: index==selected_index.bg_color}" @click="bgColorClick(index)"></span>
				</div>

				<el-divider></el-divider>

				<div class="switch-box">
					Sidebar Mini

					<el-switch
						v-model="sidebarMini"
						active-color="#13ce66"
						inactive-color="#ff4949">
					</el-switch>
				</div>

				<el-divider></el-divider>

				<h4 class="header-title">IMAGES</h4>

				<ul class="imgs">
					<li v-for="(img, index) in sidebar_background_imgs" :key="index" :class="{active: index==selected_index.bg_img}" @click="bgImgClick(index)">
						<img :src="img">
					</li>
				</ul>
			</div>

			<div class="mask" @click="is_show=!is_show"></div>
		</div>
	</div>
</template>

<script>
import { mapState } from 'vuex'

export default {
	data(){
		return {
			is_show: false,
			is_mini: false,

			sidebar_filters: [
				'#9368e9',
				'#2ca8ff',
				'#0bf',
				'#18ce0f',
				'#f44336',
				'#e91e63',
			],

			background_colors: [
				'#000',
				'#fff',
				'#f44336',
			],

			selected_index: {
				filter: 0,
				bg_color: 0,
				bg_img: 0,
			},
		}
	},
	mounted(){
		this.init();
	},
	methods:{
		init(){
			if(this.sidebar_filters_color == ''){
				this.filterClick(0);
			}else{
				this.sidebar_filters.forEach((color, index) => {
					if(color == this.sidebar_filters_color){
						this.selected_index.filter = index;
					}
				});
			}

			if(this.sidebar_background_color == ''){
				this.bgColorClick(0);
			}else{
				this.background_colors.forEach((color, index) => {
					if(color == this.sidebar_background_color){
						this.selected_index.bg_color = index;
					}
				});
			}
			
			if(this.sidebar_background_img == ''){
				this.bgImgClick(0);
			}else{
				this.sidebar_background_imgs.forEach((color, index) => {
					if(color == this.sidebar_background_img){
						this.selected_index.bg_img = index;
					}
				});
			}
		},
		filterClick(index){
			this.selected_index.filter = index;
			this.$store.dispatch('settings/changeSetting', {
				key: 'sidebar_filters_color',
				value: this.sidebar_filters[index]
			});
		},
		bgColorClick(index){
			this.selected_index.bg_color = index;
			this.$store.dispatch('settings/changeSetting', {
				key: 'sidebar_background_color',
				value: this.background_colors[index]
			});
		},
		bgImgClick(index){
			this.selected_index.bg_img = index;
			this.$store.dispatch('settings/changeSetting', {
				key: 'sidebar_background_img',
				value: this.sidebar_background_imgs[index]
			});
		},
	},
	computed: {
		sidebarMini: {
			get() {
				return this.$store.state.settings.sidebar_mini;
			},
			set(val) {
				this.$store.dispatch('settings/changeSetting', {
					key: 'sidebar_mini',
					value: val
				});
			}
		},
		...mapState('settings', {
			sidebar_filters_color: state =>state.sidebar_filters_color,
			sidebar_background_color: state =>state.sidebar_background_color,
			sidebar_background_img: state =>state.sidebar_background_img,
			sidebar_background_imgs: state =>state.sidebar_background_imgs,
		})
	}
}
</script>

<style lang="scss">
.setting-panel{
	position: fixed;
	right: -294px;
	top: 0;
	bottom: 0;
	transition: width 2s;
	width: 300px;
	transition: all .5s ease;

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
		position: absolute;
		top: 0;
		bottom: 0;
		background: #fff;
		padding: 20px 20px 20px 10px;

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
		right: 0;
		z-index: 999;

		.panel{
			z-index: 1000;
			border-left: 1px solid #dcdfe6;
		}

		.mask{
			position: fixed;
			left: 0;
			top: 0;
			bottom: 0;
			background: rgba(0,0,0,.2);
			right: 0;
		}

		.show-panel{
			z-index: 1000;
		}
	}

	.slide-fade-enter-active {
		transition: all .3s ease;
	}

	.slide-fade-leave-active {
		transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
	}

	.slide-fade-enter, .slide-fade-leave-to {
		transform: translateX(10px);
		opacity: 0;
	}
}
</style>