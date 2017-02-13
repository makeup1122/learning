// 注册一个全局自定义指令 v-focus
Vue.directive('focus', {
    // 当绑定元素插入到 DOM 中。
    inserted: function (el) {
        // 聚焦元素
        el.focus()
    }
});
var data = {
    question: "",
    explains: false,
    answer: "输入要查询的单词",
    basic: true,
    errorCode: true,
    webs: false,
    phonetic: false,
    uk_phonetic: false,
    us_phonetic: false,
};
var vm = new Vue({
    el: "#app",
    data: data,
    watch: {
        question: function (words) {
            if (words != '') {
                this.answer = '要查询[ ' + words + ' ]? 请直接按下回车!';
            } else {
                this.answer = '';
            }
        }
    },
    methods: {
        getAnswer:
        function () {
            var vm = this;
            if (this.question == '') { return; }
            vm.answer = '有道查询中...'
            $.ajax({
                type: "GET",
                url: "http://fanyi.youdao.com/openapi.do?keyfrom=MWords&key=2006756772&type=data&doctype=jsonp&version=1.1&q=" + this.question,
                dataType: "jsonp",
                jsonpCallback: 'show'
            })
        }
    }
});
function show(data) {
    //获取有道返回码
    vm.errorCode = data.errorCode == 0 ? true : false;
    //提取基本词典翻译
    vm.basic = data.basic;
    //判断是否有数据
    if (vm.errorCode && vm.basic) {
        vm.explains = data.basic.explains;
        vm.phonetic = data.basic.phonetic;
        vm.uk_phonetic = data.basic['uk-phonetic'];
        vm.us_phonetic = data.basic['us-phonetic'];
    }
    //保持记录
    if (vm.errorCode) { saveToLibing(data); }
    //获取网络释义
    vm.webs = data.web;
    //获取基本翻译结果
    vm.answer = "<h3>翻译：" + data.translation + "</h3>";
};
//jsonp回掉函数
function saveResult(data) {
    console.log(data.msg);
};
//ajax记录数据库
function saveToLibing(data) {
    $.ajax({
        type: "GET",
        url: 'http://libing.win:23544/save',
        data: data,
        dataType: "jsonp",
        jsonpCallback: 'saveResult'
    });
};
