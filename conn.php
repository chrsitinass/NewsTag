<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
// 连接数据库

$mysql_server_name="172.31.19.9";
$mysql_username="root";
$mysql_password="";
$mysql_database="newsProject";

$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
if (!$conn) 
{
  print "Error - Could not connect to MySQL";
  exit;
}
mysql_query("set names utf8", $conn);
$db = mysql_select_db($mysql_database, $conn);
if (!$db)
{
  print "Error - Could not select the guest database";
  exit;
}
?>