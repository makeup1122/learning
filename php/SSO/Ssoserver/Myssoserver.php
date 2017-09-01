<?php
// +----------------------------------------------------------------------
// | 单点登陆服务端
// +----------------------------------------------------------------------
// | Author: libing <makeup1123@163.com>
// +----------------------------------------------------------------------
require '../vendor/autoload.php';
use \Jasny\ValidationResult;
use \Jasny\SSO\Server;
class Myssoserver extends Server {

    protected $db = null;
    function __construct() {
        parent::__construct();
        $dbconfig = include_once('../shuipf/Common/Conf/dataconfig.php');
        $this->db = new mysqli($dbconfig['DB_HOST'],$dbconfig['DB_USER'],$dbconfig['DB_PWD'],$dbconfig['DB_NAME']);
    }
    protected function executeSQL($sql = null){
        if(!isset($sql)){return null;}

        return $this->db->query($sql);
        
    }
    private static $brokers = [
        'culture' => ['secret'=>'agdff34t8iwzik1bwd'],
        'tpshop' =>  ['secret'=>'89435hjh47pypox2pc'],
        'smeoa' =>   ['secret'=>'ceda63km43rf54y5hp']
    ];
	 /**
     * Authenticate using user credentials
     *
     * @param string $username
     * @param string $password
     * @return \Jasny\ValidationResult
     */
     protected function authenticate($username, $password){
         if (!isset($username)) {
            return ValidationResult::error("username isn't set");
         }
         if (!isset($password)) {
            return ValidationResult::error("password isn't set");
         } 
         $sql = sprintf("select * from cu_member where username = '%s'",$username);
         $result = $this->executeSQL($sql);
         if($result && $result->num_rows <=0){
            return ValidationResult::error("username isn't  existed");
         }
         $user = $result->fetch_assoc();
        if($user['password'] != md5($password . md5($user['encrypt']))){
            return ValidationResult::error("incorrect password");
        }
        return ValidationResult::success();
     }

    /**
     * Get the secret key and other info of a broker
     *
     * @param string $brokerId
     * @return array
     */
     protected function getBrokerInfo($brokerId){
        return isset(self::$brokers[$brokerId]) ? self::$brokers[$brokerId] : null;
     }

    /**
     * Get the information about a user
     *
     * @param string $username
     * @return array|object
     */
     protected function getUserInfo($username){
        $sql = sprintf("select userid,username,nickname,email,mobile,loginnum from cu_member where username = '%s'",$username);
        $result = $this->executeSQL($sql);
        $user = $result->fetch_assoc();
        if($result && $user){
            return $user;
        }else{
            return [];
        }
     }
     //服务端注册
     public function register(){
         $username = $_POST['username'];
         $password=$_POST['password'];
           $encrypt=$this->genRandomString(6);
          $psd =$this->encryption($password, $encrypt);
          if(empty($username)||empty($password)){$this->replay(['state'=>'fail']); }
         $sql = sprintf("INSERT INTO `cu_member` (`userid`, `username`, `password`, `encrypt`, `checked`, `sex`, `about`, `heat`, `theme`, `praise`, `attention`, `fans`, `share`, `nickname`, `userpic`, `regdate`, `lastdate`, `regip`, `lastip`, `loginnum`, `email`, `groupid`, `areaid`, `amount`, `point`, `modelid`, `message`, `islock`, `vip`, `overduedate`, `type`, `legal_person`, `business_license`, `registered_capital`, `business_address`, `registration_type`, `business_scope`, `business_license_pic`, `tax_pic`, `mobile`) VALUES ('','$username','$psd','$encrypt', '1', '0', '', '0', '', '0', '0', '0', '0', '', '', '0', '0', '', '', '0', '', '0', '0', '0.00', '0', '0', '0', '0', '', '', '1', '', '', '', '', '', '', '', '', '');");
        
        $result = $this->executeSQL($sql);
      
        if($result){
            $this->replay(['reg'=>'success']);
        }else{
             $this->replay(['reg'=>'fail1']);
            }
       
     }
     //服务端修改密码
     public function changepassword(){
        //参数
        $old_password = isset($_POST['oldpassword'])?$_POST['oldpassword']:'';
        $new_password = isset($_POST['newpassword'])?$_POST['newpassword']:'';
        $username = isset($_POST['username'])?$_POST['username']:'';
        if(empty($old_password) ||empty($new_password) ||empty($username) ){
            $this->replay(['status'=>'error','msg'=>'参数缺失']);    
        }

        //更新新密码
        $encrypt=$this->genRandomString(6);
        $psd =$this->encryption($new_password, $encrypt);
        $sql = sprintf("UPDATE `cu_member` SET `password`='%s', `encrypt`='%s' WHERE `username`='%s';",$psd,$encrypt,$username);
        $result = $this->executeSQL($sql);
        if(!$result){
            $this->replay(['status'=>'error','msg'=>'修改失败']);
        }
        $this->replay(['status'=>'success','msg'=>'修改成功']);
     }
     //验证服务端用户名是否可用
     public function checkusername(){
        $username = $_POST['username'];
        if(empty($username)){$this->replay(['state'=>'fail']); }
        $sql = sprintf("select username from cu_member where username = '%s'",$username);
       
        $result = $this->executeSQL($sql);
        if($result && $result->num_rows >=1){
           return $this->replay(['state'=>'fail']);
        }else{
            return $this->replay(['state'=>'success']);
        }
     }
     //同步账号
     public function syncuser(){
         
     }
     //统一回复
     protected function replay($result){
        //客户端同意设置
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($result);exit;
     }
     //析构
     function __destruct(){
        //关闭mysql连接
        mysqli_close($this->db);
     }

     //密码的生成
     private function encryption($pass, $verify = "") {
        $pass = md5($pass . md5($verify));
        return $pass;
     }
     //随机码
     private function genRandomString($len = 6) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱 
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

}

