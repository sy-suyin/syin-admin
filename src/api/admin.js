import { request } from '@/libs/api';

const api = {

    /**
	 * 首页数据
	 */
	index(data, dispose = true) {
		return request({
			url: '/admin/index',
			method: 'get',
			dispose,
			data,
		});
    },

    /**
	 * 获取回收站数据
	 */
    recycle(data, dispose = true){
		return request({
			url: '/admin/recycle',
			method: 'get',
			dispose,
			data,
		});
    },

	/**
	 * 获取创建数据前所需数据
	 */
	create() {
		return request({
			url: '/admin/create',
			method: 'get',
			dispose: true,
		});
	},

	/**
	 * 创建数据
	 */
	save(data) {
		return request({
			url: '/admin/save',
			data,
			method: 'post',
			dispose: true,
		});
	},

	/**
	 * 数据详情
	 */
	read(data) {
		return request({
			url: '/admin/read',
			data,
			method: 'get',
			dispose: true,
		});
	},

	/**
	 * 获取修改时所需数据
	 */
	edit(data) {
		return request({
			url: '/admin/edit',
			data,
			method: 'get',
			dispose: true,
		});
	},

	/**
	 * 修改数据
	 */
	update(data) {
		return request({
			url: '/admin/update',
			data,
			method: 'post',
			dispose: true,
		});
	},

	/**
	 * 删除数据
	 */
	del(id) {
		return request({
			url: '/admin/delete',
			data: {
				id
			},
			method: 'post',
			dispose: true,
		});
	}
}

export default api;