<template>
	<div>
		<ul class="menu-item-group" :class="'menu-group-level-'+level">
			<li class="menu-item" 
				v-for="(item, index) in menus" 
				:key=" 'item' + index" :data-index="index"  
				@click.stop="menuClick(index)"
				:class="{active: item.is_active}"
			>
				<div class="menu-link">
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
		menuClick(index){
			// 此处需加一个当重复点击不生效的
			let menu = this.menus[index];

			if(menu.children.length){
				menu.is_open = !menu.is_open;

				this.$set(this.menus, index, menu);
			}else{
				let current = this.$route.meta;

				if(current.controller != menu.controller || current.action != menu.action){
					this.$router.push(`/${menu.controller}/${menu.action}`);
				}else{
					// 此处待决定再重复点击之后是否刷新
				}
			}
		},

		/**
		 * 关闭侧边栏展开的菜单, 考虑到性能, 仅关闭最开始展开的一层
		 * 实现在侧边栏最小化, 以及最小化鼠标离开时关闭展开的侧边栏功能
		 */
		close(){
			if(this.level != 0){
				return false;
			}

			this.menus.forEach((item, index) => {
				if(item.is_open){
					this.menus[index].is_open = 0;
				} 
			});
		}
	}
}
</script>