import styleConfig from '@/config/style';

const { sidebar_mini, sidebar_filters_color, sidebar_background_color, sidebar_background_img, sidebar_background_imgs, fixed_header } = styleConfig;

const state = {
	sidebar_mini: sidebar_mini,
	sidebar_filters_color,
	sidebar_background_color,
	sidebar_background_img,
	sidebar_background_imgs,
	fixed_header,
}

const getters = {
	sidebar_mini:state=>state.sidebar_mini,
	sidebar_filters_color:state=>state.sidebar_filters_color,
	sidebar_background_color:state=>state.sidebar_background_color,
	sidebar_background_img:state=>state.sidebar_background_img,
	fixed_header:state=>state.fixed_header,
}

const mutations = {
	settingEdit(state, {key, value}){
		console.log('settingEdit');
		if (state.hasOwnProperty(key)) {
			state[key] = value;
			this.commit('settings/archive');
		}
	},

	archive(state){
		localStorage.setItem('settings', JSON.stringify(state));
	},

	init(state){
		let settings = localStorage.getItem('settings');
		if(settings){
			settings = JSON.parse(settings);

			if(settings){
				for(let key in settings){
					state[key] = settings[key];
				}
			}
		}
	}
}

const actions = {
	changeSetting({ commit }, data, data1){
		commit('settingEdit', data);
	}
}

export default {
	namespaced: true,
    state,
    getters,
	mutations,
	actions
}