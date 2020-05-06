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

				<menu-item v-if="item.children.length" :menus="item.children" v-show="item.is_open" :lv="level+1"></menu-item>
			</li>
		</ul>
	</div>
</template>

<script>
import { mapState } from 'vuex'

export default {
	name: 'menu-item',
	props: ['menus', 'lv'],
	data(){
		return {
			level: 0,
			// 此处记录渲染数据中打开项, 以供在每次点击切换页面后, 重新渲染时能保持上次的打开状态
			opens: {}
		}
	},
	created(){
		this.level = +this.lv || 0;

		this.menus.forEach(val=>{
			if(val.hasOwnProperty('is_open') && !!val.is_open){
				this.opens[val.key] = true;
			}
		});
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
				this.opens[menu.key] = menu.is_open;

				this.$set(this.menus, index, menu);
			}else{
				let current = this.$route.meta;

				if(current.controller != menu.controller || current.action != menu.action){
					// 激活路由
					this.opens[menu.key] = true;
					// 触发路由改变
					this.$event.emit('routeChange', menu.name, menu);
					// 跳转到相应页面
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
	watch: {
		menus(data){
			let flag = true;

			// 如果需要每次点击切换时, 关闭其他已打开的, 只需注释下面代码即可

			data.forEach((val, key) => {
				// 如果新记录中是未打开, 而旧记录是已打开, 则修改为已打开
				if( !val.is_open 
					&&this.opens.hasOwnProperty(val.key)
					&& this.opens[val.key] 
				){
					flag = true;
					data[key].is_open = true; 
				}

				// 在手动切换的情况下不存在子项已打开, 而上级菜单未打开的情况. 故仅在菜单点击时添加新打开记录, 而不在此处判断有无新上级打开记录
			});

			if(flag){
				this.menus = data;
			}
		}
	}
}
</script>