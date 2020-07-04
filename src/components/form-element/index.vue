<script>
export default {
	functional: true,
	props: {
		type: String,
		model: String,
		params: Object,
		results: Array,
		attrs: Object,
		props: Object
	},
    render: function (h, { data, props }) {
		let {
			show_label = false,
			label = '',
		} = props.params;

		let element = '';
		let childrens = [];
		data.attrs = { ...data.attrs, ...props.attrs };
		data.props = { ...data.props, ...props.props };

		switch(props.type){
			case 'select':{
				element = 'el-select';
				let results = [...props.results];

				if(results){
					results.forEach(item => {
						let children = h('el-option', {
							props: {
								...item
							}
        				});
						childrens.push(children);
					});
				}

				break;
			}
			case 'time': {
				element = 'el-time-select';
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

		let node = h(element, data, childrens);

		// 判断是否加入外围的标签
		if( show_label ){
			let parent = h('el-form-item', {
				props: {
					label: label,
					prop: props.model
				}
			}, [node]);

			return parent;
		}else{
			return node;
		}

		return parent;
	}
}
</script>