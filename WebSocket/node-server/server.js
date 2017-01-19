//Socket.io 的模型

const http = require("http");
const socket = require('socket.io');

var server = http.createServer((req,res)=>{
    res.end('hello world!');
});
var io = new socket(server);
var i = 0;
io.on('connection',function(socket){
    setInterval(function() {
      socket.emit('news',{hello:'world',id:(i++)});
    }, 1000);
    socket.on('my other event',function(data){
        console.log(data);
    })  
});
server.listen(8888);

//WebSocket的模型
