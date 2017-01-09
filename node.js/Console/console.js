// 常用用法
console.log('hello world!');
console.log('hello %s','world');
// console.error(new Error('Whoops,something bad happend'));
const name = 'Li Bing';
console.warn(`Danger ${name} Danger!`);

//言断
console.assert(true,'t1rue');

//有待了解
const util = require('util');
var opts = {
    'showHidden':true,
    'depth':null,
    'colors':true};
console.dir(util.inspect('test'),opts);

//设置时间的起止标签
console.time('libing_label');
//6.0版本以后 调用timeEnd会清除计时器，之前的版本可以多次调用
console.timeEnd('libing_label'); 

//别名
console.info('console.info() is an alias for console.log()');
console.warn('console.warn() is an alias for console.error()');

//打印代码栈信息
console.trace('show me');
console.trace(opts);
//创建Console类对象
// const Console = require('console').Console;
//或者
const Console = console.Console;

//创建一个简单的自定义日志方法
// new Console(process.stdout, process.stderr);
const fs = require('fs');
const output = fs.createWriteStream('./stdout.log');
const errorOutput = fs.createWriteStream('./stderr.log');
const myLogger = new Console(output,errorOutput);
var count = 5;
myLogger.log('count:%d',count);
myLogger.error(`test ${count}`);

