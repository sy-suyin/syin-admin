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


				<sy-menu-item v-if="item.has_children" :level="level+1" :parent="item"></sy-menu-item>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	name: 'sy-menu-item',
	props: ['level', 'parent'],
	data(){
		return {
			menus: [],
		}
	},
	created(){
		let menus = this.$store.state.access.menus;
		this.level = +level || 0;
		let current_menus = null;
		
		if(level < 1){
			this.menus = menus[level];
		}else{
			this.menus = menus[level][parent.controller+'_'+parent.action];
		}

	},
}
</script>