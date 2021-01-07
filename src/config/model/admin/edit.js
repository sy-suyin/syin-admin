import { initial } from '@/config/table';

let config = {
    title: '修改管理员',
    items: [
        {
            type: 'string',
            name: '登录账号',
            value: 'login',
            propValue: '',
            data: null,
        },
        {
            type: 'string',
            name: '用户名称',
            value: 'name',
            propValue: '',
            data: null,
        },
        {
            type: 'selects',
            name: '权限角色',
            value: 'roles',
            propValue: {
                placeholder: '请选择权限角色',
                label_name: 'name',
                value_name: 'id',
            },
            target: 'roles',
            data: []
        },
        {
            type: 'divider',
            name: '如果不修改密码, 下面留空',
        },
        {
            type: 'string',
            name: '登录密码',
            value: 'password',
            propValue: '',
            data: null,
        },
    ],
    rules: {
        login: [
            { required: true, message: '请输入登录账号名称', trigger: 'blur' },
        ],
        name: [
            { required: true, message: '请输入用户名称', trigger: 'blur' },
        ],
        roles: [
            { required: true, message: '请先选择角色', trigger: 'blur' },
        ],
    },
    redirect_url: '/admin/index',
}


config = {...initial, ...config};
export default config;