<template>
	<div>
		<page-header></page-header>

		<div class="content-container">
			<svg-icon icon="api" class-name="test"></svg-icon>
		</div>
	</div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common.js";
import svgIcon from '@/components/svg-icon';

export default {
	name: 'icon',
	mixins: [commonMixin],
	components: { svgIcon },
}
</script>

<style>

</style>