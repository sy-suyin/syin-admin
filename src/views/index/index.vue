<template>
	<div>
		<div class="content-container">
			<el-row :gutter="20">
				<el-col :span="4">
					<el-card class="indicator">
						<div class="gains">
							<span>6%</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon ml-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
						</div>
						<div class="num">
							95
						</div>
						<div class="describe">
							New Tickets
						</div>
					</el-card>
				</el-col>
				<el-col :span="4">
					<el-card class="indicator">
						<div class="gains">
							<span>6%</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon ml-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
						</div>
						<div class="num">
							95
						</div>
						<div class="describe">
							New Tickets
						</div>
					</el-card>
				</el-col>
				<el-col :span="4">
					<el-card class="indicator">
						<div class="gains">
							<span>6%</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon ml-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
						</div>
						<div class="num">
							95
						</div>
						<div class="describe">
							New Tickets
						</div>
					</el-card>
				</el-col>
				<el-col :span="4">
					<el-card class="indicator">
						<div class="gains">
							<span>6%</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon ml-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
						</div>
						<div class="num">
							95
						</div>
						<div class="describe">
							New Tickets
						</div>
					</el-card>
				</el-col>
				<el-col :span="4">
					<el-card class="indicator">
						<div class="gains">
							<span>6%</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon ml-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
						</div>
						<div class="num">
							95
						</div>
						<div class="describe">
							New Tickets
						</div>
					</el-card>
				</el-col>
				<el-col :span="4">
					<el-card class="indicator">
						<div class="gains">
							<span>6%</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon ml-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
						</div>
						<div class="num">
							95
						</div>
						<div class="describe">
							New Tickets
						</div>
					</el-card>
				</el-col>
			</el-row>

			<el-row :gutter="20">
				<el-col :span="18">
					<el-card>
						<div slot="header" class="header">
							Development Activity
						</div>
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<div slot="header" class="header">
							Development Activity
						</div>
					</el-card>
				</el-col>
			</el-row>

			<el-row :gutter="20">
				<el-col :span="12">
					<el-card>
						<div slot="header" class="header">
							Development Activity
						</div>
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<div slot="header" class="header">
							Chart title
						</div>
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<div slot="header" class="header">
							Chart title
						</div>
					</el-card>
				</el-col>
			</el-row>

			<el-row :gutter="20">
				<el-col :span="6">
					<el-card>
						<div slot="header" class="header">
							Social Media Traffic
						</div>
					</el-card>
				</el-col>
				<el-col :span="18">
					<el-card>
						<div slot="header" class="header">
							Tasks
						</div>


					<!-- 						
						<el-table
							ref="table"
							:data="results"
							tooltip-effect="dark"
							style="width: 100%"
							@selection-change="selectionChange"
						>
							<el-table-column type="selection" width="46" align="center"></el-table-column>

							<el-table-column label="排序" width="86">
								<template slot-scope="{row}">
									<el-input v-model="row.sort" type="number" size="mini" max="99" min="0"/>
								</template>
							</el-table-column>

							<el-table-column prop="id" label="编号" width="60"></el-table-column>

							<el-table-column prop="name" label="名称" width="200"></el-table-column>

							<el-table-column prop="login_name" label="登录账号"></el-table-column>

							<el-table-column label="角色">
								<template slot-scope="scope">
									<el-tag class="role-tag" effect="plain" type="info" size="mini" v-for="item in scope.row.roles" :key="item.id">{{item.name}}</el-tag>
								</template>
							</el-table-column>

							<el-table-column prop="add_time" label="添加时间" width="180"></el-table-column>

							<el-table-column align="right" label="操作">
								<template slot-scope="scope">
									<el-button
										size="mini" type="text" 
										@click="jump('edit', {id: scope.row.id})"
										v-permission:page="['system', 'adminedit']"
									>修改</el-button>

									<el-divider direction="vertical"></el-divider>

									<el-button
										size="mini"
										type="text" 
										@click="del(scope.row.id, 1)"
										v-permission:page="['system', 'admindel']"
									>删除</el-button>
								</template>
							</el-table-column>
						</el-table>		 -->


						<table>
							<tbody>
								<tr>
									<td>Extend the data mode</td>
									<td>January 01, 2019</td>
									<td>3/10</td>
									<td>3</td>
								</tr>
								<tr>
									<td>Verify the event flow.</td>
									<td>January 01, 2019</td>
									<td>3/10</td>
									<td>3</td>
								</tr>
								<tr>
									<td>Extend the data mode</td>
									<td>January 01, 2019</td>
									<td>3/10</td>
									<td>3</td>
								</tr>
								<tr>
									<td>Extend the data mode</td>
									<td>January 01, 2019</td>
									<td>3/10</td>
									<td>3</td>
								</tr>
								<tr>
									<td>Extend the data mode</td>
									<td>January 01, 2019</td>
									<td>3/10</td>
									<td>3</td>
								</tr>
							</tbody>
						</table>
					</el-card>
				</el-col>
			</el-row>
		</div>
	</div>
</template>

<script>
import * as util from '@/libs/util.js';
import {common as commonMixin} from "@/mixins/common.js";
import echarts from 'echarts'

export default {
	name: "home",
	mixins: [commonMixin],
	mounted(){
	},
  	data() {
      	return {
        	form: {
          		name: '',
				region: '',
				date1: '',
				date2: '',
				delivery: false,
				type: [],
				resource: '',
				desc: ''
			},

			tasks: [
			]
		}
	},
	methods: {
		initChart(){
			      this.chart = echarts.init(this.$el, 'macarons')

			this.chart.setOption({
				tooltip: {
				trigger: 'item',
				formatter: '{a} <br/>{b} : {c} ({d}%)'
				},
				legend: {
				left: 'center',
				bottom: '10',
				data: ['Industries', 'Technology', 'Forex', 'Gold', 'Forecasts']
				},
				series: [
				{
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
				}
				]
			});
		}
	}
};
</script>

<style lang="scss">
	.el-row{
		margin-bottom: 20px;
	}

	// .el-col{
	// 	padding: 0 10px;
	// }

	.indicator{
		.gains{

		}

		.num{
			line-height: 1.1428571;
			font-size: 28px;
			color: #354052;
			text-align: center;
			font-weight: 500;
		}

		.describe{

		}
	}
 </style>