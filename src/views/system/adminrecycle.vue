<template>
	<div class="data-table">
		<page-header>
			<template #breadcrumb-after>
				<div>
					<h2 class="page-title">管理员回收站</h2>
				</div>
			</template>
		</page-header>

		<div class="content-container" v-loading="is_loading">
			<db-table 
				:data="results"
				:config="config"
				:pagination="page_default"
				@handle="handle"
			>
			</db-table>
		</div>
	</div>
</template>

<script>
import tableMixin from "@/mixins/table";
import config from "@/assets/build/adminrecycle";

export default {
	name: "system_adminrecycle",
	mixins: [ tableMixin ],
  	data() {
      	return {
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