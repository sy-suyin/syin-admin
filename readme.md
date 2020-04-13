- 参考项目

	- https://panjiachen.github.io/vue-element-admin-site/zh/

	- https://demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html

	- https://preview.pro.ant.design/

	- https://lison16.github.io/iview-admin-doc/#/

- 目录架构

	- api 				后台接口文件

	- src 				vue资源文件

	- dashboard.sql 	后台数据库文件

- 项目测试

	- 前端

		> 本地需先安装npm, 并更换淘宝源

		```
			// 安装依赖
			cnpm install

			// 运行
			cnpm run serve

			// 打包
			cnpm run build
		```

	- 接口

		> 不需要查看后台的, 可以调过, 修改相关配置即可
		>
		> 在使用接口前 需先将数据库文件导入数据库

		- 方法一

			> 使用此方法需php全局可调用

			```
				// 进入api目录
				cd api

				// 使用php内置服务器启动
				php think run
			```

		- 方法二

			> 使用如 phpStudy 之类的工具部署后台, 运行目录为 api/public 目录