<template>
	<svg aria-hidden="true" class="svg-icon" :class="className" v-on="$listeners">
		<use :href="iconName" />
	</svg>
</template>

<script>
export default {
	name: 'svg_icon',
	props: {
		icon: {
			type: String,
			required: true
		},
		className: {
			type: String,
			default: ''
		}
	},
	computed: {
		iconName(){
			return `#icon-${this.icon}`;
		}
	}
}
</script>

<style lang="scss">
.svg-icon{
	width: 1em;
	height: 1em;
	vertical-align: -0.15em;
	fill: currentColor;
	overflow: hidden;
}

.svg-external-icon {
	background-color: currentColor;
	mask-size: cover!important;
	display: inline-block;
}
</style>