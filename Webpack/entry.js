// require("!style-loader!css-loader!./style.css"); // 载入 style.css
require('./style.css');
//ro 
//require("./style.css")  with  webpack entry.js bundle.js --module-bind 'css=style!css'
document.write('It works.');
document.write(require('./module.js'));//添加模块