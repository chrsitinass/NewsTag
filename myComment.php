<?php
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );
session_start();
if ($_SESSION['username'] == "") {
	header('Location: indexTag.php?flag=3');
	exit;
}
$task = $_GET['task'];
$personId = $_GET['personId'];

$sql0 = "SELECT * FROM editTask WHERE task = '$task' AND personId = $personId";
$result0 = mysql_query($sql0);
$row0 = mysql_fetch_object($result0);
$username = $row0->username;

$sql1 = "SELECT * FROM user WHERE username = '$username'";
$result1 = mysql_query($sql1);
$row1 = mysql_fetch_object($result1);
$realname = $row1->realname;

$sql2 = "SELECT count(*) FROM comment WHERE username = '$username' AND task = '$task'";
$result2 = mysql_query($sql2);
$total2 = mysql_fetch_array($result2); 
$content = "";
if ($total2[0] == 0) {
	$string = "提交";
} else {
	$string = "修改";
	$sql3 = "SELECT * FROM comment WHERE username = '$username' AND task = '$task'";
	$result3 = mysql_query($sql3);
	$row3 = mysql_fetch_object($result3);
	$content = $row3->content;
}
?>

<head>
	<?php
		include "head.php";
	?>
	<title>ICST-新闻聚合网站-管理员评价</title>
</head>

<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" style="font-size:25px;" href="#">
					ICST-新闻聚合网站
				</a>
				<ul class="nav navbar-nav">
					<li class="active" style="font-size:16px; color:#fff"><a href="admin.php">主页</a></li>
				</ul>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><p class="navbar-text">Hi, 管理员!</p></li>
					<li><a href="indexTag.php">注销</a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<div class="container">
		<div class="jumbotron">
			<h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
		</div>
	</div>
	<div align="center" style="margin-bottom:20px; width:1000px; margin:auto;">
		<form action="updateComment.php" class="form-horizontal" method="post" id="FORM">
			<div class="form-group">
				<label class="col-sm-2 control-label">
					标注任务
				</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" value="<?php echo $task?>"
					name="task" style="margin-bottom: 20px;" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					用户名
				</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" value="<?php echo $username?>"
					name="username" style="margin-bottom: 20px;" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					真实姓名
				</label>
				<div class="col-sm-10">
					<input class="form-control" type="text" value="<?php echo $realname?>"
					name="realname" style="margin-bottom: 20px;" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					内容
				</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="content"
					style="margin-bottom: 20px;"><?php echo $content?></textarea>
				</div>
			</div>
			<input class='btn btn-success' type="submit" value="<?php echo $string?>">
		</form>
	</div>
</body>