/**
 * 封装主要用于 添加/修改 表单验证
 */
import { getType } from '@/libs/util';
import Chain from '@/libs/Chain';

export default {
	data(){
		return {
			form_name: 'form',

			// 获取参数数组
			optimget: '',

			// 验证方法数组
			validator: [ 'validate' ],
		}
	},
	methods: {

		/**
		 * 表单提交
		 */
		submitChain(params = {}){
			let chains = [...this.validator];

			// 数据处理
			if(this.optimget){
				chains.unshift(this.optimget);
			}else{
				params.args = {...this.args};
			}

			let promise = Promise.resolve(params);
			chains.forEach(fn => {
				promise = promise.then(this[fn], null);
			});

			// 异常处理
			promise.catch( this.errorHandle );

			return promise;
		},

		/**
		 * 提交前进行数据检查
		 */
		validate(params){
			return new Promise((resolve, reject) => {
				this.$refs[this.form_name].validate((valid, fields) => {
					if(!valid){
						this.validateTip(fields);
						reject();
					}else{
						resolve(params);
					}
				});
			});
		},

		/**
		 * element-ui 自带表单验证失败时, 弹窗提示
		 *
		 */
		validateTip(fields){
			let keys = Object.keys(fields);

			if(keys.length < 1 || fields[keys[0]].length < 1){
				return;
			}

			let msg = fields[keys[0]][0].message;
			this.message(msg, 'warning');
		},

		/**
		 * 异常处理
		 */
		errorHandle(e){
			if(! e) return;

			let msg = e;
			if(getType(msg) != 'string'){
				msg = e.message || '服务器异常, 请稍后重试';
			}

			this.$message(msg, 'warning');
		}
	},
}