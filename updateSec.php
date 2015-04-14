<?php
session_start();
include "connect.php";
//重新登录
if ($_SESSION['username'] == "") {
  header('Location: indexTag.php?flag=3');
  exit;
}
$task = $_GET['task'];
$surfaceId = $_GET['surfaceId'];
$username = $_SESSION['username'];
$sql1 = "SELECT * FROM editTask WHERE username='$username' AND task='$task'";
$result1 = mysql_query($sql1);
$row1 = mysql_fetch_object($result1);
$personId = $row1->personId;

$sql3 = "SELECT * FROM $task WHERE personId=$personId AND surfaceId=$surfaceId";
$result3 = mysql_query($sql3);
$row3 = mysql_fetch_object($result3);
if ($_GET['Cate'] != "") {
  $Cate = $_GET['Cate'];
  $sql1 = "select * from category where superCateId=0 and cateLabel='$Cate'";
  $result1 = mysql_query($sql1);
  $row1 = mysql_fetch_object($result1);
  $CateId = $row1->cateId;
  if ($CateId != $row3->Cate) { 
    $sql2 = "UPDATE $task SET RedoCate = $CateId WHERE personId=$personId AND surfaceId=$surfaceId";
    mysql_query($sql2);
  }
} else {
  $CateId = $row3->Cate;
}
if ($CateId == $row3->Cate) {
  $sql5 = "UPDATE $task SET RedoCate = NULL WHERE personId=$personId AND surfaceId=$surfaceId";
  mysql_query($sql5);
}
if ($_GET['SubCate'] != "") {
  $SubCate = $_GET['SubCate'];
  $sql1 = "select * from category where superCateId=$CateId and cateLabel='$SubCate'";
  $result1 = mysql_query($sql1);
  $row1 = mysql_fetch_object($result1);
  $SubCateId = $row1->cateId;
  $sql2 = "UPDATE $task SET SubCate = $SubCateId WHERE personId=$personId AND surfaceId=$surfaceId";
  mysql_query($sql2);
}
header("Location: newsSecContent.php?task=$task&surfaceId=$surfaceId&tagged=1");
?>