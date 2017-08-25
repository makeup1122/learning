<?php
/**
 * 图书馆用户身份验证接口
 *
 * @param [type] $rdid
 * @param [type] $password
 * @param [string] $默认为身份验证
 * @return bool 是否验证成功
 */
function ReaderApi($rdid,$password,$function='loginByRdid'){
    $url= C("SX_LIB_API_WSDL_URL");
    if(empty($url)){
        return 'not set wsdl url!';
    }
    if(empty($rdid)||empty($password)){
        return "params isn't set!";
    }
    try{
        //初始化SoapClinet
        $client = new SoapClient($url,['trace'=>true]);  
        //获取可用函数列表
        // if($client->__getFunctions()){
        //     return false;
        // }

        // 方法一
        // $param->rdid = $rdid;
        // $param->password = $password;
        // $result = $client->loginByRdid($param);
        // 方法二
        $result = $client->__soapCall($function,['parameters'=>["rdid"=>$rdid,"password"=>$password]]);
        
        // 方法三 需要自定义xml参数属性的时候
        // $param_rdid = new SoapParam($rdid,'rdid');
        // $param_password = new SoapParam($password,'password');
        // $result = $client->loginByRdid([$param_rdid]);

        //开启debug跟踪调试
        // var_dump($client->__getLastRequest());
        // var_dump($client->__getLastRequestHeaders());
        // var_dump($client->__getLastResponse());
        // var_dump($client->__getLastResponseHeaders());
        return $result;
    }catch(Exception $e){
        return $e->getMessage();
    }  
}