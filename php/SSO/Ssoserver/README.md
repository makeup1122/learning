## 单点登陆说明
---
### 依赖 
#### 库
[Jasny/SSO](https://github.com/jasny/sso)
#### 环境
1. php5.6+
2. apache

### 问题总结
1. Broker的构造函数中的第一个参数url,需为外网可访问地址，不能设置为内网或本地地址。
2. Server中用到了PHP中的[`getallheader()`](http://php.net/manual/zh/function.getallheaders.php)函数，该函数为`apache_request_headers()`的别名，所以只能在Apache服务器中使用，nginx下为`undefindd`。
在nginx中使用如下代码可以解决：
```
    $headers = ''; 
    if (!function_exists('getallheaders')) 
    { 
        foreach ($_SERVER as $name => $value) 
        { 
            if (substr($name, 0, 5) == 'HTTP_') 
            { 
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_',', substr($name, 5)))))] = $value; 
            } 
        } 
    }else{
        $headers = getallheaders();
    }
```
3. 因为Session的关系，Server的部署必须与任意Broker完全隔离(即hostname和port不能相同)。

### 20170522 遗留问题
问题总结中的*问题2*可以解决nginx中无法使用`getallheaders()`的问题，但是在47服务的nginx中，Server代码中的`session_start()`总是执行到timeout。
然而将SsoServer单独部署到apache后就没有任何问题，单独在nginx中执行`session_start()`，也不会执行timeout。

### 20170526 更新
以下新发现：
1. Broker第一次访问Server进行Attach时，是直接用php的`header`函数进行http跳转，而当Attach之后，第二次与Server通信获取用户数据(`getUserinfo`)时，用的`curl`函数。
2. 在php环境中如果两个项目部署在同一个域名或者IP，只以端口号来区分，那么这两个项目的session变量是相同的，即同一用户在两个项目中的session是共享的......
3. 浏览器访问站点，后台调用`session_start`开始获取session时，php会锁住session文件以防冲突，直到php脚本执行结束或者手动调用了`session_write_close`函数。

基于以上3点，如果SSOserver和culture等工程仅以端口区分的方式来部署，那么当culture中的Broker以`curl`方式访问SSOserver时，此时的session文件其实已经被culture锁定，且进程还未结束，所以并未释放session文件。
而且在SSOserver接收到culture的Broker的请求，而想要调用`session_start()`时，由于session文件已经先被culture锁定，所以就会进入等待从而导致两个工程相互锁死，直至请求timeout。

解决办法：
1. 如0522的办法，把ssoserver和culture部署在两个不同的中间件内。
2. 在SSOserver的Server代码的构造函数中添加如下代码：
```
@ini_set('session.name', 'PHPSESSID_SSOSERVER');
```
这样即可区分session文件，不会锁死。