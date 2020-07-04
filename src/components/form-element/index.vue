<script>
export default {
	functional: true,
	props: ['element', 'results'],
    render: function (h, context) {
		// <el-input v-model="filter.name" size="mini"></el-input>
  		let self = this;

		let element = context.props.element || '';
		let childrens = [];

		switch(element){
			case 'select':{
				element = 'el-select';

				let results = [...context.props.results];
				console.log(context);

				if(results){
					results.forEach(item => {
						// console.log(item);
						let children = h('el-option', {
							props: {
								...item
							}
        				});
						childrens.push(children);
					});
				}

				console.log(context);
				console.log(childrens);

				break;
			}
			case 'date':{
				element = 'el-date-picker';
				break;
			}
			default: {
				element = 'el-input';
			}
		}

		if(element == 'options'){
			h('el-option', {
				attrs: {
					label: "区域一",

					// label: item.label,
					// value: item.value,
				}
			});
		}

		let data = context.data;

		// data.domProps = {
		// 	value: context.props.value
		// };

		//  data.model = {
		// 	prop: 'value',
		// 	event: 'change.value'
		// };

		// data.on = {
		// 	input: function (value) {
		// 		console.log(event);
		// 		context.listeners.input(value);
		// 		// self.$emit('input', event.target.value)
		// 	}
		// }

		// console.log(childrens);

		return h(element, data, childrens);
	}
}
</script>