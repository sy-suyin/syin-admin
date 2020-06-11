<template>
	<div id="page-lock">
		<el-card class="unlock-box">
			<div class="user-avatar">
				<el-avatar :src="user.avatar" :size="80"></el-avatar>
			</div>
			<div class="user-name">
				{{user.name}}
			</div>
			<div>
				<el-input v-model="pwd" placeholder="请输入密码"></el-input>
			</div>

			<div class="footer-bar">
				<el-button type="primary" @click="unlock">解锁</el-button>
			</div>
		</el-card>
	</div>
</template>

<script>
import commonMixin from "@/mixins/common";

export default {
	name: 'lock',
	mixins: [commonMixin],
	data(){
		return {
			pwd: '',

			user: {
				name: '',
				avatar: ''
			}
		}
	},
	mounted(){
		// 获取用户信息
		let user = this.$store.getters['auth/user'];

		// 验证用户信息
		if(!user){
			this.$store.commit('auth/logout');
			return false;
		}

		// 如果解锁密码异常, 则退出登录
		if(! user.hasOwnProperty('lock_pwd') || user.lock_pwd.length < 3){
			return this.$store.commit('auth/logout');
		}

		// 如果已解锁, 则返回首页
		if(! user.hasOwnProperty('is_lock') || !user.is_lock){
			return this.$router.replace({path: '/'})
		}

		this.user = user;

		// 设置页面标题
		window.document.title = '页面锁定中';
	},
	methods: {
		// 解锁
		unlock(){
			let user = {...this.user};
			const real_pwd = user.lock_pwd || '';

			// TODO: 对本地存储的是数据进行加密处理
			if(this.pwd.length < 3 || real_pwd !== this.pwd){
				return this.message('解锁密码不正确');
			}

			this.$store.commit('auth/unlock');
			this.$router.replace({path: '/'});
		}
	}
}
</script>

<style lang="scss" scoped>
#page-lock{
	height: 100%;
	background-position: 50%;
    background-size: cover;
	background-image: url(../../assets/img/lock.jpg);
	position: relative;
	padding: 15vh 0!important;

	&:after{
		position: absolute;
		background: rgba(0,0,0,.5);
		z-index: 1;
		width: 100%;
		height: 100%;
		display: block;
		left: 0;
		top: 0;
		content: "";
	}

	.unlock-box{
		position: absolute;
		z-index: 9;
		margin: 100px auto 0;
		width: 320px;
		left: 0;
		right: 0;
		overflow: unset;

		.user-avatar{
			.el-avatar{
				display: block;
				margin: 0 auto;
				margin-top: -45px;
			}
		}

		.user-name{
			margin: 10px 0;
			text-align: center;
			color: #3c4858;
			font-size: 18px;
			line-height: 1.4em;
			text-decoration: none;
			font-family: Roboto,Helvetica,Arial,sans-serif;
		}

		.footer-bar{
			margin-top: 20px;

			.el-button{
				width: 100%;
			}
		}
	}
}
</style>