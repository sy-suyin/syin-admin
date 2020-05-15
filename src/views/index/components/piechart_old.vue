<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme

const animationDuration = 6000

export default {
	name: 'piechart',
	props: {
		className: {
			type: String,
			default: 'pie'
		},
		width: {
			type: String,
			default: '100%'
		},
		height: {
			type: String,
			default: '300px'
		}
	},
	data() {
		return {
		chart: null
		}
	},
	mounted() {
		this.$nextTick(() => {
		this.initChart()
		})
	},
	beforeDestroy() {
		if (!this.chart) {
		return
		}
		this.chart.dispose()
		this.chart = null
	},
	methods: {
		initChart() {
			this.chart = echarts.init(this.$el, 'macarons')

			this.chart.setOption({
				tooltip: {
					trigger: 'item',
					// formatter: '{a} <br/>{b} : {c} ({d}%)'
				},
				grid: {
					top: 10,
					left: '2%',
					right: '2%',
					bottom: '3%',
					containLabel: true
				},
				series: [{
					name: 'WEEKLY WRITE ARTICLES',
					type: 'pie',
					roseType: 'radius',
					radius: [15, 95],
					center: ['50%', '38%'],
					data: [
					{ value: 320, name: 'Industries' },
					{ value: 240, name: 'Technology' },
					{ value: 149, name: 'Forex' },
					{ value: 100, name: 'Gold' },
					{ value: 59, name: 'Forecasts' }
					],
					animationEasing: 'cubicInOut',
					animationDuration: 2600
				}]
			})
		}
	}
}
</script>
