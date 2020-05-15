<template>
	<div>
		<page-header></page-header>

		<div class="content-container">
			<el-card class="box-card">
				<div>
					<h4 class="detail-tilte">退款申请</h4>

					<el-row class="detail-content" :gutter="20">
						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">取货单号</span>
							<span class="detail-item-content">1000000000</span>
						</el-col>

						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">状态</span>
							<span class="detail-item-content">已取货</span>
						</el-col>

						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">销售单号</span>
							<span class="detail-item-content">1231231231</span>
						</el-col>

						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">子订单</span>
							<span class="detail-item-content">1231231231</span>
						</el-col>
					</el-row>
				</div>

				<el-divider></el-divider>

				<div>
					<h4 class="detail-tilte">用户信息</h4>

					<el-row class="detail-content" :gutter="20">
						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">用户姓名</span>
							<span class="detail-item-content">测试</span>
						</el-col>

						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">联系电话</span>
							<span class="detail-item-content">13400000000</span>
						</el-col>

						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">性别</span>
							<span class="detail-item-content">无</span>
						</el-col>

						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">取货地址</span>
							<span class="detail-item-content">甘肃省庆阳市西峰区温泉乡囫囵峁</span>
						</el-col>

						<el-col :span="8" class="detail-item">
							<span class="detail-item-label">备注</span>
							<span class="detail-item-content">无</span>
						</el-col>
					</el-row>
				</div>

				<el-divider></el-divider>

				<div class="table-box">
					<h4 class="detail-tilte">退货商品</h4>

					<el-table :data="return_goods" style="width: 100%" :header-cell-style="{color:'rgba(0,0,0,.85)'}">
						<el-table-column
							prop="id"
							label="商品编号"
							width="180">
						</el-table-column>

						<el-table-column
							prop="name"
							label="商品名称">
						</el-table-column>

						<el-table-column
							prop="barcode"
							label="商品条码">
						</el-table-column>

						<el-table-column
							prop="price"
							label="单价"
							:formatter="price_format">
						</el-table-column>

						<el-table-column
							prop="num"
							label="数量（件）">
						</el-table-column>

						<el-table-column
							prop="amount"
							label="金额"
							:formatter="price_format">
						</el-table-column>
					</el-table>
				</div>
				
				<div class="table-box">
					<h4 class="detail-tilte">退货进度</h4>

					<el-table :data="progress_data" style="width: 100%" :header-cell-style="{color:'rgba(0,0,0,.85)'}">
						<el-table-column
							prop="time"
							label="时间">
						</el-table-column>

						<el-table-column
							prop="progress"
							label="当前进度"
							width="180">
						</el-table-column>

						<el-table-column
							prop="status"
							label="状态">
						</el-table-column>

						<el-table-column
							prop="operator_id"
							label="操作员ID">
						</el-table-column>

						<el-table-column
							prop="consuming"
							label="耗时">
						</el-table-column>
					</el-table>
				</div>
			</el-card>
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common.js";

export default {
	name: 'profile_basic',
	mixins: [commonMixin],
	data(){
		return {
			return_goods: [
				{
					id: '1234561',
					name: '矿泉水 550ml',
					barcode: '12421432143214321',
					price: 2.00,
					num: 1,
					amount:2.00
				},
				{
					id: '1234562',
					name: '凉茶 300ml',
					barcode: '12421432143214322',
					price: 3.00,
					num: 2,
					amount:6.00
				},
				{
					id: '1234563',
					name: '好吃的薯片',
					barcode: '12421432143214323',
					price: 7.00,
					num: 4,
					amount:28.00
				},
				{
					id: '1234564',
					name: '特别好吃的蛋卷',
					barcode: '12421432143214324',
					price: 8.50,
					num: 3,
					amount:25.50
				}
			],

			progress_data: [
				{
					time: '2017-10-01 14:10',
					progress: '联系客户',
					status: '进行中',
					operator_id: '取货员 ID1234',
					consuming: '5mins',
				},
				{
					time: '2017-10-01 14:05',
					progress: '取货员出发',
					status: '成功',
					operator_id: '取货员 ID1234',
					consuming: '1h',
				},
				{
					time: '2017-10-01 13:05',
					progress: '取货员接单',
					status: '成功',
					operator_id: '取货员 ID1234',
					consuming: '5mins',
				},
				{
					time: '2017-10-01 13:00	',
					progress: '申请审批通过	',
					status: '成功',
					operator_id: '系统	',
					consuming: '1h',
				},
				{
					time: '2017-10-01 12:00	',
					progress: '发起退货申请',
					status: '成功',
					operator_id: '取货员 ID1234',
					consuming: '5mins',
				}
			]
		}
	},
	methods:{
		price_format(row, column, cellValue, index){
			return row[column.property].toFixed(2);
		}
	}
}
</script>

<style lang="scss">
	.detail-tilte{
		margin-bottom: 20px;
		color: rgba(0,0,0,.85);
		font-weight: 700;
		font-size: 16px;
		line-height: 1.5;
		margin-top: 0;
	}

	.detail-content{
		.detail-item{
			padding-bottom: 16px;

			.detail-item-label{
				color: rgba(0,0,0,.85);
				font-weight: 400;
				font-size: 14px;
				line-height: 1.5;
				white-space: nowrap;

				&:after {
					content: ":";
					position: relative;
					top: -.5px;
					margin: 0 8px 0 2px;
				}
			}

			.detail-item-content{
				color: rgba(0,0,0,.65);
				font-size: 14px;
				line-height: 1.5;
			}
		}
	}

	.el-divider{
		margin-bottom: 32px;
	}

	.table-box{
		margin-bottom: 32px;
	}
</style>