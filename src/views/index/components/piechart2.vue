<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts'
require('echarts/theme/macarons') // echarts theme

const animationDuration = 6000

export default {
	name: 'piechart2',
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
				legend: {
					bottom: 30,
					left: "center",
					data: ["及格人数", "未及格人数"],
				},
				series: [
					{
					name: "状态",
					type: "pie",
					radius: "65%",
					center: ["50%", "40%"],
					avoidLabelOverlap: false,
					itemStyle: {
						emphasis: {
							shadowBlur: 10,
							shadowOffsetX: 0,
							shadowColor: "rgba(0, 0, 0, 0.5)"
						},
						color: function(params) {
							//自定义颜色
							var colorList = ["#1ab394", "#79d2c0"];
							return colorList[params.dataIndex];
						}
					},
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
					},
					data: [
						{ value: 12, name: "及格人数", itemStyle: "#1ab394" },
						{ value: 18, name: "未及格人数", itemStyle: "#79d2c0" }
					]
				}]
			})
		}
	}
}
</script>
