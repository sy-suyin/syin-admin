<template>
	<div class="data-table">
		<page-header>
			<template #breadcrumb-after>
				<h2 class="page-title">管理员列表</h2>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
            <el-card class="box-card">
                <el-tabs tab-position="left" style="height: 200px;">
                    <el-tab-pane label="基本配置">用户管理</el-tab-pane>
                    <el-tab-pane label="小程序配置">小程序配置</el-tab-pane>
                    <el-tab-pane label="配置添加">配置添加</el-tab-pane>
                </el-tabs>
            </el-card>
		</div>
	</div>
</template>

<script>
import commonMixin from "@/mixins/common";
import validateMixin from "@/mixins/validate";
import userApi from '@/api/auth';

export default {
	name: "home",
	mixins: [ commonMixin, validateMixin ],
}
</script>