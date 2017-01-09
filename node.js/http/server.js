const http = require('http');
const server = http.createServer((req,res)=>{
    res.end('hello world!');
});
//客户端发生错误时，不在有req,res对象
server.on('clientError',(err,socket)=>{
    socket.end('HTTP/1.1 400 Bad Request\r\n\rn');
});
// server.on('open')
// server.close();
server.on('close',function(){
    console.log('close here!');
});
server.on('request',function(req,res){
    console.log('new request!');
});
server.on('upgrade',function(req,res){
    // console.log(data);
});
server.on('connection',function(socket){
    console.log('emitted connection here!');
});
server.on('connect',function(request,socket,head){
    console.log('emitted connect here!');
    console.log('head is ['+head+']');
});
// server.listen(handle[, callback])
// server.listen(path[, callback])
// server.listen([port][, hostname][, backlog][, callback])
//当port为0时，系统随机分配端口号
//backlog时最大链接数据，取决于你的系统本身，比如linux的tcp_max_syn_backlog和somaxconn，默认值是511
server.listen(8888,function(){
    console.log('start server here!');
    if(server.listening){//布尔值
        console.log('listen on port ['+server.address().port+']');
        console.log('server.maxHeadersCount=['+server.maxHeadersCount+']');
        // console.log('server.maxHeadersCount=['+server.connections+']');
    }
});
server.on('listening',function(){
    console.log('emitted listening here!');
});

//超时
//默认超时时2min  ，单位为毫秒
server.setTimeout(120000,function(){
});
//超时事件
server.on('timeout',function(socket){
    //server.timeout 返回当前默认时间
    console.log('more then '+server.timeout+' and you\'re timeout!');
})