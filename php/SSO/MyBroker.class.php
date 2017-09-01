<?php
namespace Libs\Util;
require 'vendor/autoload.php';

Class MyBroker {
    static public function login($username,$password){
        $sso_config = require(getcwd().'/Ssoserver/config.php');
        $broker = new \Jasny\SSO\Broker($sso_config['SSO_SERVER_URL'],'culture','agdff34t8iwzik1bwd');
        $broker->attach(true);
        $broker->login($username, $password);
    }
    static public function logout(){
        $sso_config = require(getcwd().'/Ssoserver/config.php');
        $broker = new \Jasny\SSO\Broker($sso_config['SSO_SERVER_URL'],'culture','agdff34t8iwzik1bwd');
        $broker->attach(true);
        $broker->logout();
    }
    static public function getUserInfo(){
        $sso_config = require(getcwd().'/Ssoserver/config.php');
        $broker = new \Jasny\SSO\Broker($sso_config['SSO_SERVER_URL'],'culture','agdff34t8iwzik1bwd');
        $broker->attach(true);
        return $broker->getUserInfo();
    }
}