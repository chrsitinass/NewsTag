<?php
include "conn.php"; // 连接数据库
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>ICST-新闻聚合网站</title>
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
.topic_example{float:left;margin:20px 8px;height:350px;width:282px; }
.news_list{margin:10px auto; width:900px; }
.panel-body {font-size:16px; padding:10px 2px;}
</style>
</head>

<body>
<div class="container">

<div class="panel panel-primary" style='border:0;'> <!-- 页面正文-->
<div class="panel-heading" style='margin:0'><h1>ICST-新闻聚合网站</h1></div> <!-- 标题栏 -->
<div class="panel-body" style='font-size:16px;padding:0;'>
<div class="navigator"> <!-- 导航栏-->
	<table cellspacing='0'>
	<tr>
		<td><a href="index.php">首页</a></td>
		<td><a href="#">关于</a></td>
	</tr>
	</table>
</div>


<div class='panel panel-info' style='float:left; margin:20px; margin-right:5px; height:600px;width:430px; line-height:30px;'>
   <div class="panel-heading"><h3>当前最热话题</h3></div>
   <div class="panel-body" style='font-size:16px;'>
	<ul>
	<?php
	$sql = "SELECT DISTINCT topic_id, count( * ) FROM news GROUP BY topic_id ORDER BY count( * ) DESC LIMIT 1,15";
	$result = mysql_query($sql);
	while($row = mysql_fetch_object($result))
	{
		$sql2 = "SELECT name FROM topic where id = '$row->topic_id'";
		$result2 = mysql_query($sql2);
		$row2 = mysql_fetch_object($result2);
		echo "<li><a href='topic.php?id=$row->topic_id&name=$row2->name'>$row2->name</a></li>";
	}
	
	?>
	<span style='float:right;'><a href='topic_result.php'>更多...</a><span>
	</ul>
	</div>
</div>

<div class='panel panel-success' style='float:right; margin:20px 20px 1px 5px; height:400px;width:430px;'>
  <div class="panel-heading"><h3>热门实体</h3></div>
   <div class="panel-body">
	<div id="barChart">
	<?php
	// For bar chart
	$max_count_res = mysql_query("SELECT COUNT(*) as cnt FROM entity_news, entity WHERE entity_news.entity_id = entity.id GROUP BY entity.keyword ORDER BY COUNT(*) DESC LIMIT 0,1;"); 
	$max_count = mysql_fetch_object($max_count_res)->cnt;
	$entityRes = mysql_query("SELECT COUNT(*) as cnt, entity.keyword FROM entity_news, entity WHERE entity_news.entity_id = entity.id GROUP BY entity.keyword ORDER BY COUNT(*) DESC LIMIT 10;");   //定义变量
	$hotEntity = array();
	while($row = mysql_fetch_object($entityRes))
	{
		
		array_push($hotEntity, array($row->cnt*1.0/$max_count*10, $row->keyword));
	}
	?>
	</div>
	</div>
</div>
<div class='panel panel-success' style='float:right; margin:5px 20px 20px 5px; height:220px;width:430px;'>
  <div class="panel-heading"><h3>实体关联搜索</h3></div>
   <div class="panel-body" style='padding:5px 20px'>
	<div id="query">
		<form name="myform" action="../entity_linking/index.php"  method="get">
			实体名称：<input name="query" type="text" style='width:150px'>&nbsp;&nbsp;&nbsp;<input type="submit" value="搜索" style='width:100px'>
			<br/><br/>
			关联内容：<textarea name="context" style='width:280px;max-width:280px;height:70px;'></textarea>
		</form>
    </div>
	</div>
	</div>
</div>
<div style='clear:both'></div>

<div class='panel panel-info' style='margin:20px'>
	<div class="panel-heading"><h3>最新话题词云图</h3></div>
	<div class="panel-body">
		<div id="div1"><!----------------------- Word Cloud 2------------------>
			<?php
				$sql = "SELECT * FROM topic where id in (select distinct topic_id from news where topic_id!=-1) ORDER BY id DESC LIMIT 0,15";
				$result = mysql_query($sql);
				$color = Array('FireBrick', 'DarkBlue', 'Purple', 'green');
				$i = 0;
				while($row = mysql_fetch_object($result))
				{
					$i++;
				if ($i == 4) $i = 0;
					echo "<a href='topic.php?id=$row->id&name=$row->name' style='color:$color[$i]'>$row->name</a>";
				}
			?>
			</div>
		
	</div>
</div>

<div class='panel panel-success' style='margin:20px'>
	<div class="panel-heading"><h3>新闻时间折线图</h3></div>
	<div class="panel-body">
			<div id="a" style="float:left; height:200px;width:850px">
				<canvas id="curveChart" height="200" width="850"></canvas>
			</div>
	</div>
</div>

<br/>
<div style='font-size:15px; text-align:center;'>
<legend>按条件查询</legend>
<form action="query_result.php" method="get">
	<b>时间</b>
	从 <input type="text" id="datehmstart" name="datehmstart" readOnly onClick="setDayHM(this);">
	到 <input type="text" id="datehmend" name="datehmend" readOnly onClick="setDayHM(this);">
	<input type="button" value="重置" onclick="clearTime()">
	&nbsp;&nbsp;&nbsp;
	<b>来源</b>
	<select name="source">
		<option value="">全部</option>
		<option value="新浪网">新浪网</option>
		<option value="中国新闻网">中国新闻网</option>
		<option value="腾讯网">腾讯网</option>
		<option value="新华网">新华网</option>
		<option value="人民网">人民网</option>
		<option value="网易新闻">网易新闻</option>
		<!--
		<option value="央视新闻">央视新闻</option>
		<option value="凤凰新闻">凤凰新闻</option>
		<option value="环球时报">环球时报</option>
		<option value="央广网">央广网新闻</option>
		<option value="中国网">中国网-新闻频道</option>
		<option value="南方网">南方网新闻频道</option>
		<option value="澎湃新闻">澎湃新闻</option>
		
		<option value="搜狐">搜狐新闻</option>
		-->
	</select>
	&nbsp;&nbsp;&nbsp;
	<input type="submit" class="button" value="查询" onsubmit="return checkTime();">
