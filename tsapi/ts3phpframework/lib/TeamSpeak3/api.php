<?php
date_default_timezone_set("Asia/Shanghai");
require_once("TeamSpeak3.php");
TeamSpeak3::init();
header('Content-Type: application/json; charset=utf8');
$status = "offline";
$count = 0;
$max = 0;
$page_title = "newton_miku的TS服务器状态";
$uptime = "";
$time = "";
try {
    $ts3 = TeamSpeak3::factory("serverquery://serveradmin:passwd@127.0.0.1:10011/?server_port=9987&use_offline_as_virtual=1&no_query_clients=1");
    $status = $ts3->getProperty("virtualserver_status")->__toString();
    if ($status=="online"){$status = "在线";}
    else
    {
    	$status = "离线";
    }
    $count = $ts3->getProperty("virtualserver_clientsonline") - $ts3->getProperty("virtualserver_queryclientsonline");
    $clientList=$ts3->clientList();
    $channelList=$ts3->channelList();
    $max = $ts3->getProperty("virtualserver_maxclients");
    //$uptime = TeamSpeak3_Helper_Convert::seconds($ts3->virtualserver_uptime);
    $time = $ts3->virtualserver_uptime;
    $lefttime = $time%86400;
    $uptime = sprintf('%d 天 %02d 小时 %02d 分钟 %02d 秒', ($time/86400), ($lefttime/3600), ($lefttime/60%60), $lefttime%60);
}
catch (Exception $e) {
	if ($status=="online"){$status = "在线";}
    else
    {
    	$status = "离线";
    }
    $response = array(
    'status' => $status,
    'uptimeStr' => "0 秒",
    'online_count' => "0",
	'max_count' => "0",
    'error' => 'QueryError: ' . $e->getCode() . ' ' . $e->getMessage()
    );
    echo json_encode($response);
    exit;
}

$onlineClients = array();
if ($count >0){
	$time = time();
	foreach($clientList as $ts3_Client)
	{
		$livetime = $time - $ts3_Client["client_lastconnected"];
		$hour = ($livetime/3600>1)?intval($livetime/3600)." 小时":"";
		$min = ($livetime%86400/60%60>1)?($livetime%86400/60%60)." 分钟 ":"";
		$livetimeStr = sprintf('%s%s%02d 秒', $hour, $min, $livetime%86400%60);
		$channel = $channelList[$ts3_Client["cid"]];
		$channelStr = $channel->__toString();
		while ( $channel["pid"] != 0 )
		{
			$channel = $channelList[$channel["pid"]];
			$channelStr = $channel->__toString() . " - " . $channelStr;
		}
		$onlineClients[] = array(
			'name' => $ts3_Client->__toString(),
			'platform' => $ts3_Client["client_platform"]->__toString(),
			'version' => $ts3_Client['client_version']->__toString(),
			'liveTime' => $livetimeStr,
			'channel' => $channelStr
		);
	}
}

$response = array(
	'status' => $status,
	'uptime' => $time,
	'uptimeStr' => $uptime,
	'online_count' => $count,
	'max_count' => $max,
	'online_clients' => $onlineClients
);
echo json_encode($response);
exit;
?>
