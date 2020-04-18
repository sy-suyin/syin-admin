<template>
	<div>
		<div class="content-container">		
			<div class="error-page" id="error-403">
				<div class="pag-container">
					<div class="error-img"></div>
					<div class="error-info">
						<h1 class="error-title">403</h1>
						<p class="error-desc">
							抱歉，你无权访问该页面
						</p>

						<el-button type="primary" @click="getback">返回主页</el-button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common.js";

export default {
	name: "error_403",
	mixins: [commonMixin],
  	data() {
		return {
			
		}
	},
	methods: {
		getback(){
			this.$router.push({path: '/'})
		}
    }
};
</script>

<style lang="scss" scoped>
#error-403{
	padding: 48px 32px;
	font-family: Chinese Quote,-apple-system,BlinkMacSystemFont,Segoe UI,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;

	.pag-container{
		margin: auto;
		display: flex;
		align-items: center;
		justify-content: center;

		.error-img{
			background-image: url("../../assets/img/403.svg");
			height: 360px;
			width: 500px;
			margin-right: 82px;
			background-repeat: no-repeat;
			background-position: 50% 50%;
			background-size: contain;
			display: inline-block;
		}

		.error-info{
			display: inline-block;
			margin-right: 200px;

			.error-title{
				color: #434e59;
				font-size: 72px;
				font-weight: 600;
				line-height: 72px;
				margin-bottom: 24px;
			}

			.error-desc{
				color: rgba(0,0,0,.45);
				font-size: 20px;
				line-height: 28px;
				margin-bottom: 20px;
			}
		}
	}

}
</style>