<?php

// +----------------------------------------------------------------------
// | ShuipFCMS 前台Controller
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.shuipfcms.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 水平凡 <admin@abc3210.com>
// +----------------------------------------------------------------------

namespace Common\Controller;
use Admin\Service\User;
use Libs\Util\MyBroker;

class Base extends ShuipFCMS {

    //初始化
    protected function _initialize() {
        parent::_initialize();
        //静态资源路径
        $this->assign('model_extresdir', self::$Cache['Config']['siteurl'] . MODULE_EXTRESDIR);
        $this->sync_login();
    }

    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    protected function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {

       $this->view->display(parseTemplateFile($templateFile), $charset, $contentType, $content, $prefix);
       
    }

  protected  function getCategory($catid, $field = '', $newCache = false) {
    if (empty($catid)) {
        return false;
    }
    $key = 'getCategory_' . $catid;
  
    //强制刷新缓存
    if ($newCache) {
        S($key, NULL);
    }
    $cache = S($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据
        $cache = M('Category')->where(array('catid' => $catid))->find();

        if (empty($cache)) {
            S($key, 'false', 60);
            return false;
        } else {
            //扩展配置
            $cache['setting'] = unserialize($cache['setting']);
            //栏目扩展字段
            $cache['extend'] = $cache['setting']['extend'];
            S($key, $cache, 3600);
        }
    }
    if ($field) {
        //支持var.property，不过只支持一维数组
        if (false !== strpos($field, '.')) {
            $vars = explode('.', $field);
            return $cache[$vars[0]][$vars[1]];
        } else {

            return $cache[$field];
        }
    } else {
        
        return $cache;
    }
    
}
    public function sync_login(){
        if(!IS_GET){return true;}
        require_once 'vendor/autoload.php';
        $sso_config = require(getcwd().'/Ssoserver/config.php');
        if(!$sso_config['SSO_SERVER_OPEN']){return null;}
        //获取Broker
        try{
            $userBroker = MyBroker::getUserInfo();
        } catch (NotAttachedException $e) {
        } catch (SsoException $e) {
        }
        if(!empty($userBroker) && !service('Passport')->isLogged()){
            // 本地同步登陆
            // var_dump($userBroker);
              //注册用户登陆状态
            if (service('Passport')->registerLogin($userBroker, $cookieTime ? 86400 * 180 : 86400)) {
                $map['userid'] = $userBroker['userid'];
                $map['username'] = $userBroker['username'];
                //修改登陆时间，和登陆IP
                D("Member/Member")->where($map)->save(array(
                    "lastdate" => time(),
                    "lastip" => get_client_ip(),
                    "loginnum" => $userinfo['loginnum'] + 1,
                ));
            }
        }else if(empty($userBroker) && service('Passport')->isLogged() && !strpos($_SERVER['HTTP_REFERER'],'doLogin')){
            //本地同步退出
            service("Passport")->logoutLocal();
            session("connect_openid", NULL);
            session("connect_app", NULL);
            //注销在线状态
            D('Member/Online')->onlineDel();
            //tag 行为点
            tag('action_member_logout');
            //后台用户退出
            User::getInstance()->logout();
            //手动登出时，清空forward
            cookie("forward", NULL);
            session('login_type',null);
            
        }else{
            // nothing to do
        }
    }
}
