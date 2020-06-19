<h1 align="center">
	syin Admin
</h1>

<p align="center">
  <a href="https://github.com/vuejs/vue">
    <img src="https://img.shields.io/badge/vue-2.6.11-brightgreen.svg" alt="vue">
  </a>
  <a href="https://github.com/ElemeFE/element">
    <img src="https://img.shields.io/badge/element--ui-2.13.0-brightgreen.svg" alt="element-ui">
  </a>
  <a href="https://sysyin.coding.net/public/syin-admin/syin-admin/git/files/master/LICENSE">
    <img src="https://img.shields.io/github/license/mashape/apistatus.svg" alt="license">
  </a>
</p>

## 参考项目

- [vue-element-admin](https://panjiachen.github.io/vue-element-admin-site/zh)

- [creative-tim](https://demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html)

- [ant design](https://preview.pro.ant.design/)

- [iview-admin](https://github.com/iview/iview-admin)

## 简介

[syin-admin](http://admin.e.syin.top) 是一个后台前后端解决方案，它基于 [vue](https://github.com/vuejs/vue) 和 [element-ui](https://github.com/ElemeFE/element)实现。根据个人项目开发经验, 对过往业务代码进行提取整合, 封装前后端常用功能(后端代码见 API 文件夹), 未集成其他可能用不到功能, 适合当基础模板来进行二次开发. 如需其他功能, 可以参考vue-element-admin

- [在线预览](http://admin.e.syin.top)

## 运行

```bash
# 克隆项目
git clone https://e.coding.net/sysyin/syin-admin/syin-admin.git

# 进入项目目录
cd syin-admin

# 安装依赖
npm install

# 建议不要直接使用 cnpm 安装依赖，会有各种诡异的 bug。可以通过如下操作解决 npm 下载速度慢的问题
npm install --registry=https://registry.npm.taobao.org

# 启动服务
npm run serve
```

浏览器访问 http://localhost:8080/

## 开发

详细请查看同目录下的二次开发说明

## 功能

- 登录 / 注销

- 错误页面
  - 403
  - 404

- 前后端交互封装
	- 管理员功能
	- 角色功能
	- 表格自动操作
	- 分页操作封装
	- token

- 全局功能
	- 多种动态换肤
	- 动态侧边栏（支持多级路由嵌套）
	- 动态面包屑
	- 自适应收展侧边栏
	- 侧边栏最小化悬浮展开
	- 全局可控加载异常页面
	- 动态生成路由
	- 动态生成菜单
	- 存储数据加密

- 权限验证
	- 页面权限
	- 指令权限
	- 权限配置

## License

[MIT](https://sysyin.coding.net/public/syin-admin/syin-admin/git/files/master/LICENSE)

Copyright (c) 2020-present 溯隐