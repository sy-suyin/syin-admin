import { initial } from '@/config/table';

let config = {
    title: '添加管理员',
    items: [
        {
            type: 'string',
            name: '角色名称',
            value: 'name',
            propValue: '',
            data: null,
        },

        {
            type: 'custom',
            name: '数据权限',
            value: 'data',
            propValue: false,
            target: 'roles',
            data: []
        },

        {
            type: 'custom',
            name: '页面权限',
            value: 'page',
            propValue: false,
            target: 'roles',
            data: []
        },

        {
            type: 'text',
            name: '备注说明',
            value: 'password',
            propValue: '',
            data: null,
        },
    ],
    rules: {
        name: [
            { required: true, message: '请输入用户名称', trigger: 'blur' },
        ],
    },
    redirect_url: '/admin/index',
}


config = {...initial, ...config};
export default config;