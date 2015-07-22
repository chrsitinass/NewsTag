<?php
session_start();
include "connect.php";
//重新登录
if ($_SESSION['username'] == "") {
    header('Location: indexTag.php?flag=3');
    exit;
}
$ccncLabel = $_GET['ccncLabel'];
//查询ccncLabel对应的markedlabel
$sql1 = "select * from category where superCateId=0 and cateLabel='$ccncLabel'";
$result1 = mysql_query($sql1);
$row1 = mysql_fetch_object($result1);
$cateId = $row1->cateId;

$username = $_SESSION['username'];
$task = $_GET['task'];
$surfaceId = $_GET['surfaceId'];
//已知personId，surfaceId更新task表里对应的markedlabel
$sql1 = "SELECT * FROM editTask WHERE username='$username' AND task='$task'";
$result1 = mysql_query($sql1);
$row1 = mysql_fetch_object($result1);
$personId = $row1->personId;
$sql = "UPDATE $task SET markedlabel=$cateId WHERE personId=$personId AND surfaceId=$surfaceId";
mysql_query($sql);
header("Location: newsContent.php?task=$task&surfaceId=$surfaceId&tagged=1");
?>