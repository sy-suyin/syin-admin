<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme

// 代码参考
// https://segmentfault.com/a/1190000022096665
// https://juejin.im/post/5d88a77f6fb9a06acf2b98f8

export default {
	name: 'linechart',
	props: {
		className: {
			type: String,
			default: 'line'
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
			this.initChart();
		})
	},
	beforeDestroy() {
		if (!this.chart) {
			return;
		}
		this.chart.dispose();
		this.chart = null;
	},
	methods: {
		initChart() {
			this.chart = echarts.init(this.$el, 'macarons');

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
					type: 'category',
                    boundaryGap: false,
                    data: ['周一','周二','周三','周四','周五','周六','周日','周八'] // 横坐标都一样。故取默认第一个
				},
				yAxis: {
					splitLine: {
						show: false
					}
				},
				series: [{
					name: '数据源一',
					type: 'line',
					smooth: true, // 平滑
					itemStyle : {
						normal : {
							color: '#456ef4', // 设置折线折点颜色
							lineStyle:{  
								color: '#456ef4'  // 设置折线线条颜色
							}
						}
					},
					data: [120, 132, 101, 134, 90, 230, 210, 123]
				}, {
					name: '数据源二',
					type: 'line',
					smooth: true, // 平滑
					itemStyle : {
						normal : {
							color: '#3fe0c2', // 设置折线折点颜色
							lineStyle:{  
								color: '#3fe0c2'  // 设置折线线条颜色
							}
						}
					},
					data: [1210, 1132, 1101, 1134, 910, 2310, 2110, 1123]
				}]
			});
		}
	}
}
</script>
