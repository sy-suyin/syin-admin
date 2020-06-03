# 概述

## token

> 用于接口请求数据时身份认证

在用户登录成功时, 后端会返回 token 与 refresh_token

token 有效时间很短, 一般为几分钟. 在请求时 token 一般写入 header 中提交

token 使用jwt生成, 加密方法为RS256. 在项目开始前需更换当前使用的公钥私钥

## refresh_token

token 用于请求数据时授权

> refresh_token 用于token过期时, 从服务器获取新token

refresh_token 有效时间很长, 可以设置为几天. 但是只有登录时才会生成, 一旦失效必须退出登录

token 使用jwt生成, 加密方法为HS256. 在项目开始前需更换当前使用的加密秘钥

## token设置过期

前端在登录时, 可提供 client_id, 后端将 client_id + 登录账号id 拼接, 设置当前时间为client_id生效时间

当token的签发时间小于client_id对应生效时间, token即为无效

此功能暂未实现, 如有需要可按需添加

# token刷新流程

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