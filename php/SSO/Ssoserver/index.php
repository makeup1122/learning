<?php
require_once './Myssoserver.php';
$ssoServer = new Myssoserver();
$command = isset($_REQUEST['command']) ? $_REQUEST['command'] : null;

if (!$command || !method_exists($ssoServer, $command)) {
    header("HTTP/1.1 404 Not Found");
    header('Content-type: application/json; charset=UTF-8');
    
    echo json_encode(['error' => 'Unknown command']);
    exit();
}
// $log = fopen('./log.txt','a+');
// fwrite($log,$_SERVER['REQUEST_TIME']."  ");
// fwrite($log,$_SERVER['REQUEST_METHOD']."  ");
// fwrite($log,$_SERVER['REMOTE_ADDR']."  ");
// fwrite($log,$_SERVER['HTTP_REFERER']."  ");
// fwrite($log,$_REQUEST['command']."  ");
// fwrite($log,json_encode($_REQUEST)."\n");
// fclose($log);
$result = $ssoServer->$command();
exit;