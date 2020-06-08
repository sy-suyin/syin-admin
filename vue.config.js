const path = require('path')

const resolve = dir => {
  return path.join(__dirname, dir)
}

// 项目部署基础
// 默认情况下，我们假设你的应用将被部署在域的根目录下,
// 例如：https://www.my-app.com/
// 默认：'/'
// 如果您的应用程序部署在子路径中，则需要在这指定子路径
// 例如：https://www.foobar.com/my-app/
// 需要将它改为'/my-app/'
// iview-admin线上演示打包路径： https://file.iviewui.com/admin-dist/
const CompressionWebpackPlugin = require('compression-webpack-plugin');
const productionGzipExtensions = ['js', 'css'];
const isProduction = process.env.NODE_ENV === 'production';
const BASE_URL = isProduction
  ? '/'
  : '/'

module.exports = {
  // Project deployment base
  // By default we assume your app will be deployed at the root of a domain,
  // e.g. https://www.my-app.com/
  // If your app is deployed at a sub-path, you will need to specify that
  // sub-path here. For example, if your app is deployed at
  // https://www.foobar.com/my-app/
  // then change this to '/my-app/'
  publicPath: BASE_URL,
  //输出文件目录
	outputDir: 'dist',
  //是否在保存的时候检查
	lintOnSave: true,
	//放置生成的静态资源 (js、css、img、fonts) 的 (相对于 outputDir 的) 目录。
	assetsDir: 'static/admin',
  // tweak internal webpack configuration.
  // see https://github.com/vuejs/vue-cli/blob/dev/docs/webpack.md
  // 如果你不需要使用eslint，把lintOnSave设为false即可
	lintOnSave: false,
	configureWebpack: config => {
    if (isProduction) {
      config.plugins.push(new CompressionWebpackPlugin({
        algorithm: 'gzip',
        test: new RegExp('\\.(' + productionGzipExtensions.join('|') + ')$'),
        threshold: 10240,
        minRatio: 0.8
      }))
    }
  },
  chainWebpack: config => {
	config.plugins.delete('preload')
	config.plugins.delete('prefetch')

	config.resolve.alias
		.set('@', resolve('src')) // key,value自行定义，比如.set('@@', resolve('src/components'))

	config.output.filename('[name].[hash].js').end()

	config.module
		.rule('svg')
		.exclude.add(resolve('src/icons'))
		.end()

	config.module
		.rule('icons')
		.test(/\.svg$/)
		.include.add(resolve('src/icons'))
		.end()
		.use('svg-sprite-loader')
		.loader('svg-sprite-loader')
		.options({
			symbolId: 'icon-[name]'
		})
		.end()

    // 生产模式
    config.when(process.env.NODE_ENV === 'production', config => {
      // 生产模式加载 main-prod 入口文件
      config.entry('app').clear().add('./src/main-prod.js')
      // CDN - externals
      config.set('externals', {
        vue: 'Vue',
        'vue-router': 'VueRouter',
        axios: 'axios',
        echarts: 'echarts',
      })
      // 首页自定义，添加一个变量来控制html模版，是否加载cdn资源。
      config.plugin('html').tap(args => {
        args[0].isProd = true
        return args
      })
    })
    // 开发模式
    config.when(process.env.NODE_ENV === 'development', config => {
      // 开发模式
      // 首页自定义
      config.plugin('html').tap(args => {
        args[0].isProd = false
        return args
      })
    })
	},
  // 设为false打包时不生成.map文件
  productionSourceMap: false
  // 这里写你调用接口的基础路径，来解决跨域，如果设置了代理，那你本地开发环境的axios的baseUrl要写为 '' ，即空字符串
  // devServer: {
  //   proxy: 'localhost:3000'
  // }
}
