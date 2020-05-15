<template>
  <div class="echarts">
    <div :style="{height:'300px',width:'100%'}" ref="myEchart"></div>
  </div>
</template>
<script>
// 代码来源 
// https://juejin.im/post/5b7e1d7151882542ad05844e
// https://my.oschina.net/xuexipython/blog/967514

import echarts from "echarts";
import 'echarts/map/js/china.js' // 引入中国地图数据

export default {
    props: ["renderData"],
    data() {
        return {
            chart: null
        };
    },
    mounted() {
        this.initEchartMap();
    },
    beforeDestroy() {
        if (!this.chart) {
            return;
        }
        this.chart.dispose();
        this.chart = null;
    },
    methods: {
        initEchartMap() {
            //这里我们用固定的数据，真正使用时，用父组件传递来的数据替换series即可
            let myChart = echarts.init(this.$refs.myEchart);     
            window.onresize = myChart.resize;
            myChart.setOption({ 
                // backgroundColor: "#0b1225",
                title: {
                    // text: '每日货盘运行图',
                    left: 'center',
                    textStyle: {
                        color: '#fff'
                    }
                },
                tooltip : {
                    trigger: 'item'
                },
                dataRange: {
                    show: false,
                    min: 0,
                    max: 1000,
                    text: ['High', 'Low'],
                    realtime: true,
                    calculable: true,
                    color: ['orangered', 'yellow', 'lightskyblue']
                },
                visualMap : {
                    show : true,
                    min : 0,
                    max : 255,
                    calculable : true,
                    inRange : {
                        color : ['aqua', 'lime', 'yellow', 'orange', '#ff3333']
                    },
                    textStyle : {
                        color : '#12223b'
                    }
                }, 
                series: [{
                    type: "map", 
                    mapType: 'china',
                    layoutCenter : ['50%', '50%'],
                    layoutSize : "130%",
                        roam: true,
                        label: {
                            emphasis: {
                                show: false
                            },
                            normal: {
                                show: true, // 是否显示对应地名
                                textStyle: {
                                    color: 'rgba(0,0,0,0.4)'
                                },
                                areaColor: '#37376e',
                                borderColor: 'rgba(0, 0, 0, 0.2)'
                            },
                        },
                        "data": [
                            { name: '北京', value: 0 },
                            { name: '天津', value: 0 },
                            { name: '上海', value: 0 },
                            { name: '重庆', value: 0 },
                            { name: '河北', value: 0 },
                            { name: '河南', value: 0 },
                            { name: '云南', value: 0 },
                            { name: '辽宁', value: 0 },
                            { name: '黑龙江', value: 0 },
                            { name: '湖南', value: 0 },
                            { name: '安徽', value: 0 },
                            { name: '山东', value: 0 },
                            { name: '新疆', value: 0 },
                            { name: '江苏', value: 0 },
                            { name: '浙江', value: 0 },
                            { name: '江西', value: 0 },
                            { name: '湖北', value: 0 },
                            { name: '广西', value: 0 },
                            { name: '甘肃', value: 0 },
                            { name: '山西', value: 0 },
                            { name: '内蒙古', value: 0 },
                            { name: '陕西', value: 0 },
                            { name: '吉林', value: 0 },
                            { name: '福建', value: 0 },
                            { name: '贵州', value: 0 },
                            { name: '广东', value: 0 },
                            { name: '青海', value: 0 },
                            { name: '西藏', value: 0 },
                            { name: '四川', value: 0 },
                            { name: '宁夏', value: 0 },
                            { name: '海南', value: 0 },
                            { name: '台湾', value: 0 },
                            { name: '香港', value: 0 },
                            { name: '澳门', value: 0 }
                        ]
                    },
                ]
            })
        }
    }
}
</script>