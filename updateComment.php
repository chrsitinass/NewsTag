<?php
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );
session_start();
if ($_SESSION['username'] == "") {
  header('Location: indexTag.php?flag=3');
  exit;
}

$task = $_POST['task'];
$username = $_POST['username'];
$comment = $_POST['content'];

$sql0 = "SELECT count(*) FROM comment WHERE username = '$username' AND task = '$task'";
$result0 = mysql_query($sql0);
$total0 = mysql_fetch_array($result0);

if ($total0[0] == 0) {
  $sql = "INSERT INTO comment(username, task, content) VALUES('$username', '$task', '$comment')";
} else {
  $sql = "UPDATE comment SET content = '$comment' WHERE task = '$task' AND username = '$username'";
}
mysql_query($sql);
header("Location: {$_SERVER["HTTP_REFERER"]}");
exit;
?>