<template>
	<div>
		<page-header>
			<div slot="breadcrumb-after">
				<div class="page-header-heading">
					<div>
						<h1>单号: 12312312412</h1>
					</div>
					<div>
						操作1
						操作2
					</div>
				</div>
				<div class="page-header-content">
					<div class="page-header-content-detail">
						<table>
							<tbody>
								<tr>
									<td>
										<span>创建人</span>
										<span>test</span>
									</td>
									<td>
										<span>订购产品</span>
										<span>xxxx</span>
									</td>
								</tr>
								<tr>
									<td>
										<span>创建时间</span>
										<span>test</span>
									</td>
									<td>
										<span>关联单据</span>
										<span>xxxx</span>
									</td>
								</tr>
								<tr>
									<td>
										<span>生效日期</span>
										<span>test</span>
									</td>
									<td>
										<span>备注</span>
										<span>xxxx</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="page-header-content-extra">
						<div class="extra-statistic">
							<div class="statistic">
								<div class="statistic-title">状态</div>
								<div class="statistic-content">
									<span>待审批</span>
								</div>
							</div>
							<div class="statistic">
								<div class="statistic-title">订单金额</div>
								<div class="statistic-content">
									<span>¥</span>
									<span>
										<span>568</span>
										<span>.08</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</page-header>

		<div class="content-container">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					<span>流程进度</span>
				</div>
				<div>
					<el-steps :active="2" align-center>
						<el-step title="创建项目" description="项目开始时间"></el-step>
						<el-step title="步骤2" description="这是一段很长很长很长的描述性文字"></el-step>
						<el-step title="步骤3" description="这是一段很长很长很长的描述性文字"></el-step>
						<el-step title="步骤4" description="这是一段很长很长很长的描述性文字"></el-step>
					</el-steps>
				</div>
			</el-card>

			<el-card class="box-card">
				<div slot="header" class="clearfix">
					<span>用户信息</span>
				</div>

				<table class="info-table">
					<tbody>
						<tr>
							<td>
								<span class="item-label">用户姓名</span>
								<span class="item-content">测试</span>
							</td>
							<td>
								<span class="item-label">会员卡号</span>
								<span class="item-content">32943898021309809423	</span>
							</td>
							<td>
								<span>会员卡号</span>
								<span>32943898021309809423	</span>
							</td>
						</tr>
					</tbody>
				</table>
			</el-card>

			<el-card class="box-card">
				<el-tabs v-model="tab_val">
					<el-tab-pane label="操作记录一" name="first">
						<el-table
							ref="table"
							:data="logs1"
							tooltip-effect="dark"
							style="width: 100%"
						>
							<el-table-column prop="type" label="操作类型"></el-table-column>

							<el-table-column prop="operator" label="操作人"></el-table-column>

							<el-table-column prop="result" label="执行结果"></el-table-column>

							<el-table-column label="状态">
								<template slot-scope="scope">
									<el-tag
										class="disabled-btn"
										type="success"
										effect="dark"
										size="mini"
										v-if="scope.row.result == '成功'"
									>{{scope.row.result}}</el-tag>

									<el-tag
										class="disabled-btn"
										type="danger"
										effect="dark"
										size="mini"
										v-else
									>{{scope.row.result}}</el-tag>
								</template>
							</el-table-column>

							<el-table-column prop="remark" label="备注"></el-table-column>
						</el-table>
					</el-tab-pane>
					<el-tab-pane label="操作记录二" name="second">
						<el-table
							ref="table"
							:data="logs2"
							tooltip-effect="dark"
							style="width: 100%"
						>
							<el-table-column prop="type" label="操作类型"></el-table-column>

							<el-table-column prop="operator" label="操作人"></el-table-column>

							<el-table-column prop="result" label="执行结果"></el-table-column>

							<el-table-column label="状态">
								<template slot-scope="scope">
									<el-tag
										class="disabled-btn"
										type="success"
										effect="dark"
										size="mini"
										v-if="scope.row.result == '成功'"
									>{{scope.row.result}}</el-tag>

									<el-tag
										class="disabled-btn"
										type="danger"
										effect="dark"
										size="mini"
										v-else
									>{{scope.row.result}}</el-tag>
								</template>
							</el-table-column>

							<el-table-column prop="remark" label="备注"></el-table-column>
						</el-table>
					</el-tab-pane>
					<el-tab-pane label="操作记录三" name="third">
						<el-table
							ref="table"
							:data="logs3"
							tooltip-effect="dark"
							style="width: 100%"
						>
							<el-table-column prop="type" label="操作类型"></el-table-column>

							<el-table-column prop="operator" label="操作人"></el-table-column>

							<el-table-column prop="result" label="执行结果"></el-table-column>

							<el-table-column label="状态">
								<template slot-scope="scope">
									<el-tag
										class="disabled-btn"
										type="success"
										effect="dark"
										size="mini"
										v-if="scope.row.result == '成功'"
									>{{scope.row.result}}</el-tag>

									<el-tag
										class="disabled-btn"
										type="danger"
										effect="dark"
										size="mini"
										v-else
									>{{scope.row.result}}</el-tag>
								</template>
							</el-table-column>

							<el-table-column prop="remark" label="备注"></el-table-column>
						</el-table>
					</el-tab-pane>
				</el-tabs>
			</el-card>
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common.js";

export default {
	name: 'profile_advanced',
	mixins: [commonMixin],
	data(){
		return {
			logs1: [
				{
					type: '下单',
					operator: '韦丹',
					result: '成功',
					time: '2020-05-15 11:22',
					remark: '',
				},
				{
					type: '付钱',
					operator: '汪正业',
					result: '失败',
					time: '2020-05-15 11:22',
					remark: '数据异常',
				},
				{
					type: '审核',
					operator: '冯浩',
					result: '失败',
					time: '2020-05-15 11:22',
					remark: '',
				},
				{
					type: '复审',
					operator: '申正诚',
					result: '成功',
					time: '2020-05-15 11:22',
					remark: '正常',
				},
				{
					type: '取消',
					operator: '郭智渊',
					result: '成功',
					time: '2020-05-15 11:22',
					remark: '',
				},
			],
			logs2: [
				{
					type: '下单',
					operator: '韦丹',
					result: '成功',
					time: '2020-05-15 11:22',
					remark: '',
				},
			],
			logs3: [
				{
					type: '取消',
					operator: '郭智渊',
					result: '成功',
					time: '2020-05-15 11:22',
					remark: '',
				},
			],
			tab_val: "first"
		}
	}
}
</script>

<style lang="scss">
.page-header-heading{
	display: flex;
	justify-content: space-between;
}

.page-header-content{
	display: flex;
	padding-top: 12px;

	.page-header-content-detail{
		flex: auto;

		table{
			width: 100%;

			td{
				padding-bottom: 8px;
			}
		}
	}

	.page-header-content-extra{
		min-width: 242px;
		margin-left: 88px;
		text-align: right;

		.extra-statistic{
			display: flex;
			justify-content: space-between;
			width: 200px;

			.statistic-title{
				margin-bottom: 4px;
				color: rgba(0,0,0,.45);
				font-size: 14px;
			}

			.statistic-content{
				color: rgba(0,0,0,.85);
				font-size: 24px;
			}
		}
	}
}

.box-card{
	margin-bottom: 20px;
}

.info-table{
	width: 100%;
}

.item-label{
	color: rgba(0,0,0,.85);
	font-size: 14px;

	&::after{
		content: ":";
		margin: 0 8px 0 2px;
	}
}

.item-content{
	color: rgba(0,0,0,.65);
	font-size: 14px;
}
</style>