var mongoose = require('mongoose')
mongoose.connect('mongodb://localhost:27017/test')

var db = mongoose.connection;
db.on('error',console.error.bind(console,'connection error:'))

db.once('open',function(){
    console.log('mongoDB open here!');
    //结构
    var kittySchema = mongoose.Schema({
        name:String
    })
    kittySchema.methods.speak = function(){
        var greeting = this.name ?"Meow name is "+this.name:"I don't have a name"
        console.log(greeting);
    }
    //model实例
    var kitten = mongoose.model('Kitten',kittySchema)
    
    //数据
    var silence = new kitten({name:'Silence'})
    console.log(silence);

    //函数
    var fluffy = new kitten({name:'fluffy'})
    // fluffy.speak();
    
    //保存数据
    fluffy.save(function(err,fluffy){
        if(err) return console.error(err)
        fluffy.speak();
    })
})


