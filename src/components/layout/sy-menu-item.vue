<template>
	<div>
		<ul class="menu-item-group">
			<li class="menu-item" v-for="(item, index) in menus" :key=" 'item' + index" :data-index="index"  @click.stop="menuClick(index)">
				<div class="menu-link">
					<span>
						<i class="el-icon-setting"></i>
						{{item.name}}
					</span>

					<i class="switch el-icon-arrow-up" v-if="item.has_children"></i>
				</div>

				<sy-menu-item v-if="item.has_children" :lv="level+1" :root="item" v-show="item.is_open"></sy-menu-item>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	name: 'sy-menu-item',
	props: ['lv', 'root'],
	data(){
		return {
			level: 0,
			menus: [],
		}
	},
	created(){
		let menus = this.$store.state.access.menus_active;
		let level = +this.lv || 0;
		let current_menus = null;
		
		if(level < 1){
			this.menus = menus[level];
		}else{
			this.menus = menus[level][this.root.controller+'_'+this.root.action];
		}

		this.level = level;
	},

	methods:{
		menuClick(index){
			let menu = this.menus[index];

			if(menu.has_children){
				menu.is_open = !menu.is_open;

				this.$set(this.menus, index, menu);
			}else{
				this.$router.push(`/${menu.controller}/${menu.action}`);
			}
		}
	}
}
</script>