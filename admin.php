<?php
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );
session_start();
if ($_SESSION['username'] == "") {
	header('Location: indexTag.php?flag=3');
	exit;
}
$finish_task = array();
$finish_name = array();
$finish_realname = array();
$finish_personId = array();
?>

<html>
<head>
	<?php
		include "head.php";
	?>
	<title>ICST-新闻聚合网站-供标注</title>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" style="font-size:25px;" href="#">
					ICST-新闻聚合网站
				</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><p class="navbar-text">Hi, 管理员!</p></li>
					<li><a href="indexTag.php">注销</a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<div class="container" role="main">
		<div class="jumbotron">
			<h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" 
						 href="#collapseOne" style="color: #2C3E50;">
						 用户列表
					</a>
				</h3>
			</div>
			<div id="collapseOne" class="panel-collapse collapse">
				<div class="panel-body">
					<div class="table">
						<table class="table table-hover table-bordered">
							<thead>
								<th>用户名</th>
								<th>真实姓名</th>
								<th>密码</th>
							</thead>
							<tbody>
								<?php
									$sql = "SELECT * FROM user";
									$result = mysql_query($sql);
									while($row = mysql_fetch_object($result)) {
										echo "<tr>";
										echo "<td>$row->username</td>";
										echo "<td>$row->realname</td>";
										echo "<td>$row->password</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
		include "admin_sum.php";
		?>
		<?php
		$sql6 = "SELECT DISTINCT task FROM editTask ORDER BY task";
		$result6 = mysql_query($sql6);
		$id = 1;
		while ($row6 = mysql_fetch_object($result6)) {
			$id = $id + 1;
			$task = $row6->task;
			$sql7 = "SELECT DISTINCT editable FROM editTask WHERE task='$task'";
			$result7 = mysql_query($sql7);
			$row7 = mysql_num_rows($result7);
			$ok = 0;
			if ($row7 == 2) $ok = 1;
			else {
				$row8 = mysql_fetch_object($result7);
				if ($row8->editable == 1) $ok = 1;
			}
			include "coll.php";
		}
		?>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" 
						 href="#message" style="color: #2C3E50;">
						我的反馈
					</a>
				</h3>
			</div>
			<div id="message" class="panel-collapse collapse">
				<div class="panel-body">
					<table class="table table-hover table-bordered">
						<thead>
							<th>用户名</th>
							<th>真实姓名</th>
							<th>标注任务</th>
							<th>反馈</th>
						</thead>
						<?php
							$number = 0;
							foreach ($finish_name as $temp_name) {
								echo "<tr>";
								echo "<td>$finish_name[$number]</td>";
								echo "<td>$finish_realname[$number]</td>";
								echo "<td>$finish_task[$number]</td>";

								$sql0 = "SELECT count(*) FROM comment WHERE username = '$finish_name[$number]' AND task = '$finish_task[$number]'";
								$result0 = mysql_query($sql0);
								$total0 = mysql_fetch_array($result0);

								if ($total0[0] == 0) {
									$string = "编写";
									$button = "success";
								} else {
									$button = "primary";
									$string = "查看";
								}
								echo "<td><a href='myComment.php?task=$finish_task[$number]&personId=$finish_personId[$number]'".
								" class='btn btn-xs btn-$button' role ='button'>$string</a></td>";
								echo "</tr>";
								$number++;
							}
						?>
					</table>
				</div>
			</div>		
		</div>
	</div>
	<footer>
		<div class="center">
			<center>Copyright: ICST @ PKU</center>
			<center>Chrome 10 / Safari 5 / Opera 11 or higher version, with 1024x768 or higher resolution for
				 best views.</center>
		</div>
	</footer>
</body>
</html>