<template>
	<div v-if="errorLogs.length > 0">
		<li class="bug-icon" @click="show_error=true">
			<svg-icon icon="bug" class-name="icon"></svg-icon>
		</li>

		<el-dialog
			title="Error Log"
			:visible.sync="show_error"
			width="80%"
			append-to-body
		>
			<div slot="title">
				<span class="dialog-title">Error Log</span>
				<el-button size="mini" type="primary" icon="el-icon-delete" @click="clearAll">Clear All</el-button>
			</div>
			<el-table
				ref="table"
				:data="errorLogs"
				tooltip-effect="dark"
				style="width: 100%"
			>
				<el-table-column label="Message">
					<template slot-scope="{row}">
						<div class="cell">
							<div>
								<span class="msg-title">Msg:</span>
								<el-tag type="danger">{{row.err.message}}</el-tag>
							</div>
							<br>
							<div>
								<span class="msg-title">Info:</span>
								<el-tag type="warning">{{row.info}}</el-tag>
							</div>
							<br>
							<div>
								<span class="msg-title">Url: </span>
								<el-tag type="success">{{row.url}}</el-tag>
							</div>
						</div>
					</template>
				</el-table-column>

				<el-table-column label="Message">
					<template slot-scope="{row}">
						{{row.err.stack}}
					</template>
				</el-table-column>
			</el-table>
		</el-dialog>
	</div>
</template>

<script>

export default {
	data(){
		return {
			show_error: false
		}
	},
	methods: {
		clearAll(){
			this.$store.dispatch('errorLog/clearErrorLog');
		},
	},
	computed: {
		errorLogs() {
			return this.$store.getters['errorLog/logs']
		}
	}
}
</script>

<style lang="scss">
.dialog-title{
	margin-right: 10px;
	font-size: 16px;
}

.msg-title{
	margin-right: 10px;
	font-weight: 600;
	font-size: 16px;
}
</style>