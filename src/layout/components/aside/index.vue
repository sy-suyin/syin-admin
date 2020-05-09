<template>
	<div class="layout-aside" :class="asideClass" @mouseleave="mouseleave" @mouseenter="mouseenter">
		<div class="slider">
			<el-scrollbar class="layout-aside-scroll">
				<logo></logo>

				<div class="user-info">
					<div class="user-avatar">
						<img :src="user.avatar" alt="">
					</div>
					<span class="user-name">{{user.name}}</span>
				</div>

				<!-- 侧边导航栏 -->
				<div class="nav">
					<menu-item :menus="menus" :lv="0" ref="menu_item"></menu-item>
				</div>

				<div class="background" :style="[backgroundStyle]"></div>
			</el-scrollbar>
		</div>
	</div>
</template>

<script>
import menuItem from "../menu-item";
import logo from "../logo";
import { debounce } from '@/libs/util.js';
import { mapState } from 'vuex'

export default {
	name: "layout-aside",
	components: {
		menuItem, logo
	},

	props: {
		user: {
			type: Object,
			default: function () {
				return {
					name: '',
					avatar: ''
				}
			}
		}
	},

	data(){
		return {
			is_mini: false,
			is_leave: false,
		}
	},

	created(){
		// 在初次加载时, 如果侧边栏为最小化, 则关闭展开的
		if(this.sidebar_mini){
			this.is_leave = true;
			this.closeMenu();
		}
	},

	methods:{
		mouseenter(){
			this.is_leave = false;
		},

		mouseleave(){
			this.is_leave = true;
			this.closeMenu();
		},

		closeMenu: debounce(300, function(){
			if(this.sidebar_mini && this.is_leave){
				this.$refs.menu_item.close()
			}
		})
	},

	computed: {
		menus: {
			get() {
				return this.$store.state.access.menus;
			}
		},

		...mapState('settings', {
			sidebar_mini: state =>state.sidebar_mini,
			filters_color: state =>state.sidebar_filters_color,
			background_project: state =>state.sidebar_background_project,
			background_img: state =>state.sidebar_background_img,
		}),

		asideClass(){
			let project_class = 'aside-' + this. background_project.class;

			return {
				'layout-aside-mini': this.sidebar_mini,
				[project_class]: true,
			}
		},

		backgroundStyle(){
			return {
				backgroundImage: 'url(' + this.background_img + ')',
			}
		},
	},
	watch: {
		sidebar_mini(val){
			if(true == val){
				this.is_leave = val;

				this.closeMenu();
			}
		}
	}
}
</script>