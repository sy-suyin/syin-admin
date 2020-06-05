## token

> 用于接口请求数据时身份认证

在用户登录成功时, 后端会返回 token 与 refresh_token

token 有效时间很短, 一般为几分钟, 过期需用 refresh_token 获取新 token

token 使用jwt生成, 加密方法为RS256. 在项目开始前需更换当前使用的公钥私钥

## refresh_token

> refresh_token 用于token过期时, 从服务器获取新token

refresh_token 有效时间很长, 可以设置为几天. 但是只有登录时才会生成, 一旦失效必须退出登录

token 使用jwt生成, 加密方法为HS256. 在项目开始前需更换当前使用的加密秘钥

## Token设置过期

前端在登录时, 可提供 client_id, 后端将 client_id + 登录账号id 拼接, 设置当前时间为client_id生效时间

当token的签发时间小于client_id对应生效时间, token即为无效

此功能暂未实现, 如有需要可按需添加

## 多标签页

> 在开启多个标签页的情况下, 如果第二个标签页退出登录或更新token, 第一个标签页不会更新

为解决多个标签页问题

- 当用户登录时, 系统会生成并存储 login_time, login_time 为当前时间

- 更新token时, 系统会生成并存储 auth_time, auth_time 为当前时间

- 当用户退出登录时, 清除所有 localStorage 存储的数据, 并刷新页面

在每次发送请求时, 通过获取token进行判断状态

0. 系统会将vuex中存储的 auth_time 与 localStorage 中存储的 auth_time 进行比较

1. 如果 localStorage 中存储的 auth_time 为 null, 即认定为其他标签页已退出登录

2. 如果 localStorage 中存储的 auth_time 比 vuex 中的时间要晚, 则可以判断token已被更新, 将会检查 login_time

	0. 因为localStorage 中存储的 token 更新, 所以可以判定其他标签页是在当前标签页之后修改的

	1. 如果 localStorage 中存储的 login_time 比 vuex 中的时间要晚, 可认定为其他标签页已重新登录, 此时将刷新该标签页

	2. 如果 1 不成立, 则重新读取 localStorage 中的 token, 替换 vuex 中的数据

## Token传递

前端token传递给后端时, 数据将写入header, 详情如下

```
$header = [
	'access_token'  => $token_info['access_token'],
	'refresh_token' => $token_info['refresh_token'],

	// 获取新token的请求链接
	'refresh_token_url' => $token_info['refresh_token_url'],

	'token_type'    => 'bearer',

	// token 有效期
	'expires'       => $token_info['token_expire'],
]
```

后端传递token给前端时, 应将数据写header中, 格式如下

```
config.headers['Authorization'] = 'Bearer ' + token;
```

## token刷新流程

当 token 失效时, 系统会将Token的 is_request 字段设置为 true, 并开始请求获取新token

同时执行的请求将会由监听者模式 **(Observer 类)** 接管, 在token完成后再执行

```
// Token.js
static refreshToken(){
	return new Promise((resolve, reject) => {
		if(this.is_request){
			Observer.on('refresh_token_end', (token)=>{
				resolve(token);
			});
		}else{
		}
	})
});
```

对于在正在请求新token时发起的数据请求, 请求将会加入请求队列中, 在token更新后再执行

```
// Request.js
queue(fn){
	if(Token.isRequest()){
		return new Promise((resolve, reject) => {
			Observer.on('refresh_token_end', (token)=>{
				resolve(fn());
			});
		});
	}else{
	}
}
```

## 参考

https://www.oauth.com/oauth2-servers/making-authenticated-requests/refreshing-an-access-token/

https://www.cnblogs.com/XiongMaoMengNan/p/6785155.html

https://learnku.com/articles/17883

https://www.oauth.com/oauth2-servers/making-authenticated-requests/