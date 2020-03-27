<template>
	<div>
		<div class="right-panel show">
			<div class="show-panel" @click="show">
				<i class="icon el-icon-setting"></i>
			</div>
			<div class="panel">
				<h2></h2>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data(){
		return {
			is_show: true
		}
	},
	methods:{
		show(){
			console.log('show');
		}
	}
}
</script>

<style lang="scss">
.right-panel{
	position: fixed;
	right: 0;
	top: 0;
	bottom: 0;

	.show-panel{
		position: absolute;
		top: 215px;
		width: 48px;
		height: 48px;
		// background: rgba(0,0,0,.3);
		background: #1890FF;
		text-align: center;
		border-radius: 6px 0 0 6px;
		left: -48px;

		.icon{
			font-size: 24px;
			color: #fff;
			line-height: 48px;
			// width: 48px;
		}
	}

	&.show{
		width: 260px;
		.panel{
			position: absolute;
			width: 260px;
			top: 0;
			bottom: 0;
			background: crimson;
		}

		&::after{
			z-index: 9999999;
			content: '';
			position: fixed;
			left: 0;
			top: 0;
			bottom: 0;
			background: fuchsia;
			right: 260px;
		}
	}
	// display: flex;
	
}
</style>