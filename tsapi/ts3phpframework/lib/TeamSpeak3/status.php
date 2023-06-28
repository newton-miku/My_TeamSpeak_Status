<?php
date_default_timezone_set("Asia/Shanghai");
require_once("TeamSpeak3.php");
TeamSpeak3::init();
header('Content-Type: text/html; charset=utf8');
$status = "offline";
$count = 0;
$max = 0;
$page_title = "newton_miku的TS服务器状态";
$uptime = "";
$time = "";
echo("<title>$page_title</title>");
try {
    $ts3 = TeamSpeak3::factory("serverquery://serveradmin:passwd@127.0.0.1:10011/?server_port=9987&use_offline_as_virtual=1&no_query_clients=1");
    $status = $ts3->getProperty("virtualserver_status");
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
    echo '<div style="background-color:red; color:white; display:block; font-weight:bold;">QueryError: ' . $e->getCode() . ' ' . $e->getMessage() . '</div>';
}
echo '<span class="ts3status">TS3服务器状态: ' . $status . '</span><br/>
<span id="timeDate">运行时长: ' . $uptime . '</span><span id="times"></span><br>
<span class="ts3_clientcount">在线人数: ' . $count . '/' . $max . '</span><br><br>';
if ($count >0){
	echo'<span class="ts3status">以下为在线客户端:</span><br>';
	foreach($clientList as $ts3_Client)
	{
		#echo "<pre>";print_r($ts3_Client);echo "<pre>";
		$livetime = time() - $ts3_Client["client_lastconnected"];
		$hour = ($livetime/3600>1)?($livetime/3600)."小时":"";
		$min = ($livetime%86400/60%60>1)?($livetime%86400/60%60)." 分钟 ":"";
		$livetimeStr = sprintf('%s%s%02d 秒', $hour, $min, $livetime%86400%60);
 		echo "<br>" . $ts3_Client . " 在用 " . $ts3_Client["client_platform"] . "平台，软件版本" . $ts3_Client["client_version"] .  "在线时长：" . $livetimeStr ."<br />\n";
	}
	echo'<a href="/tree.php">以树状图进行查看</a><br>';
}
echo'<h3>
<a href="ts3server://gz.ddxnb.cn">加入服务器-入口1</a>&nbsp;
<a href="ts3server://tsapi.ddxnb.cn">加入服务器-入口2</a><br></h3>';
echo'
<h2><a href="https://ts3.com.cn/downloads/teamspeak">下载TeamSpeak</a></h2>&nbsp;
';
echo '<script>
	var timesRun = 0;
	function createtime() {
		timesRun += 1;
		starttime = '. $time .'
		time = (starttime + timesRun);
		days = time / 60 / 60 / 24; dnum = Math.floor(days);
		hours = time / 60 / 60 - (24 * dnum); hnum = Math.floor(hours);
		if(String(hnum).length ==1 ){hnum = "0" + hnum;}
		minutes = time / 60 - (24 * 60 * dnum) - (60 * hnum);mnum = Math.floor(minutes); 
		if(String(mnum).length ==1 ){mnum = "0" + mnum;}
        seconds = time - (24 * 60 * 60 * dnum) - (60 * 60 * hnum) - (60 * mnum);
        snum = Math.round(seconds); if(String(snum).length ==1 ){snum = "0" + snum;}
        document.getElementById("timeDate").innerHTML = "运行时长: "+dnum+" 天 ";
        document.getElementById("times").innerHTML = hnum + " 小时 " + mnum + " 分钟 " + snum + " 秒";
    }
setInterval("createtime()",1000);
</script>';
exit;
?>
