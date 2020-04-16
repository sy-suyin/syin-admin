<template>
	<div>
		<ul class="menu-item-group" :class="'menu-group-level-'+level">
			<li class="menu-item" 
				v-for="(item, index) in menus" 
				:key=" item.key " :data-index="index"  
				:class="{active: item.is_active}"
			>
				<div 
					class="menu-link" 
					:style="{backgroundColor: (item.is_active ? filters_color : 'transparent')}"
					@click.stop="menuClick(index)"
				>
					<i class="menu-item-icon el-icon-s-grid" v-if="level< 1"></i>
					<span class="menu-name">
						{{item.name}}
					</span>

					<i class="menu-switch-icon el-icon-arrow-up" v-if="item.children.length" :class="{'menu-show-icon': item.is_open}"></i>
				</div>

				<sy-menu-item v-if="item.children.length" :menus="item.children" v-show="item.is_open" :lv="level+1"></sy-menu-item>
			</li>
		</ul>
	</div>
</template>

<script>
import { mapState } from 'vuex'

export default {
	name: 'sy-menu-item',
	props: ['menus', 'lv'],
	data(){
		return {
			level: 0,
		}
	},
	created(){
		this.level = +this.lv || 0;
	},
	methods:{
		/**
		 * 菜单栏点击事件
		 */
		menuClick(index){
			// 此处需加一个当重复点击不生效的
			let menu = this.menus[index];

			if(menu.children.length){
				menu.is_open = !menu.is_open;

				this.$set(this.menus, index, menu);
			}else{
				let current = this.$route.meta;

				if(current.controller != menu.controller || current.action != menu.action){
					// 激活路由
					this.$store.commit('access/active', menu);
					this.$router.push(`/${menu.controller}/${menu.action}`);
				}else{
					// 此处待决定再重复点击之后是否刷新
				}
			}
		},

		/**
		 * 关闭打开的菜单栏
		 */
		close(){
			this.menus.forEach((item, index) => {
				if(item.is_open){
					this.menus[index].is_open = 0;
				} 
			});
		}
	},
	computed: {
		...mapState('settings', {
			filters_color: state =>state.sidebar_filters_color,
			background_color: state =>state.sidebar_background_color,
		})
	},
}
</script>