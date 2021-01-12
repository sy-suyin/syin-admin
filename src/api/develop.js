import { request } from '@/libs/api';

const api = {

	/**
	 * 获取数据表列表
	 */
    getTables(){
		return request({
			url: '/develop/getTables',
			method: 'get',
			dispose: true,
		});
	},

	/**
	 * 获取数据表详情
	 */
	getTableDetail(){
		return request({
			url: '/develop/getTables',
			method: 'get',
			dispose: true,
		})
	}
}

export default api;