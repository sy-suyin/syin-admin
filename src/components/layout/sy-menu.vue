<template>
	<div>
		<sy-menu-item :menus="menus" :lv="0"></sy-menu-item>
	</div>
</template>

<script>
import syMenuItem from "@/components/layout/sy-menu-item.vue";

export default {
	name: 'sy-menu',
	components: {
		syMenuItem
	},
	data(){
		return {
			menus: [],
		}
	},
	created(){
		this.menus = this.$store.state.access.menus;
	},
	methods:{
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