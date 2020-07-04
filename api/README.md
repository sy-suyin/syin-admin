## 安装

使用composer安装依赖

~~~
composer install
~~~

启动服务

~~~
php think run
~~~

然后就可以在浏览器中访问

~~~
http://localhost:8000
~~~

更新框架
~~~
composer update topthink/framework
~~~

## 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─admin              后台模块目录
│  │  ├─config          后台配置文件目录
│  │  ├─controller      后台控制器目录
│  │  ├─library         后台逻辑处理器目录
│  │  ├─middleware      后台中间件目录
│  │  │  ├─Auth.php     后台权限控制中间件
│  │  │  ├─IpControl.php后台ip访问控制中间件
│  │  │  └─Token.php    后台请求token控制中间件
│  │  │
│  │  ├─model           后台模型目录
│  │  ├─validate        后台验证器目录
│  │  ├─common.php      后台函数文件
│  │  └─middleware.php  中间件加载配置文件
│  │ 
│  ├─common             公共模块目录
│  │  ├─controller      自定义控制器公共基类目录
│  │  ├─exception       自定义异常处理目录
│  │  ├─function        系统通用函数目录
│  │  ├─library         系统逻辑类目录
│  │  ├─model           自定义模型基类目录
│  │  └─validate        自定义验证器基类目录
│  │
│  ├─command.php        命令行定义文件
│  ├─common.php         公共函数文件
│  └─tags.php           应用行为扩展定义文件
│
├─config                应用配置目录
│  ├─module_name        模块配置目录
│  │  ├─database.php    数据库配置
│  │  ├─cache           缓存配置
│  │  └─ ...            
│  │
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─log.php            日志配置
│  ├─session.php        Session配置
│  ├─template.php       模板引擎配置
│  └─trace.php          Trace配置
│
├─route                 路由定义目录
│  ├─route.php          路由定义
│  └─...                更多
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
│  ├─page               分页页码控制文件目录
│  │  └─Page.php        分页页码控制文件
│  │
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~
