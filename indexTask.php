<?php
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );
session_start();
if ($_SESSION['username'] == "") {
	header('Location: indexTag.php?flag=3');
	exit;
}
//inital username and task
$username = $_SESSION['username'];
$task = $_GET['task'];
//check
$sql1 = "SELECT * FROM editTask WHERE username='$username' AND task='$task'";
$result1 = mysql_query($sql1);
$number1 = mysql_num_rows($result1);
if ($number1 == 0) {
	header("Location: indexPerson.php?flag=1");
	exit;
}
//Get personId and total
$row1 = mysql_fetch_object($result1);
$personId = $row1->personId;
$sql2 = "SELECT count(*) FROM $task WHERE personId=$personId";
$result2 = mysql_query($sql2);
$row2 = mysql_fetch_array($result2);
$total = $row2[0];
//Set page
$page = 1;
if ($_GET['page']) $page = $_GET['page'];
?>

<html>
	<head>
		<?php
			include "head.php";
		?>
		<title>ICST-标注任务</title>
	</head>
	<body>
		<?php
			include "navbar.php";
		?>
		<div class="container">
			<div class="jumbotron">
				<h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
			</div>
			<div style='font-size:15px; text-align:center; width:1100px; margin: auto;'>
				<div class="page-header">
					<h2>新闻列表</h2>
				</div>
				<?php
					$num = 1;
					include "pagination.php";
				?>
				<?php
					if (strstr($task,"Sec")) { 
						include "progressSec.php";
						include "newsListSec.php";
					} else {
						include "progress.php";
						include "newsList.php";
					}
					$num = 2;
					include "pagination.php";
				?>
			</div>
		</div>
		<footer>
			<div class="center">
				<center>Copyright: ICST @ PKU</center>
				<center>Chrome 10 / Safari 5 / Opera 11 or higher version, with 1024x768 
				or higher resolution for best views.</center>
			</div>
		</footer>
	</body>
</html>