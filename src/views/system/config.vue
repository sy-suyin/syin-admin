<template>
	<div class="data-table">
		<page-header>
			<template #breadcrumb-after>
				<h2 class="page-title">{{title}}</h2>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
            <el-card class="box-card">
                <el-tabs tab-position="left">
                    <el-tab-pane label="基本配置">
						<el-form :ref="this.form_name" label-width="80px">

							<el-form-item :label="item.name" v-for="item in config" :key="item.name">
								<!-- <el-input v-model="args.name"></el-input> -->
								<form-item v-model="args[item.value]" :type="item.type" :options="item.data" :propValue="item.propValue"></form-item>
							</el-form-item>

						</el-form>

						<button @click="submit">提交</button>

					</el-tab-pane>
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
import formItem from '@/components/form-item';

export default {
	name: "home",
	mixins: [ commonMixin, validateMixin ],
	components: {formItem},
	data(){
		return {
			title: '系统配置',
			args: {
			},
			config: [
				{
					type: 'select',
					name: '邮件发送',
					value: 'type',
					defaultValue: '',
					propValue: {
						placeholder: '请选择邮件',
					},
					data: [
						{
							label: '请选择',
							value: 0,
						},
						{
							label: 'SMTP',
							value: 1,
						},
						{
							label: 'Mail',
							value: 2,
						},
					]
				},
				{
					type: 'selects',
					name: '支付方式',
					value: 'paytype',
					propValue: {
						disabled: false
					},
					data: [
						{
							label: '请选择',
							value: 0,
						},
						{
							label: '微信支付',
							value: 1,
						},
						{
							label: '支付宝支付',
							value: 2,
						},
						{
							label: '美团支付',
							value: 3,
						},
						{
							label: '银行卡支付',
							value: 4,
						},
						{
							label: '余额支付',
							value: 5,
						},

					]
				},
				{
					type: 'string',
					name: '名称',
					value: 'name',
					defaultValue: '',
					propValue: '',
					data: null,
				},
				{
					type: 'text',
					name: '备注说明',
					value: 'description',
					defaultValue: '',
					propValue: '',
					data: null,
				},
			]
		}
	},
	methods: {
		submit(){
			console.log({...this.args});
		}
	}
}
</script>