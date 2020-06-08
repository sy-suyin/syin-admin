<template>
	<el-card class="indicator">
		<div class="gains" :class="classObject">
			<span>{{gains}}%</span>

			<svg v-if="!gains" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z"/><path d="M5 12h14"/></svg>

			<svg v-else-if="gains < 0" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z"/><path d="M3 7l6 6 4-4 8 8"/><path d="M21 10v7h-7"/></svg>

			<svg v-else xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z"/><path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>
		</div>
		<div class="num">
			{{num}}
		</div>
		<div class="remark">
			{{remark}}
		</div>
	</el-card>
</template>

<script>
export default {
	props: {
		gains: {
			type: Number,
			default: 0,
		},
		num: {
			type: Number,
			default: 0,
		},
		remark: {
			type: String,
			default: '',
		},
	},
	computed: {
		classObject: function () {
			let color = (!this.gains ? 'yellow' : (this.gains > 0 ? 'green' : 'red'));
			return 'text-' + color;
		}
	}
}
</script>

<style lang="scss">
.indicator{
	.gains{
		text-align: right;

		.icon{
			width: 16px;
			height: 16px;
			vertical-align: text-bottom;
			margin-left: 4px;
		}

		.text-green{
			color: #5eba00;
		}
	}

	.num{
		line-height: 1.1428571;
		font-size: 28px;
		color: #354052;
		text-align: center;
		font-weight: 500;
	}

	.remark{
		text-align: center;
		color: #6e7582;
		min-height: 22px;
	}
}

.text-green{
    color: #5eba00 !important;
}

.text-red{
    color: #cd201f!important;
}

.text-yellow{
	color: #fab005!important;
}
</style>