<?php
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );
session_start();
if ($_SESSION['username'] == "") {
	header('Location: indexTag.php?flag=3');
	exit;
}
if ($_GET['flag'] == 1) {
	echo "<script language='javascript' type='text/javascript'>alert('请重新选择标注任务')".
	"</script>";
}
$username = $_SESSION['username'];
unset($_SESSION['task']);
?>

<html>
	<head>
		<?php
			include "head.php";
		?>
		<title>ICST-个人主页</title>
	</head>
	<body>

		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" style="font-size:25px;" href="#">ICST-新闻聚合网站</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><p class="navbar-text">Hi, <?php echo $_SESSION['realname']?>!</p></li>
						<li><a href="indexTag.php">注销</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="jumbotron">
				<h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
			</div>
			<ul id="myTab" class="nav nav-tabs">
				<li class="active">
					<a href="#task" data-toggle="tab"><b>标注任务</b></a>
				</li>
				<li>
					<a href="#comment" data-toggle="tab"><b>反馈</b></a>
				</li>
				<li>
					<a href="#news" data-toggle="tab"><b>公告</b></a>
				</li>
			</ul>
			<div id="myTabContent" class="tab-content">
  				<div class="tab-pane fade in active" id="task">
				<div class="table">
					<table class="table table-hover table-bordered">
						<thead>
							<th>任务名称</th>
							<th>完成进度</th>
							<th>标注入口</th>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT * FROM editTask WHERE username='$username' ORDER BY task";
							$result = mysql_query($sql);
							while($row = mysql_fetch_object($result)) {
								echo "<tr>";
								$task = $row->task;
								echo "<td>$task</td>";
								
								$sql2 = "SELECT count(*) FROM $row->task WHERE personId=$row->personId";
								$result2 = mysql_query($sql2);
								$row2 = mysql_fetch_array($result2);
								$total = $row2[0];

								if (strstr($task, "Sec")) {
									$sql3 = $sql2." AND SubCate>0";
									$result3 = mysql_query($sql3);
									$row3 = mysql_fetch_array($result3);
									echo "<td>$row3[0] / $total</td>";
								}
								else {
									$sql3 = $sql2." AND markedlabel>0";
									$result3 = mysql_query($sql3);
									$row3 = mysql_fetch_array($result3);
									echo "<td>$row3[0] / $total</td>";
								}
								if ($row->editable == 0) {
									echo "<td>已下线</td>";
								} else {
									echo "<td><a href='indexTask.php?task=$task' class='btn btn-xs btn-link'".
									"role ='button' style='font-size:15px;'>Go&raquo;</a></td>";
									echo "</tr>";
								}
							}
							?>
						</tbody>
					</table>
				</div>
   				</div>
   				<div class="tab-pane fade" id="comment">
				<div class="table">
					<table class="table table-hover table-bordered">
						<thead>
							<th>任务</th>
							<th width="60%">内容</th>
							<th>时间</th>
						</thead>
						<tbody>
						<?php
							$sql = "SELECT * FROM comment WHERE username='$username' ORDER BY Timestamp DESC";
							$result = mysql_query($sql);
							while ($row = mysql_fetch_object($result)) {
								echo "<tr>";
								echo "<td>$row->task</td>";
								echo "<td>$row->content</td>";
								echo "<td>$row->Timestamp</td>";
								echo "</tr>";
							}
						?>
						</tbody>
					</table>
				</div>
   				</div>
   				<div class="tab-pane fade" id="news">
   					<?php
					include "forAdmin/news.php";
					?>
   				</div>
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
