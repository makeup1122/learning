<?php
namespace Libs\Util;
/**
 * 百度统计接口
 */
//登陆接口
define('LOGIN_URL', 'https://api.baidu.com/sem/common/HolmesLoginService');
//API URL
define('API_URL','https://api.baidu.com/json/tongji/v1/ReportService/');
//USERNAME
define('USERNAME', 'haomantech');
//PASSWORD
define('PASSWORD', 'Ha0man2017');
//TOKEN
define('TOKEN', '7f65e4468063df70139f02942dec25c3');
//UUID, used to identify your device, for instance: MAC address
define('UUID', 'culture');
//ACCOUNT_TYPE
define('ACCOUNT_TYPE', 1); //ZhanZhang:1,FengChao:2,Union:3,Columbus:4
class BaiduTJ {
    private $publicKey = null;
    private $returnCode = null;
    private $ucid = null;
    private $st = null;
    private $errorMsg = null;
    
    public $header = null;
    public $body = null;
    public $raw = null;
    //构造函数
    function __construct(){
        list($this->ucid,$this->st) = $this->doLogin();
        // echo 'ucid = '.$this->ucid.PHP_EOL;
    }
    //析构函数
    function __destruct() {
        $this->doLogout();
    }
    public function getError(){
        return $this->errorMsg;
    }
    /**
     * 获取账号下可用站点列表
     *
     * @return array $list
     */
    public function getSiteList(){
        $getSiteListData = array(
            'header' => array(
                'username' => USERNAME,
                'password' => $this->st,
                'token' => TOKEN,
                'account_type' => ACCOUNT_TYPE,
            ),
            'body' => null,
        );
        list($this->header,$this->body,$this->raw) = $this->postReport($getSiteListData,"getSiteList");
        if(empty($this->body)){
            $this->errorMsg = $header['failures'][0]['message'];
            return false;
        }
        return $this->body['data'][0]['list'];
    }
    /**
     * 获取站点统计数据
     * 参数数组内容
     * 'site_id' => $siteId,                   //站点ID
     * 'method' => 'trend/time/a',             //趋势分析报告
     * 'start_date' => '20160501',             //所查询数据的起始日期
     * 'end_date' => '20160531',               //所查询数据的结束日期
     * 'metrics' => 'pv_count,visitor_count',  //所查询指标为PV和UV
     * 'max_results' => 0,                     //返回所有条数
     *  'gran' => 'day',                        //按天粒度
     * @param [type] $site
     * @return array $dataee
     */
    public function getReportData($parameters){
        $getReportData = array(
            'header' => array(
                'username' => USERNAME,
                'password' => $this->st,
                'token' => TOKEN,
                'account_type' => ACCOUNT_TYPE,
            ),
            'body' => $parameters,
        );
        list($this->header,$this->body,$this->raw) = $this->postReport($getReportData,"getData");
        if(empty($this->body)){
            $this->errorMsg = $header['failures'][0]['message'];
            return false;
        }
        return $this->body['data'][0]['result'];
    }
    /**
     * 生产post数据
     *
     * @param [type] $data
     * @return void
     */
    private function genPostData($data) {
        //压缩比 0-不压缩 9-最大压缩 默认值应该在php.ini里设置
        $gzData = gzencode(json_encode($data), 9);
        for ($index = 0, $enData = ''; $index < strlen($gzData); $index += 117) {
            $gzPackData = substr($gzData, $index, 117);
            $enData .= $this->pubEncrypt($gzPackData);
        }
        // $this->postData = $enData;
        return $enData;
    }
    /**
     * 加密数据
     *
     * @param [type] $data
     * @return void
     */
    private function pubEncrypt($data){
        $this->getPubKey();
        $ret = openssl_public_encrypt($data, $encrypted, $this->publicKey);
        if($ret)
        {
            return $encrypted;
        }
        else
        {
            return null;
        } 
    }
    /**
     * 获取公钥
     *
     * @return void
     */
    private function getPubKey(){
        if(is_resource($this->publicKey))
        {
            return true;
        }
        $file = getcwd().DIRECTORY_SEPARATOR."shuipf".DIRECTORY_SEPARATOR."Libs".DIRECTORY_SEPARATOR."Util".DIRECTORY_SEPARATOR."baidu_api_pub.key";
        if(file_exists($file)){
            $puk = file_get_contents($file);
            $this->publicKey = openssl_pkey_get_public($puk);
            return true;
        }else{
            echo 'no pub key!'.PHP_EOL;exit;
        }
    }
    /**
     * 预登陆
     *
     * @return void
     */
    private function perLogin(){
        //TODO
    }
    /**
     * 登陆函数
     *
     * @return void
     */
    private function doLogin(){
        //准备登陆数据
        $doLoginData = array(
            'username' => USERNAME,
            'token' => TOKEN,
            'functionName' => 'doLogin',
            'uuid' => UUID,
            'request' => array(
                'password' => PASSWORD,
            ),
        );
        //生成加密登陆数据
        $postData = $this->genPostData($doLoginData);
        $retArray = $this->postLogin($postData);
        if($retArray && $retArray['retcode'] === 0) {
            return array(
                $retArray['ucid'],
                $retArray['st'],
            );
        }else{
            return false;
        }
    }
    /**
     * 退出函数
     *
     * @return void
     */
    private function doLogout(){
        $doLogoutData = array(
            'username' => USERNAME,
            'token' => TOKEN,
            'functionName' => 'doLogout',
            'uuid' => UUID,
            'request' => array(
                'ucid' => $this->ucid,
                'st' => $this->st,
            ),
        );
        //生成加密登陆数据
        $postData = $this->genPostData($doLogoutData);
        $retArray = $this->postLogin($postData);
        if($retArray && $retArray['retcode'] === 0) {
            echo 'logout success!';
            return true;
        }else{
            return false;
        }
    }
    /**
     * 发送登陆/退出请求
     *
     * @param [type] $data
     * @return void
     */
    private function postLogin($data){
        $headers = array('UUID: '.UUID, 'account_type: '.ACCOUNT_TYPE, 'Content-Type:  data/gzencode and rsa public encrypt;charset=UTF-8');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, LOGIN_URL);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            echo '[error] CURL ERROR: ' . curl_error($curl). PHP_EOL;
        }
        curl_close($curl);
        $this->returnCode = ord($tmpInfo[0])*64 + ord($tmpInfo[1]);
        if ($this->returnCode === 0) {
            $retData = substr($tmpInfo, 8);
            try{
                $data = gzdecode($retData);
            }catch(Exception $e){
                var_dump($e);
            }
            $retArray = json_decode($data, true);
            return $retArray;
        }else{
            return false;
        }
    }
    /**
     * 请求报告数据
     *
     * @param [type] $data
     * @return void
     */
    private function postReport($data,$function=''){
        if(empty($function) || empty($data)){
            return false;
        }
        $postData = json_encode($data);
        $curl = curl_init();
        $api_url = API_URL."/".$function;
        $headers = array('UUID: '.UUID, 'USERID: '.$this->ucid, 'Content-Type:  data/json;charset=UTF-8');
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $tmpRet = curl_exec($curl);
        if (curl_errno($curl)) {
            echo '[error] CURL ERROR: ' . curl_error($curl) . PHP_EOL;
        }
        curl_close($curl);
        $tmpArray = json_decode($tmpRet, true);
        if (isset($tmpArray['header']) && isset($tmpArray['body'])) {
            return [$tmpArray['header'],$tmpArray['body'],$tmpRet];
        }
        else {
            echo "[error] SERVICE ERROR: {$tmpRet}" . PHP_EOL;
        }
    }
}