<?php
date_default_timezone_set("Asia/Shanghai");
require_once("TeamSpeak3.php");
TeamSpeak3::init();
header('Content-Type: text/html; charset=utf8');
try {
    $ts3 = TeamSpeak3::factory("serverquery://serveradmin:cWuW0GJh@127.0.0.1:10011/?server_port=9987&use_offline_as_virtual=1&no_query_clients=1");
}
catch (Exception $e) {
    echo '<div style="background-color:red; color:white; display:block; font-weight:bold;">QueryError: ' . $e->getCode() . ' ' . $e->getMessage() . '</div>';
}
echo $ts3->getViewer( new  TeamSpeak3_Viewer_Html ( "images/viewer/" , "images/flags/" , "data:image" ));
?>
