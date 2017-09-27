const assert = require('assert');

var test = 1;
console.log(assert(test,'错误')); //undefined
console.log(assert.ok(test,'错误')); //undefined

var testEqual = 1;
console.log(assert.equal(test,testEqual,'相等'));
console.log(assert.notEqual(test,testEqual,'不想等'));

var testDeepEqual = '1';
console.log(assert.deepEqual(test,testDeepEqual,'不深度相等')); //相等
console.log(assert.deepEqual({ a: 1 }, { a: '1' })); //相等\
//测试 actual 参数与 expected 参数是否不深度相等。 与 assert.deepEqual() 相反。
console.log(assert.notDeepEqual({ a: 1 }, { a: '1' },'深度相等'))

var strictEqual = 1;
console.log(assert.strictEqual(test,strictEqual,'全等'))
console.log(assert.notStrictEqual(test,strictEqual,'不全等'))

// var testDeepStrictEqual = '1'; //error
var testDeepStrictEqual = 1.0; //相等
console.log(assert.deepStrictEqual(test,testDeepStrictEqual,'不深度全等'))
console.log(assert.notDeepStrictEqual(test,testDeepStrictEqual,'深度全等'))


assert.throws();
//判断异常类型
// assert.doesNotThrow(
//     () => {
//       throw new TypeError('错误信息');
//     },
//     SyntaxError
//   );
// assert.doesNotThrow(
//     () => {
//       throw new SyntaxError('错误信息');
//     },
//     SyntaxError,
//     '类型不匹配'
//   );

//  assert.fail(1,1,'不相等');

// 可用于测试回调函数的 error 参数。
assert.ifError('das');
