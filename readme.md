<h1>
	syin Admin
    <h3>个人自用后台管理系统, 仅供学习交流</h3>
</h1>

## 参考项目

- [vue-element-admin](https://panjiachen.github.io/vue-element-admin-site/zh)

- [creative-tim](https://demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html)

- [ant design](https://preview.pro.ant.design/)

- [iview-admin](https://github.com/iview/iview-admin)

## 开发

```bash
# 克隆项目
git clone https://e.coding.net/sysyin/admin-dashboard/admin-dashboard.git

# 进入项目目录
cd vue-element-admin

# 安装依赖
npm install

# 建议不要直接使用 cnpm 安装依赖，会有各种诡异的 bug。可以通过如下操作解决 npm 下载速度慢的问题
npm install --registry=https://registry.npm.taobao.org

# 启动服务
npm run serve
```

浏览器访问 http://localhost:8080/

## 发布

```bash
# 构建测试环境
npm run build:stage

# 构建生产环境
npm run build:prod
```

## 接口支持

> 不需要查看后台的, 可以通过修改相关配置即可
>
> 在使用接口前 需先将数据库文件导入数据库

- 方法一

```bash
# 使用此方法需PHP为全局变量

# 进入api目录
cd api

# 使用php内置服务器启动
php think run
```

- 方法二

	> 使用如 phpStudy 之类的工具部署后台, 运行目录为 api/public 目录


## 单独允许

> 不启动PHP服务器, 只是简单体验

找到 **src/views/pages/login.vue** 文件, 修改 ```use_offline``` 为 **true**

即可正常登录体验, 但如果想获得完整体验, 请运行PHP服务

public目录下offline文件夹, 为单独运行需要的资源文件, 如后期不需要可直接删除

## 功能

- 登录 / 注销

- 错误页面
  - 403
  - 404

- 前后端交互封装
	- 管理员功能
	- 角色功能
	- 数据字典
	- 表格自动操作
	- 分页操作封装

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

## 其他

更多详细文档请查看**docs**目录