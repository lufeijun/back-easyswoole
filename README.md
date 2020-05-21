# 说明
  使用 easyswoole 开发的管理系统的后端代码
  
  
  
# 修改部分

## 参数接收
   vue-element-admin 前端框架在发送的请求时会携带一个头部信息：Content-Type: application/json;charset=UTF-8，导致使用框架提供的 getRequestParam 方法接收不到参数，所以在 BaseController 中新增了一个 input 方法，用来接受请求参数
   
```$xslt
public function input( String $key , $default = '' )
{
    $result = $this->request()->getRequestParam($key);

    if ( $result ) {
        return $result;
    }

    $arr = json_decode($this->request()->getSwooleRequest()->rawContent(),true);

    if ( isset( $arr[$key] ) ) {
        return $arr[$key];
    }


    return $default;
}
```


## 用户登录验证

  在登录成功之后，使用 JSON Web Token（JWT） 提供的功能生成 token 传递到前端，前端在每次请求时，都会将 token 放在 header 中一起传递过来。登录验证部分在 JwtController::onRequest 的方法中
  


# 部署

* 通过 git 下载代码

* 设置配置，主要是 mysql 的相关配置，将 easyswoole.sql 的数据导入数据库中

* 运行 easyswoole 框架

```$xslt
php easyswoole start
```

* 配置 web 服务器
```$xslt
server {
    listen       80;
    server_name back.vueelement.com;

    # 当获取图片时
    location ~ \.(gif|jpg|png|jpeg)$ {
      root path";
    }

    # Nginx 处理静态资源，LaravelS 处理动态资源
    location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header X-Real-IP $remote_addr;
        if (!-f $request_filename) {
             proxy_pass http://127.0.0.1:9501; // easyswoole 开启是指定的端口
        }
    }
}
```