</form>
</div>

<div class='panel panel-info news_list'>
	<div class="panel-heading"><h3>最新资讯</h3></div> <!-- 输出新闻列表-->
	<div class="panel-body">
		<table align='center'>
			<tr>
				<th style='width:400px'>标题</th>
				<th style='width:100px;text-align:center;'>来源</th>
				<th style='width:220px;text-align:center;'>时间</th>
			</tr>
		<?php
			$result = mysql_query("SELECT * FROM news ORDER BY pubtime DESC limit 0,10"); // 按id从大到小查询新闻
			while($row = mysql_fetch_object($result))
			{
					echo "<tr><td style='width:400px'><a href='news_content.php?id=$row->id'>$row->title</a></td>
						<td style='width:100px;text-align:center'><a href='query_result.php?source=$row->source'>$row->source</a></td>
						<td style='width:220px;text-align:center;'>$row->pubtime</td></tr>";
			}
		?>
		</table>


<div class="panel panel-success topic_example">
	<div class="panel-heading"><h3>国内要闻</h3></div>
	<div class="panel-body">
	<ul>
	<?php
	$result = mysql_query("SELECT * FROM news WHERE category like '%国内要闻%' ORDER BY pubtime DESC LIMIT 0,5");
	while($row = mysql_fetch_object($result))
	{
		echo "<li><a href='news_content.php?id=$row->id'>$row->title</a></li>";
	}
	?>
	<span style="float:right;"><a href='category_result.php?title=国内要闻'>更多...</a><span>
	</ul>
	</div>
</div>

<div class="panel panel-success topic_example">
	<div class="panel-heading"><h3>港澳台新闻</h3></div>
	<div class="panel-body">
		<ul>
		<?php
		$result = mysql_query("SELECT * FROM news WHERE category like '%港澳台%' ORDER BY pubtime DESC LIMIT 0,5");
		while($row = mysql_fetch_object($result))
		{
			echo "<li><a href='news_content.php?id=$row->id'>$row->title</a></li>";
		}
		?>
		<span style="float:right;"><a href='category_result.php?title=港澳台新闻'>更多...</a><span>
		</ul>
	</div>
</div>

<div class="panel panel-success topic_example">
	<div class="panel-heading"><h3>社会新闻</h3></div>
	<div class="panel-body">
	<ul>
	<?php
	$result = mysql_query("SELECT * FROM news WHERE category like '%社会%' ORDER BY pubtime DESC LIMIT 0,5");
	while($row = mysql_fetch_object($result))
	{
		echo "<li><a href='news_content.php?id=$row->id'>$row->title</a></li>";
	}
	?>
	<span style="float:right;"><a href='category_result.php?title=社会新闻'>更多...</a><span>
	</ul>
	</div>
</div>
<div style='clear:both'></div>
	</div>
</div>

</div>

</div>
<h6>Copyright: ICST @ PKU</h6>

</div>
<script>
// Bar Chart
$(document).ready(function(){
    // For horizontal bar charts, x an y values must will be "flipped"
    // from their vertical bar counterpart.
	var entityData = new Array();
	entityData[0] = eval('<?php echo json_encode($hotEntity);?>').reverse();
	var plot2 = $.jqplot('barChart', entityData, {
        seriesDefaults: {
            renderer:$.jqplot.BarRenderer,
            // Show point labels to the right ('e'ast) of each bar.
            // edgeTolerance of -15 allows labels flow outside the grid
            // up to 15 pixels.  If they flow out more than that, they 
            // will be hidden.
            pointLabels: { show: true, location: 'w', edgeTolerance: -15 },
            // Rotate the bar shadow as if bar is lit from top right.
            shadowAngle: 135,
            // Here's where we tell the chart it is oriented horizontally.
            rendererOptions: {
                barDirection: 'horizontal'
            }
        },
		seriesColors: [ "rgba(151,187,215,1)"],
        axes: {
            yaxis: {
                renderer: $.jqplot.CategoryAxisRenderer
            }
        }
    });
});
</script>



<?php	
	// news curve chart
	$newsRes = mysql_query("SELECT DATE(pubtime) as pubtime, count(*) as cnt from news WHERE Date_SUB(CURDATE(), INTERVAL 15 DAY) <= DATE(pubtime) GROUP BY DATE(pubtime) ORDER BY DATE(pubtime)");
	$myDate = array();
	$newsCnt = array();
	
	while($row = mysql_fetch_object($newsRes))
	{
		array_push($myDate, $row->pubtime);
		array_push($newsCnt, $row->cnt);
	}
		
?>

<script>
var ctx = document.getElementById("curveChart").getContext("2d");
var myDate =eval('<?php echo json_encode($myDate);?>');
var newsCnt = eval('<?php echo json_encode($newsCnt);?>');
var data = {
	labels : myDate,
	datasets : [
		{
			fillColor : "rgba(151,187,205,0.5)",
			strokeColor : "rgba(151,187,205,1)",
			pointColor : "rgba(151,187,205,1)",
			pointStrokeColor : "#fff",
			data : newsCnt
		}
	]
}
var myNewCurveChart = new Chart(ctx).Line(data);

</script>

</body>
</html>