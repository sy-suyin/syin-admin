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

## 三、手动搭建后端

运行前应先安装数据库和php, 为方便也可直接安装 phpStudy, 后端使用框架为 thinkphp5.1 

### 数据库配置

> 数据库路径在 api/config 目录下 **database.php** 文件中

数据库默认端口为 3306

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

# 打包部署

```bash
cnpm run build
```

打包完成后的 **dist** 文件夹下所有的文件, 复制到后端的public文件夹下即可

访问时需加上 index.html 文件, 如后端地址为 http://127.0.0.1:8000/

则访问 http://127.0.0.1:8000/index.html, 即可查看

如果不需要显示index.html文件, 可按如下方式修改

修改 **src/router** 目录下的路由模式, 改为 hash 模式, 再于后端修改nginx

此处以宝塔nginx为例, 修改配置文件, 并开启伪静态

```bash
# 旧的配置
# index index.php index.html index.htm default.php default.htm default.html;

# 新的配置
index index.html index.php index.htm default.php default.htm default.html;
```