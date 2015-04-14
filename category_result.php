<?php
error_reporting(E_ALL^E_NOTICE);
include "conn.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>新闻内容</title>
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
		<div class="panel-heading" style='margin:0'><h1>ICST-新闻聚合网站</h1></div> <!-- 标题栏 -->
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
				<div class="panel-heading" style='margin:20px'><h1><?php echo $_GET['title'];?></h1></div>
				<div class="panel-body">
				<div class="content" style='margin-bottom:20px;'>


				<h2><?php $_GET['title']?></h2>
				<table align='center'>
				<?php
					echo "<tr style='height:30px;'>
					<th style='width:420px;'>标题</th>
					<th style='width:100px;text-align:center;'>来源</th>
					<th style='width:220px;text-align:center;'>时间</th></tr>";
					$newsType = $_GET['title'];
					if($newsType == "国内要闻")
					{
						$result = mysql_query("SELECT id, title, source, pubtime FROM news WHERE category like '%国内%' ORDER BY pubtime DESC LIMIT 100");
					}
					elseif($newsType == "港澳台新闻")
						$result = mysql_query("SELECT id, title, source, pubtime FROM news WHERE category like '%港澳台%' ORDER BY pubtime DESC LIMIT 100");
					elseif($newsType == "社会新闻")
						$result = mysql_query("SELECT id, title, source, pubtime FROM news WHERE category like '%社会%' ORDER BY pubtime DESC LIMIT 100");
					else
					{
						echo $newsType;
						$result = mysql_query("SELECT id, title, source, pubtime FROM news ORDER BY pubtime DESC LIMIT 100");
					}
					while($row = mysql_fetch_object($result))
					{	echo "<tr style='height:30px;'><td style='width:420px;'><a href='news_content.php?id=$row->id'>$row->title</a></td>
								<td style='width:100px;text-align:center'>
									<a href='query_result.php?source=$row->source'>$row->source</a>
								</td>
								<td style='width:220px;text-align:center;'>$row->pubtime</td>
								</tr>";
					}
				?>
				</table>
				</div>
			</div>
		</div>
	</div>
	<h6>Copyright: ICST @ PKU</h6>
</div>
</body>
</html>