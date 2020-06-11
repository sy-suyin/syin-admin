<template>
	<div>
		<page-header></page-header>

		<div class="content-container">
			<el-card>
				<ul class="icon-list">
					<li v-for="item in icons" :key="item" class="icon-item">
						<svg-icon :icon="item" class-name="disabled"></svg-icon>

						<span>{{item}}</span>
					</li>
				</ul>
			</el-card>
		</div>
	</div>
</template>

<script>
import commonMixin from "@/mixins/common";
import svgIcon from '@/components/svg-icon';
import icons from './icons'

export default {
	name: 'icon',
	mixins: [commonMixin],
	components: { svgIcon },
	data(){
		return {
			icons
		}
	},
	mounted() {
	},
}
</script>

<style lang="scss">

.icon-list{
	display: flex;
	flex-wrap: wrap;

	.icon-item{
		width: 30px;
		font-size: 40px;
		flex-basis: 10%;
		text-align: center;
		padding: 20px 0;

		svg{
			display: block;
			margin: 0 auto;
		}

		span{
			display: block;
			font-size: 12px;
		}
	}
}
</style>>