<template>
	<div>
		<ul class="menu-item-group">
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

					<i class="menu-switch-icon el-icon-arrow-up" v-if="item.has_children" :class="{'menu-show-icon': item.is_open}"></i>
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

<style lang="scss">
.menu-item-group{	
	.menu-item{
		margin: 10px 15px 0;
		// height: 46px;

		&.active{
			&>.menu-link{
				background-color: #e91e63;
				box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(233,30,99,.4);
			}

			.active>.menu-link{
				margin-top: 10px;
			}
		}
		

		.menu-link{
			display: flex;
			cursor: pointer;
			align-items: center;
			height: 46px;
			padding: 0 10px;
			border-radius: 4px;

			.menu-item-icon{
				font-size: 20px;
				margin-right: 12px;
			}

			.menu-name{
				flex-grow: 1;
			}

			.menu-switch-icon{
				transition: transform .3s;
				font-size: 16px;
				// float: right;
			}

			.menu-show-icon{
				transform: rotate(180deg);
			}
		}

		/* .switch{
			float: right;
		} */

		.menu-item-group{
			// margin-top: 10px;

			.menu-item{
				margin: 0px 0 6px 16px;
				padding-right: 0;

				&:last-child{
					padding-bottom: 0;
				}

				.menu-link{
					height: 36px;
				}
			}
		}
	}
}
</style>