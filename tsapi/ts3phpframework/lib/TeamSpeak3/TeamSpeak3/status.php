<?php
date_default_timezone_set("Asia/Shanghai");
require_once("TeamSpeak3.php");
TeamSpeak3::init();
header('Content-Type: text/html; charset=utf8');
$status = "offline";
$count = 0;
$max = 0;
try {
    $ts3 = TeamSpeak3::factory("serverquery://serveradmin:cWuW0GJh@127.0.0.1:10011/?server_port=9987&use_offline_as_virtual=1&no_query_clients=1");
    $status = $ts3->getProperty("virtualserver_status");
    $count = $ts3->getProperty("virtualserver_clientsonline") - $ts3->getProperty("virtualserver_queryclientsonline");
    $clientList=$ts3->clientList();
    $max = $ts3->getProperty("virtualserver_maxclients");
}
catch (Exception $e) {
    echo '<div style="background-color:red; color:white; display:block; font-weight:bold;">QueryError: ' . $e->getCode() . ' ' . $e->getMessage() . '</div>';
}
echo '<span class="ts3status">TS3服务器状态: ' . $status . '</span><br/>
<span class="ts3_clientcount">在线人数: ' . $count . '/' . $max . '</span><br><br>
';
if ($count >0){
	echo'<span class="ts3status">以下为在线客户端:</span><br>';
	foreach($clientList as $ts3_Client)
	{
 	echo $ts3_Client . " 在用 " . $ts3_Client["client_platform"] . "平台，软件版本" . $ts3_Client["client_version"] .  "<br />\n";
	}echo'<a href="/tree.php">以树状图进行查看</a><br>';
}
echo'
<a href="ts3server://gz.ddxnb.cn">加入服务器</a>';
?>
