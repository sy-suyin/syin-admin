<template>
	<div>
		<page-header></page-header>
	
		<div class="content-container">
			<el-row :gutter="20">
				<el-col :xs="24" :sm="12" :lg="8" :xl="6">
					<el-card>
						<div class="add-card">
							<i class="icon el-icon-plus"></i>
							<span>新增卡片</span>
						</div>
					</el-card>
				</el-col>
				<el-col :xs="24" :sm="12" :lg="8" :xl="6">
					<img-card :title="item.title" :description="item.description" :img="item.img">
						<div class="button">操作一</div>
						<el-divider direction="vertical"></el-divider>
						<div class="button">操作二</div>
					</img-card>
				</el-col>
				<el-col :xs="24" :sm="12" :lg="8" :xl="6">
					<img-card :title="item.title" :description="item.description" :img="item.img">
						<div class="button">操作一</div>
						<el-divider direction="vertical"></el-divider>
						<div class="button">操作二</div>
					</img-card>
				</el-col>
				<el-col :xs="24" :sm="12" :lg="8" :xl="6">
					<img-card :title="item.title" :description="item.description" :img="item.img">
						<div class="button">操作一</div>
						<el-divider direction="vertical"></el-divider>
						<div class="button">操作二</div>
					</img-card>
				</el-col>
				<el-col :xs="24" :sm="12" :lg="8" :xl="6">
					<img-card :title="item.title" :description="item.description" :img="item.img">
						<div class="button">操作一</div>
						<el-divider direction="vertical"></el-divider>
						<div class="button">操作二</div>
					</img-card>
				</el-col>
				<el-col :xs="24" :sm="12" :lg="8" :xl="6">
					<img-card :title="item.title" :description="item.description" :img="item.img">
						<div class="button">操作一</div>
						<el-divider direction="vertical"></el-divider>
						<div class="button">操作二</div>
					</img-card>
				</el-col>
				<el-col :xs="24" :sm="12" :lg="8" :xl="6">
					<img-card :title="item.title" :description="item.description" :img="item.img">
						<div class="button">操作一</div>
						<el-divider direction="vertical"></el-divider>
						<div class="button">操作二</div>
					</img-card>
				</el-col>
			</el-row>
		</div>
	</div>
</template>

<script>
import imgCard from "@/components/img-card";
import {common as commonMixin} from "@/mixins/common.js";

export default {
	name: 'list_card',
	mixins: [commonMixin],
	components: { imgCard },
	data(){
		return {
			item: {
				title: 'Angular',
				description: 'Angular is a platform for building mobile and desktop web applications',
				img: 'https://gw.alipayobjects.com/zos/rmsportal/zOsKZmFRdUtvpqCImOVY.png'
			}
		}
	}
}
</script>

<style lang="scss">
.el-col{
	margin-bottom: 20px;
}

.add-card{
	display: flex;
	text-align: center;
	height: 140px;
	justify-content: center;
	align-items:center;
	cursor: pointer;

	.icon{
		margin-right: 6px;
	}

	&:hover{
		color: #40a9ff;
	}
}
</style>