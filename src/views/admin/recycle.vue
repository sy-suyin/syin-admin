<template>
	<div class="data-table">
		<page-header>
			<template #breadcrumb-after>
				<h2 class="page-title">{{title}}</h2>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
			<db-table 
				:data="results"
				:config="config"
				:pagination="pages.default"
				@handle="handle"
			>
			</db-table>
		</div>
	</div>
</template>

<script>
import tableMixin from "@/mixins/table";
import config from "@/config/model/admin/recycle";

export default {
	name: "admin_recycle",
	mixins: [ tableMixin ],
  	data() {
      	return {
			title: '管理员回收站',
			config,
		}
	},
	created(){
		this.addScene(config.urls.recycle, 'default', { mapping: 'results' });
	},
	mounted(){
		this.getRequestData();
	},
	methods: {
    }
};
</script>

<style lang="scss">
@import "@/assets/style/table.scss";
</style>