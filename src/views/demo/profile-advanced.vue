<template>
	<div>
		<page-header>
			<div slot="breadcrumb-after">
				<div>
					<div>
						<h1>单号: 12312312412</h1>
					</div>
					<div>
						操作1
						操作2
					</div>
				</div>
				<div>
					<div>
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
					<div>
						<div>
							<div>状态</div>
							<div>
								<span>待审批</span>
							</div>
						</div>
						<div>
							<div>订单金额</div>
							<div>
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
		</page-header>

		<div class="content-container">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					<span>流程进度</span>
				</div>
				<div>
					<el-progress :percentage="50" :show-text="false"></el-progress>
				</div>
			</el-card>
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common.js";

export default {
	name: 'profile_advanced',
	mixins: [commonMixin],

}
</script>