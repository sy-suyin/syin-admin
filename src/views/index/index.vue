<template>
	<div>
		<div class="content-container">
			<el-row :gutter="20">
				<el-col :span="4">
					<el-card class="indicator">
						<div class="gains">
							<span>6%</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
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
							<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
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
							<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
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
							<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><polyline points="3 7 9 13 13 9 21 17"></polyline><polyline points="21 10 21 17 14 17"></polyline></svg>
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
						<barchart />
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<div slot="header" class="header">
							Development Activity
						</div>

						<mapchart />
					</el-card>
				</el-col>
			</el-row>

			<el-row :gutter="20">
				<el-col :span="12">
					<el-card>
						<div slot="header" class="header">
							Development Activity
						</div>

						<linechart />
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<div slot="header" class="header">
							Chart title
						</div>

						<piechart />
					</el-card>
				</el-col>
				<el-col :span="6">
					<el-card>
						<div slot="header" class="header">
							Chart title
						</div>

						<piechart2 />
					</el-card>
				</el-col>
			</el-row>

			<el-row :gutter="20">
				<el-col :span="6">
					<el-card body-style="padding-top: 0;min-height: 360px;">
						<div slot="header" class="header">
							Ranking
						</div>
							
						<el-table
							ref="table"
							:data="rankings"
							tooltip-effect="dark"
							style="width: 100%"
							:default-sort="{prop: 'num', order: 'descending'}"
						>
							<el-table-column prop="name" label="名称" width="120"></el-table-column>
							<el-table-column prop="num" label="数值" width="60"></el-table-column>
							<el-table-column>
								<template slot-scope="scope">
									<el-progress :text-inside="true" :stroke-width="6" :percentage="scope.row.progress" :show-text="false"></el-progress>
								</template>
							</el-table-column>
						</el-table>
					</el-card>
				</el-col>
				<el-col :span="18">
					<el-card body-style="padding-top: 0;min-height: 360px;">
						<div slot="header" class="header">
							Tasks
						</div>
						
						<el-table
							ref="table"
							:data="tasks"
							tooltip-effect="dark"
							style="width: 100%"
							:show-header="false"
						>
							<el-table-column type="selection" width="46" align="center"></el-table-column>

							<el-table-column prop="content" label="内容"></el-table-column>

							<el-table-column label="时间">
								<template slot-scope="scope">
									<i class="el-icon-date"></i>
									{{scope.row.time}}
								</template>
							</el-table-column>

							<el-table-column label="评论数">
								<template slot-scope="scope">
									<i class="el-icon-chat-line-square"></i>
									{{scope.row.comments}}
								</template>
							</el-table-column>
						</el-table>
					</el-card>
				</el-col>
			</el-row>
		</div>
	</div>
</template>

<script>
import * as util from '@/libs/util.js';
import {common as commonMixin} from "@/mixins/common.js";
import barchart from './components/barchart'
import mapchart from './components/mapchart'
import piechart from './components/piechart'
import piechart2 from './components/piechart2'
import linechart from './components/linechart'
import echarts from 'echarts'

export default {
	name: "home",
	mixins: [commonMixin],
	components: {barchart, mapchart, piechart, piechart2, linechart},
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

			rankings: [
				{
					name: '测试数据 1',
					num: 111,
					progress: 30,
				},
				{
					name: '测试数据 3',
					num: 525,
					progress: 56,
				},
				{
					name: '测试数据 2',
					num: 211,
					progress: 45,
				},
				{
					name: '测试数据 4',
					num: 921,
					progress: 64,
				},
				{
					name: '测试数据 5',
					num: 1056,
					progress: 72,
				},
				{
					name: '测试数据 6',
					num: 2020,
					progress: 92,
				}
			],

			tasks: [{
				content: 'There is no royal road to learning',
				time: 'May 01, 2020',
				comments: 10,
			}, {
				content: 'There is no royal road to learning',
				time: 'May 01, 2020',
				comments: 32,
			}, {
				content: 'Put the cart before the horse',
				time: 'April 27, 2020',
				comments: 54,
			}, {
				content: 'Everything must have a beginning',
				time: 'April 19, 2020',
				comments: 5,
			}, {
				content: 'Once a thief, always a thief',
				time: 'March 15, 2020',
				comments: 75,
			}, {
				content: 'One cannot put back the clock',
				time: 'March 12, 2020',
				comments: 43,
			}]
		}
	},
	methods: {
	}
};
</script>

<style lang="scss">
	.el-row{
		margin-bottom: 20px;
	}

	.indicator{
		.gains{
			text-align: right;

			.icon{
				width: 16px;
				height: 16px;
				vertical-align: text-bottom;
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

		.describe{
			text-align: center;
			color: #6e7582;
		}
	}
 </style>