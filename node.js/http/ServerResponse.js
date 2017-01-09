const http = require('http');
//http.ServerResponse 对象 是系统通过server对象创建
var server = http.createServer((req,Response)=>{
    //response.addTrailers(headers);
    //状态检测
    console.log('response.finished is ['+Response.finished+']');
    
    //单个处理设置http header值
    Response.setHeader('content-type','text');
    //获取http header值
    Response.getHeader('content-type');
    //删除http header值
    Response.removeHeader('content-type');

    //http header DATE
    //默认位true,即自动发送服务器时间到客户端
    Response.sendDate = false;
    
    //状态码
    console.log('状态码:'+Response.statusCode);
    //设置状态码
    Response.statusCode = 200;
    //状态消息
    console.log('状态消息:'+Response.statusMessage);
    //设置状态消息
    Response.statusMessage = 'ok-test';

    //设置http header的状态码和状态消息
    Response.writeHead(200,'hehe');
    //写数据
    //原型 response.write(chunk[, encoding][, callback])
    Response.write('<h3>I\'m libing test here!</h3>');
    
    //原型 response.end([data][, encoding][, callback])
    //每次必须调用，如果data有值，相当于调用response.wirte(data,encoding)
    Response.end('<h1>hello world!</h1> --send by Response.end');
    //当response被发送后出发，之后不会再有任何response事件被触发
    Response.on('finish',()=>{
        console.log('ServerResponse Finish here!');
    });
    Response.on('close',()=>{
        console.log('ServerResponse close here!');
    });
    //超时设置
    Response.setTimeout(120,()=>{
        console.log('response timeout here!');
    });

    //状态检测
    console.log('response.finished is ['+Response.finished+']');
});
server.listen(8888,()=>{
    console.log('start server here!');
    console.log('listen on port ['+server.address().port+']');
});
