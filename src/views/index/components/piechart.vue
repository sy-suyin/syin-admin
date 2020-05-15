<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme

// 代码参考
// https://blog.csdn.net/qweasdzxc_1092665276/article/details/85000602

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
				grid: {
					top: 10,
					left: '2%',
					right: '2%',
					bottom: '3%',
					containLabel: true
				},
				legend: {
					data: ['直接访问', '邮件营销', '联盟广告', '视频广告', '搜索引擎'],
					left:"center",
					top:"bottom",
					orient:"horizontal",
				},
				label: {
					normal: {
						show: true,
						position: 'inside',
						formatter: '{d}%',//模板变量有 {a}、{b}、{c}、{d}，分别表示系列名，数据名，数据值，百分比。{d}数据会根据value值计算百分比

						textStyle : {
							align : 'center',
							baseline : 'middle',
							fontFamily : '微软雅黑',
							fontSize : 15,
							fontWeight : 'bolder'
						}
					},
				},
				series: [
					{
						name: '访问来源',
						type: 'pie',
						radius: ['50%', '70%'],
						center: ['50%', '40%'],
						data: [
							{value: 335, name: '直接访问'},
							{value: 310, name: '邮件营销'},
							{value: 234, name: '联盟广告'},
							{value: 135, name: '视频广告'},
							{value: 1548, name: '搜索引擎'}
						],
						animationEasing: 'cubicInOut',
						animationDuration: 2600,
						label: {
							normal: {
								show: true,
								position: 'inside',
								formatter: '{d}%',//模板变量有 {a}、{b}、{c}、{d}，分别表示系列名，数据名，数据值，百分比。{d}数据会根据value值计算百分比
								textStyle : {                   
									align : 'center',
									baseline : 'middle',
									fontFamily : '微软雅黑',
									fontSize : 15,
									fontWeight : 'bolder'
								}
							},
							emphasis: {
								show: true,
								position: "right",
								offset: [30, 40],
								formatter: '{b} : {c} ({d}%)',
								textStyle: {
									color: "#333",
									fontSize: 18,
								}
							}
						}
					}
				]
			})
		}
	}
}
</script>
