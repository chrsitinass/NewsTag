<?php
include "conn.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>实体列表</title>
<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="./JS/Chart.js"></script>
<script src="./jqplotSrc/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="./jqplotSrc/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="./jqplotSrc/jquery.jqplot.css" />
<script type="text/javascript" src="./jqplotSrc/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="./jqplotSrc/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="./jqplotSrc/plugins/jqplot.pointLabels.min.js"></script>
<link href="./CSS/Index.css" rel="stylesheet" type="text/css">
<link href="./CSS/tagClouds.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="JS/zns_3dsc.js"></script> <!-- 话题3D旋转效果-->
<script type="text/javascript" src="JS/timeQuery.js"></script> <!-- 时间输入框-->
<style>
.panel-body {font-size:16px; padding:10px 2px;}
</style>
</head>

<body>
<div class="container">

	<div class="panel panel-primary" style='border:0;'> <!-- 页面正文-->
	
		<div class="panel-heading" style='margin:0'>
			<h1>ICST-新闻聚合网站</h1>
		</div> <!-- 标题栏 -->
		
		<div class="panel-body" style='font-size:16px;padding:0;'>
			<div class="navigator">
				<table cellspacing='0'>
				<tr>
					<td><a href="index.php">首页</a></td>
					<td><a href="#">关于</a></td>
				</tr>
				</table>
			</div>

			<div class="panel panel-info"  style='border:0;'>
				<div class="panel-heading" style='margin:20px'><h1>话题列表</h1></div>
				<div class="panel-body">
					<div class="content" style='margin-bottom:20px;margin-left:50px;'>
						<ul>
						<?php
						
						$result = mysql_query("SELECT name,id FROM topic ORDER BY id DESC LIMIT 0,100");
						while($row = mysql_fetch_object($result))
						{
							echo
							"<li><a href='topic.php?id=$row->id&name=$row->name'>$row->name</a></li>";
						}
						?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<h6>Copyright: ICST @ PKU</h6>
</body>
</html>