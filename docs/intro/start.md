# 开始

该项目为后台前后端解决方案, 所以项目运行时需要后端的支持, 这里提供三种方法

## 一、纯前端访问

> 仅能查看一些模板页面, 无法查看核心模块页面

找到 **src/views/pages/login.vue** 文件

修改 ```use_offline``` 为 **true**

注: public目录下offline文件夹, 为单独运行需要的资源文件, 如后期不需要可直接删除

## 二、使用临时接口

后端提供接口分为 接口地址, 资源地址, token刷新地址三种

- 接口地址: 用于请求数据

- 资源地址: 资源文件如图片等的存储地址, 不一定和接口在同一个服务器, 登录时返回

- token刷新地址: 用于token过期时刷新获取新token, 登录时返回

修改 **src/config/reuqest.js**, 将 base_url 中的 dev 路径(接口地址)修改为:

**http://admin.api.syout.top/client**

将 **api/public** 目录下的 **static** 文件夹复制到 **public/** 文件夹下即可

临时接口账号密码俱为 **asd123**

*注: 临时接口的数据每天3:00重置*

## 三、手动搭建后端

> 手动搭建后台前, 应先将 **api** 目录下的 **BASE.zip** 解压到当前文件夹, 压缩包内为后端运行必须的依赖文件

运行前应先安装数据库和php, 为方便也可直接安装 phpStudy, 后端使用框架为 thinkphp5.1 

### 数据库配置

> 数据库配置在 api/config 目录下 **database.php** 文件中

数据库默认端口为 **3306**

### 搭建方法 A

此方法要求设置php为系统环境全局

```bash
# 进入api目录
cd api

# 使用php内置服务器启动
php think run
```
### 搭建方法 B

运行phpStudy, 添加站点, 运行目录为 api/public 目录