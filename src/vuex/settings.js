import styleConfig from '@/config/style';
import Storage from '@/libs/Storage.js';

const { sidebar_mini, sidebar_filters_color, sidebar_background_img, fixed_header } = styleConfig;

const state = {
	sidebar_mini: sidebar_mini,
	sidebar_filters_color,
	sidebar_background_project: '',
	sidebar_background_img,
	sidebar_background_imgs: [],
	fixed_header,
}

const getters = {
	sidebar_mini:state=>state.sidebar_mini,
	sidebar_filters_color:state=>state.sidebar_filters_color,
	sidebar_background_project:state=>state.sidebar_background_project,
	sidebar_background_img:state=>state.sidebar_background_img,
	fixed_header:state=>state.fixed_header,
}

const mutations = {
	settingEdit(state, {key, value}){
		if (state.hasOwnProperty(key)) {
			state[key] = value;
			this.commit('settings/archive');
		}
	},

	archive(state){
		Storage.set('settings', state, false)
	},

	init(state){
		let settings = Storage.get('settings', {
			json: true,
			decrypt: false
		});

		console.log(settings);
		if(settings){

			if(settings){
				for(let key in settings){
					state[key] = settings[key];
				}
			}
		}
	}
}

const actions = {
	changeSetting({ commit }, data){
		commit('settingEdit', data);

		// 此处将数据提交给后端
	}
}

export default {
	namespaced: true,
    state,
    getters,
	mutations,
	actions
}