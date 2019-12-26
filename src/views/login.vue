<template>
	<div class="container">
		<el-card class="login-box">
			<div slot="header">	
				<h3>登录</h3>
			</div>

			

		</el-card>
	</div>
</template>

<script>
export default {
	name: 'login',
	mounted(){

	}
}
</script>

<style lang="scss">
	.container{
		background-image: url(../assets/img/login.jpg);
		height: 100vh;
		width: 100%;
		background-size: cover;
		background-position: top center;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.login-box{
		width: 360px;
		height: 200px;
		margin-bottom: 40px;

		h3{
			padding: 0;
			margin: 0;
			text-align: center;
		}
	}
</style>