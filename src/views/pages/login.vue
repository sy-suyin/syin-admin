<template>
	<div id="page-login">
		<div class="login-box">
			<el-card class="login-card">
				<div slot="header" class="header">
					<h3>登录</h3>
				</div>

				<div>
					<div class="input-group">
						<i class="icon el-icon-user-solid"></i>
						<input type="text" class="login-name" placeholder="登录账户" v-model="args.login">
					</div>

					<div class="input-group">
						<i class="icon el-icon-lollipop"></i>
						<input type="password" class="password" placeholder="登录密码" v-model="args.password">
					</div>

					<div>
						<el-button plain class="login-btn" @click="login" :loading="is_loading">登录</el-button>
					</div>
				</div>
			</el-card>
		</div>
	</div>
</template>

<script>
import userApi from '@/api/auth';
import { getType } from '@/libs/util';

export default {
	name: 'login',
	data(){
		return {
			is_loading: false,
			use_offline: false,

			optimget: 'getParams',
			validator: [ 'dataVerify', 'offlineVerify'],

			args: {
				login: '',
				password: ''
			}
		}
	},
	mounted(){
		this.args.login = 'test';
		this.args.password = 'asd123';
	},
	methods: {

		/**
		 * 登录
		 */
		login(){
			this.loginChains().then(params => {
				this.is_loading = true;

				userApi.login(params.args).then(result => {
					this.loginSuccess(result);
				}).catch(e => {
					let msg = e.message || '网络异常, 请稍后重试';
					this.message(msg, 'warning');
				}).finally(()=>{
					this.is_loading = false;
				});
			}).catch(e => {
				if(! e) return;

				let msg = e.message || '服务器异常, 请稍后重试';
				this.message(msg, 'warning');
			});
		},

		/**
		 * 封装登录操作, 链式调用
		 */
		loginChains(){
			let chains = [this.optimget, ...this.validator];
			let promise = Promise.resolve({});

			chains.forEach(fn => {
				promise = promise.then(this[fn], null);
			});

			// 异常处理
			promise = promise.then(null, this.errorHandle);

			return promise;
		},

		/**
		 * 获取提交所需的参数
		 */
		getParams(params){
			let args = {...this.args};
			args.login = args.login.trim();
			args.password = args.password.trim();

			params.args = args;
			return params;
		},

		/**
		 * 检查将要提交的数据
		 */
		dataVerify(params){
			if(params.args.login == ''){
				return Promise.reject(new Error('请先输入登录账户'));
			}

			if(params.args.password == ''){
				return Promise.reject(new Error('请先输入登录密码'));
			}

			return params;
		},

		/**
		 * 是否采用离线登录
		 */
		offlineVerify(params){
			if(this.use_offline){
				// 离线登录
				this.offlineLogin();
				return Promise.reject();
			}

			return params;
		},

		/**
		 * 登录成功处理
		 */
		loginSuccess(result){
			// 存储后端返回的相关配置信息
			this.$store.commit('auth/setLogin', result);

			// 如果有本地记录的重定向记录, 则在登陆后跳转回之前的页面
			// 注: 此功能未实现
			let redirect = localStorage.getItem('user_redirect');
			localStorage.removeItem('user_redirect');

			// 登录跳转
			let redirect_path = redirect ? redirect : this.$store.getters['access/routers'][0].path;
			this.$router.replace({path: redirect_path})
		},

		/**
		 * 离线登录, 不使用任何与后端有关的功能
		 */
		offlineLogin(){
			const result = {
				config: {
					domain: '',
					sidebar_imgs: [
						'http://localhost:8080/offline/bg-1.jpg',
						'http://localhost:8080/offline/bg-2.jpg',
						'http://localhost:8080/offline/bg-3.jpg',
						'http://localhost:8080/offline/bg-4.jpg',
					]
				},
				blocklist: {
					data: {
					},
					page: {
						index: ['profile'],
						system: ['adminlist', 'adminadd', 'adminedit', 'adminrecycle','rolelist', 'roleadd', 'roleedit', 'rolerecycle', 'dict']
					}
				},
				user: {
					id: 0,
					login_name: 'test',
					name: '离线用户',
					avatar: 'http://localhost:8080/offline/avatar.png',
					is_admin: 0,
				}
			};

			this.loginSuccess(result);
		},

		/** 
		 * 提示消息
		 * 
		 * @param message  消息内容
		 * @param type 	   消息类型
		 * @param duration 消息显示时间, 单位: 毫秒, 传入0将不会自动关闭
		 * @param path     消息关闭后跳转路径, 为空不跳转
		 */
		message(message, type='warning', duration=3000, path=''){
			return this.$message({
				showClose: true,
				message,
				type,
				duration,
				onClose: ()=>{
					if(path != ''){
						this.$router.push({path});
					}
				}
			});
		}
	},
}
</script>

<style lang="scss">
#page-login{
	background-image: url(../../assets/img/login.jpg);
	height: 100%;
	width: 100%;
	background-size: cover;
	background-position: top center;
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 15vh 0!important;

	&::before{
		background: rgba(0,0,0,.5);
		position: absolute;
		z-index: 1;
		width: 100%;
		height: 100%;
		display: block;
		left: 0;
		top: 0;
		content: "";
	}

	.login-box{
		z-index: 2;
		width: 100%;
		max-width: 1140px;
		padding-right: 15px;
		padding-left: 15px;
		margin-right: auto;
		margin-left: auto;
	}

	.login-card{
		width: 340px;
		height: 260px;
		margin: 0 auto;

		.el-card__header{
			border-bottom: none;
			padding-bottom: 6px;
		}

		h3{
			padding: 0;
			margin: 0;
			text-align: center;
			font-size: 25px;
		}

		.input-group{
			display: flex;
			align-items: center;
			margin-bottom: 16px;

			.icon{
				flex-basis: 36px;
			}

			input{
				flex-grow: 1;
				outline: none;
				background: no-repeat bottom,50% calc(100% - 1px);
				background-image: linear-gradient(0deg,#9c27b0 2px,rgba(156,39,176,0) 0),linear-gradient(0deg,#d2d2d2 1px,hsla(0,0%,82%,0) 0);

				background-size: 0 100%,100% 100%;
				border: 0;
				height: 36px;
				transition: background 0s ease-out;
				padding-left: 0;
				padding-right: 0;
				border-radius: 0;
				font-size: 14px;
				height: 36px;
				transition: background 0s ease-out;
				font-size: 14px;

				&:focus {
					background-size: 100% 100%,100% 100%;
					transition-duration: .3s;
					box-shadow: none;
				}

				&::-moz-placeholder, &:-ms-input-placeholder, &::-webkit-input-placeholder{
					color: #aaa;
					font-weight: 400;
					font-size: 14px
				}

				&:-ms-input-placeholder {
					color: #aaa;
					font-weight: 400;
					font-size: 14px
				}

				&::-webkit-input-placeholder {
					color: #aaa;
					font-weight: 400;
					font-size: 14px
				}
			}
		}

		.login-btn{
			width: 100%;
			margin-top: 20px;
			color: #fff;
			background-color: #ff9800;
			border-color: #ff9800;
			margin-top: 10px;

			&:hover{
				color: #fff;
				background-color: #f08f00;
				border-color: #c27400;
				box-shadow: 0 14px 26px -12px rgba(255,152,0,.42), 0 4px 23px 0 rgba(0,0,0,.12), 0 8px 10px -5px rgba(255,152,0,.2);
			}
		}
	}
}
</style>