<template>
	<div class="container">
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
import util from '@/libs/util.js';

export default {
	name: 'login',
	data(){
		return {
			is_loading: false,

			args: {
				login: 'test',
				password: 'asd123'
			}
		}
	},
	mounted(){
	},
	methods: {
		login(){
			let args = {...this.args};

			if(args.login == ''){
				return this.$message({
					showClose: true,
					message: '请先输入登录账户',
					type: 'warning'
				}); 
			}

			if(args.password == ''){
				return this.$message({
					showClose: true,
					message: '请先输入登录密码',
					type: 'warning'
				}); 
			}




			this.is_loading = true;

			util.post('/login', args).then(res => {
				this.is_loading = false;

				if(res && typeof(res.status) != 'undefined' && res.status > 0){
					// 此处添加相关登录代码

				}
				else if(res && typeof(res.msg) != 'undefined' && res.msg != ''){
					this.$message({
						showClose: true,
						message: res.msg,
						type: 'warning'
					}); 
				}
				else{
					this.$message({
						showClose: true,
						message: '服务器未响应，请稍后重试',
						type: 'warning'
					}); 
				}
			}).catch(err => {
				this.is_loading = false;

				this.$message({
					showClose: true,
					message: '网络异常, 请稍后重试',
					type: 'warning'
				}); 
			});
		



			return;

			// 此处为模拟服务器请求返回
			setTimeout(()=>{
				
				// 此处假设返回数据为res
				let res = {
					user: {
						id: 1,
						name: 'admin'
					},
					// 此处假设被禁止访问的数据是直接从数据中读取返回的
					forbidden: [
						{
							name: '',
							controller: '',
							action: '',
						}
					]
					// data: 
				};

				/*
					this.$message({
						showClose: true,
						message: '错了哦，这是一条错误消息',
						type: 'error'
					});
				 */

				this.is_loading = false;
			}, 2000);
		}
	}
}
</script>

<style lang="scss">
	*, :after, :before {
		box-sizing: border-box;
	}

	.container{
		background-image: url(../assets/img/login.jpg);
		height: 100%;
		width: 100%;
		background-size: cover;
		background-position: top center;
		display: flex;
		justify-content: center;
		align-items: center;
		padding: 15vh 0!important;
		// box-sizing: border-box;

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
			// margin-bottom: 40px;

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
					// text-align: center;
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