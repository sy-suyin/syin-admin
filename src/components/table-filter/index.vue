<template>
	<!-- 筛选 -->
	<el-form ref="filter_form" :model="filter_args" label-width="80px" class="filter-box">
		<el-row>
			<el-col
				v-for="item in fields"
				:key="item.model"
				:xs="12"
				:sm="8"
				:xl="6"
			>
				<form-element
					:type="item.type"
					:params="item.params"
					:attrs="item.attrs"
					:props="item.props"
					:results="item.data"
					v-model="filter_args[item.model]"
				></form-element>
			</el-col>

			<el-col
				class="filter-toolbar"
				:xs="12"
				:sm="8"
				:xl="6"
			>
				<el-button size="mini" type="primary" @click="filter">查询</el-button>
				<el-button size="mini" type="danger" @click="reset">重置</el-button>
			</el-col>
		</el-row>
	</el-form>
</template>

<script>
import formElement from "@/components/form-element";

export default {
	name: "table_filter",
	components: { formElement },
	props: {
		fields: Array,
		filter_func: {
			type: String,
			default: 'filter',
		}
	},
	data(){
		return {
			// 存储搜索结果
			filter_args: {},

			// 时间处理标记
			time_filters: []
		}
	},

	created(){
		this.fields.forEach(item => {
			// 添加 v-model 所需的字段

			// 针对 element-ui 的时间选择器, 进行时间处理转换
		});

		this.init();
	},

	methods: {

		/**
		 * 初始化
		 */
		init(){
			this.fields.forEach(item => {
				// 添加 v-model 所需的字段
				this.$set(this.filter_args, item.model, '');

				// 针对 element-ui 的时间选择器, 进行时间处理转换
				if(item.hasOwnProperty('type') && item.type == 'date'){
					if(item.hasOwnProperty('props')
						&& item.props.hasOwnProperty('valueFormat')
					){
						if(item.props.valueFormat == 'timestamp'){
							this.time_filters.push({
								key: item.model,
								format: 'timestamp',
							});
						} else {
							// 其他处理方式, 由上层自行处理
						}
					}else{
						this.time_filters.push({
							key: item.model,
							format: 'original',
						});
					}
				}
			});
		},

		/**
		 * 提交筛选操作
		 */
		filter(){
			let args = {
				...this.filter_args
			};

			args = this.timeFilter(args);
			this.handle(this.filter_func, args);
		},

		/**
		 * 重置筛选
		 */
		reset(){
			let keys = Object.keys(this.filter_args);
			keys.forEach(key => {
				this.filter_args[key] = '';
			});
		},

		/**
		 * 对时间选择结果进行处理
		 */
		timeFilter(args){
			if(this.time_filters.length){
				this.time_filters.forEach(item => {
					if(! args[item.key]){
						return;
					}

					if(item.format != 'timestamp'){
						args[item.key] = +(new Date());
					}

					args[item.key] /= 1000;
				});
			}

			return args;
		},

		/**
		 * 事件中转提交
		 */
		handle(...params){
			params.unshift('handle');
			this.$emit.apply(this, params);
		}
	}
}
</script>