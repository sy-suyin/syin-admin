<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme

const animationDuration = 6000

export default {
	name: 'barchart',
	props: {
		className: {
			type: String,
			default: 'chart'
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
					trigger: 'axis',
					axisPointer: { // 坐标轴指示器，坐标轴触发有效
						type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
					}
				},
				grid: {
					top: 10,
					left: '2%',
					right: '2%',
					bottom: '3%',
					containLabel: true
				},
				xAxis: [{
					type: 'category',
					data: ['10 apr', '11 apr', '12 apr', '13 apr', '14 apr', '15 apr', '16 apr', '17 apr', '18 apr', '19 apr', '20 apr', '21 apr', '22 apr', '23 apr', '24 apr', '25 apr', '26 apr', '27 apr', '28 apr', '29 apr', '30 apr'],
					axisTick: {
						alignWithLabel: true
					}
				}],
				yAxis: [{
					type: 'value',
					axisTick: {
						show: false
					}
				}],
				series: [{
					name: 'coding',
					type: 'bar',
					stack: 'vistors',
					barWidth: '60%',
					color: '#206BC4',
					data: [12, 12, 21, 25, 26, 27, 31, 32, 32, 28, 27, 35, 32, 36, 12, 9, 11, 12, 5, 25, 26],
					animationDuration
				}, {
					name: 'github',
					type: 'bar',
					stack: 'vistors',
					barWidth: '60%',
					color: '#79A6DC',
					data: [25, 27, 27, 31, 22, 12, 17, 19, 21, 12, 10, 5, 7, 11, 18, 19, 21, 22, 25, 20, 20],
					animationDuration
				}, {
					name: 'testing',
					type: 'bar',
					stack: 'vistors',
					barWidth: '60%',
					color: '#BFE399',
					data: [30, 28, 27, 25, 26, 21, 20, 18, 19, 20, 16, 14, 12, 12, 12, 10, 9, 8, 7, 8, 8],
					animationDuration
				}]
			})
		}
	}
}
</script>
