<?php
include "conn.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $_GET['title']; ?></title>
<link href="CSS/Index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="JS/timeQuery.js"></script>
</head>

<body>
<div class="container">

<div class="header"><h1>ICST-新闻聚合网站</h1></div>

<div class="navigator">
	<table cellspacing='0'>
	<tr>
		<td><a href="index.php">首页</a></td>
		<td><a href="#">关于</a></td>
	</tr>
	</table>
</div>

<div class="content">

<h2>新闻列表</h2>
<table align='center' width='700px';>
<?php
	echo "<tr><th width='450px'>标题</td><th width='100px'>来源</td><th width='220px'>时间</td></tr>";
	$newsType = $_GET['title'];
	if($newsType == "国内要闻")
	{
		$result = mysql_query("SELECT id, title, source, pubtime FROM news WHERE category='国内新闻' ORDER BY pubtime DESC LIMIT 100");
	}
	elseif($newsType == "港澳台新闻")
		$result = mysql_query("SELECT id, title, source, pubtime FROM news WHERE category='港澳台新闻' ORDER BY pubtime DESC LIMIT 100");
	elseif($newsType == "社会新闻")
		$result = mysql_query("SELECT id, title, source, pubtime FROM news WHERE category='社会新闻' ORDER BY pubtime DESC LIMIT 100");
	else
	{
		$result = mysql_query("SELECT id, title, source, pubtime FROM news ORDER BY pubtime DESC LIMIT 100");
	}
	$count = 0;
	while($row = mysql_fetch_object($result))
	{
		$count++;
		if($count == 2)
			$count = 0;
		else 
			echo "<tr><td><a href='news_content.php?id=$row->id'>$row->title</a></td>
				<td>$row->source</td><td>$row->pubtime</td>
				</tr>";
	}
?>
</table>
</div>

<div class="footer">
	<p>Copyright: ICST @ PKU</p>
</div>

</div>
</body>
</html>