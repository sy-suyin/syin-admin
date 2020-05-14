<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme

const animationDuration = 6000

export default {
	name: 'linechart',
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
				xAxis: {
				},
				yAxis: {
					splitLine: {
						show: false
					}
				},
				visualMap: {
					top: 10,
					right: 10,
					pieces: [{
						gt: 0,
						lte: 50,
						color: '#096'
					}, {
						gt: 50,
						lte: 100,
						color: '#ffde33'
					}, {
						gt: 100,
						lte: 150,
						color: '#ff9933'
					}, {
						gt: 150,
						lte: 200,
						color: '#cc0033'
					}, {
						gt: 200,
						lte: 300,
						color: '#660099'
					}, {
						gt: 300,
						color: '#7e0023'
					}],
					outOfRange: {
						color: '#999'
					}
				},
				series: [{
					name: 'expected', itemStyle: {
						normal: {
						color: '#FF005A',
						lineStyle: {
							color: '#FF005A',
							width: 2
						}
						}
					},
					smooth: true,
					type: 'line',
					// data: expectedData,
					animationDuration: 2800,
					animationEasing: 'cubicInOut'
					},
					{
					name: 'actual',
					smooth: true,
					type: 'line',
					itemStyle: {
						normal: {
							color: '#3888fa',
							lineStyle: {
								color: '#3888fa',
								width: 2
							},
							areaStyle: {
								color: '#f3f8ff'
							}
						}
					},
					markLine: {
						silent: true,
						data: [{
							yAxis: 50
						}, {
							yAxis: 100
						}, {
							yAxis: 150
						}, {
							yAxis: 200
						}, {
							yAxis: 300
						}]
					},
					// data: actualData,
					animationDuration: 2800,
					animationEasing: 'quadraticOut'
				}]
			})
		}
	}
}
</script>
