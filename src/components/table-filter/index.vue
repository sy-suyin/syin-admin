<template>
	<!-- 筛选 -->
	<el-card class="filter-box">
		<el-form ref="filter_form" :model="filter" label-width="80px">
			<el-row>
				<el-col :xs="12" :sm="8" :xl="6">
					<el-form-item label="筛选输入" prop="name">
						<form-element element="input" placeholder="请输入活动区域" size="mini" v-model="filter.name"></form-element>
						<!-- <el-input v-model="filter.name" size="mini"></el-input> -->
					</el-form-item>
				</el-col>

				<el-col :xs="12" :sm="8" :xl="6">
					<el-form-item label="筛选下拉" prop="region">
						
						<form-element element="select" placeholder="请选择活动区域" v-model="filter.region" size="mini" :results="select_data">
							<el-option label="区域一" value="shanghai"></el-option>
							<el-option label="区域二" value="beijing"></el-option>
							<!-- <form-element element="option" label="区域一" value="shanghai"></form-element> -->
						</form-element>

						<!-- <el-select v-model="filter.region" placeholder="请选择活动区域" size="mini">
							<el-option label="区域一" value="shanghai"></el-option>
							<el-option label="区域二" value="beijing"></el-option>
						</el-select> -->
					</el-form-item>
				</el-col>

				<el-col :xs="12" :sm="8" :xl="6">
					<el-form-item label="筛选下拉" prop="region">
						<el-select v-model="filter.region" placeholder="请选择活动区域" size="mini">
							<el-option label="区域一" value="shanghai"></el-option>
							<el-option label="区域二" value="beijing"></el-option>
						</el-select>
					</el-form-item>
				</el-col>

				<el-col :xs="12" :sm="8" :xl="6">
					<el-form-item label="筛选时间" prop="time">
						<el-date-picker
							v-model="filter.time"
							type="daterange"
							range-separator="至"
							start-placeholder="开始日期"
							end-placeholder="结束日期" size="mini"
						>
						</el-date-picker>
					</el-form-item>
				</el-col>

				<el-col :xs="12" :sm="8" :xl="6">
					<el-form-item label="筛选输入" prop="name2">
						<el-input v-model="filter.name2" size="mini"></el-input>
					</el-form-item>
				</el-col>

				<el-col class="filter-toolbar" :xs="{offset: 12,span: 12}" :sm="{offset: 16,span: 8}" :xl="{offset: 18,span: 6}">
					<el-button size="mini" type="primary" @click="filtera">查询</el-button>
					<el-button size="mini" type="danger" @click="resetForm('filter_form')">重置</el-button>
				</el-col>
			</el-row>
		</el-form>
	</el-card>
</template>

<script>
import formElement from "@/components/form-element";

export default {
	name: "table_filter",
	components: { formElement },
	data(){
		return {
			filter: {
          		name: '',
				region: '',
				time: '',
				name2: ''
			},

			select_data: [
			]
		}
	},

	mounted(){
		setTimeout(() => {
			this.select_data = [
				{
					label: 'aaa',
					value: '1',
				},
				{
					label: 'bbb',
					value: '2',
				},
				{
					label: 'ccc',
					value: '3',
				}
			];
		}, 5000);

		console.log({...this.select_data});
	},

	methods: {
		filtera(){
			console.log({
				...this.filter
			});
		},

		handle(...params){
			params.unshift('handle');
			this.$emit.apply(this, params);
		}
	}
}
</script>

<style lang="scss">
.filter-box{
	margin-bottom: 24px;

	.el-form-item{
		padding-left: 12px;

		.el-select{
			width: 100%;
		}

		.el-date-editor{
			width: 100%;
		}

		.el-range-separator{
			width: 20px;
		}
	
		&:nth-child(4n+0){
			padding-left: 20px;
		}


	}
	
	.el-col:nth-child(4n+1) .el-form-item{
		padding-left: 0;
	}


	.filter-toolbar{
		text-align: right;
	}
}
</style>