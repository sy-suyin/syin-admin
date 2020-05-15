<template>
	<div>
		<el-card class="img-card" :body-style="{padding: '0px'}">
			<div class="meta-content">
				<div class="meta-avatar">
					<el-avatar :src="img"></el-avatar>
				</div>
				<div>
					<div class="meta-title">
						{{title}}
					</div>
					<div class="meta-description">
						{{description}}
					</div>
				</div>
			</div>
			<div class="meta-footer">
				<slot></slot>
			</div>
		</el-card>
	</div>
</template>

<script>
export default {
	name: 'img-card',
	props: {
		title: {
			type: String,
			required: true,
		},
		img: {
			type: String,
			required: true,
		},
		description: {
			type: String,
			default: ''
		}
	}
}
</script>

<style lang="scss">
.img-card{
	.meta-content{
		padding: 20px;
	}

	.meta-avatar{
		float: left;
		padding-right: 16px;
	}

	.meta-title{
		margin-bottom: 12px;
		color: rgba(0,0,0,.85);
		font-size: 16px;
		white-space: nowrap;
	}

	.meta-description{
		color: rgba(0, 0, 0, 0.65);
		-webkit-line-clamp: 3;
		height: 64px;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}

	.meta-footer{
		display: flex;
		justify-content: space-between;
		align-content: center;
		background: #fafafa;
		text-align: center;
		height: 42px;
		line-height: 42px;

		.button{
			text-align: center;
			flex-grow: 1;
			cursor: pointer;

			&:hover{
				color: #1890ff;
			}
		}

		.el-divider{
			flex-shrink: 0;
			height: 20px;
			margin: 10px;
		}
	}
}
</style>