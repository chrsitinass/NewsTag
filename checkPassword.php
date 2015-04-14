<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['password']);
unset($_SESSION['personId']);
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );

$username = $_GET['username'];
$password = $_GET['password'];

$sql1 = "SELECT * FROM user WHERE username='$username' AND password='$password'";
$result1 = mysql_query($sql1);
$number1 = mysql_num_rows($result1);
if ($number1 == 0) {
  header('Location: indexTag.php?flag=1');
  exit;
}

$answer = mysql_fetch_object($result1);
$realname = $answer->realname;
if ($username == 'admin') {
  $_SESSION['username'] = $username;
  $_SESSION['password'] = $password;
  $_SESSION['realname'] = "管理员";
  header('Location: admin.php');
  exit;
}
else {
  $_SESSION['username'] = $username;
  $_SESSION['password'] = $password;
  $_SESSION['realname'] = $realname;
  header('Location: indexPerson.php');
  exit;
}
?>