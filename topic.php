<?php
include "conn.php"; // 连接数据库
error_reporting( E_ALL&~E_NOTICE );
$id = $_GET['id'];
$name = $_GET['name'];
	$topicName = $_GET['name'];
	$topicID = $_GET['id'];
	// For bar charts
	$max_count_res = mysql_query("SELECT MAX(entity_topic.count) as cnt, entity.keyword FROM entity_topic, entity WHERE entity_topic.topic_id=" . $topicID . " AND entity_topic.entity_id = entity.id GROUP BY entity.keyword ORDER BY entity_topic.count DESC LIMIT 0,1;");
	$max_count = mysql_fetch_object($max_count_res)->cnt;
	$entityRes = mysql_query("SELECT MAX(entity_topic.count) as cnt, entity.keyword FROM entity_topic, entity WHERE entity_topic.topic_id=" . $topicID . " AND entity_topic.entity_id = entity.id GROUP BY entity.keyword ORDER BY entity_topic.count DESC LIMIT 10;");    //定义变量
	$hotEntity = array();
	$ran = array();
	for ($iter= 0; $iter < 10; $iter++)
	{
		array_push($ran,rand(100,300));
	}
	arsort($ran);
	$max_count_ran = current($ran);
	while($row = mysql_fetch_object($entityRes))
	{
		array_push($hotEntity, array($row->cnt*10*1.0/$max_count*next($ran)/$max_count_ran, $row->keyword));
	}
	
	// For curve chart
	$newsRes = mysql_query("SELECT DATE(pubtime) as pubtime, count(*) as cnt from news WHERE topic_id=" . $topicID . " GROUP BY DATE(pubtime) ORDER BY DATE(pubtime)");
	$myDate = array();
	$newsCnt = array();
	date_default_timezone_set("PRC");
	$endDate = strtotime("today");	
	$startDate = strtotime("-15 days",$endDate);	// show a time range of 15 days
	while($startDate <= $endDate)	// make labels for each date
	{
		array_push($myDate, date("Y-m-d", $startDate));
		$startDate = strtotime("+1 days",$startDate);
	}
	$i = 0;
	
	while($row = mysql_fetch_object($newsRes))
	{
		if(strtotime(substr($row->pubtime,0,10)) < strtotime($myDate[$i]))	
		// Abandon news that happened 15 days ago
			continue;
		while(strtotime(substr($row->pubtime,0,10)) != strtotime($myDate[$i]))
		// Since myDate follows ascent order, 
		// we can simply use this paradigm to assign news count for each date
		{
			$i++;
			array_push($newsCnt, 0);
		}
		array_push($newsCnt, $row->cnt);
		$i++;
	}
	
	//For Doughnut Chart
	$sourceRes = mysql_query("SELECT COUNT(*) as cnt, news.source FROM news WHERE news.topic_id=" . $topicID . " GROUP BY news.source ORDER BY COUNT(*);");
	$type = array();
	while($row = mysql_fetch_object($sourceRes))
	{
		array_push($type, array($row->source,$row->cnt));
	}
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
<script type="text/javascript" src="JS/timeQuery.js"></script> <!-- 时间输入框-->

<script type="text/javascript" src="JS/highcharts.js"></script>
<script type="text/javascript" src="JS/highcharts-3d.js"></script>	

<script type="text/javascript" src="zhwiki/zhwiki.js"></script>
<script type="text/javascript" src="zhwiki/js/d3.js"></script>
<script src="./JS/d3.layout.cloud.js"></script>
<link href="zhwiki/css/entitygraph.css" rel="stylesheet" type="text/css">
	
<link rel="shortcut icon" href="../favicon.ico"> 
<link rel="stylesheet" type="text/css" href="css/demo.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript" src="js/modernizr.custom.11333.js"></script>	

<style>
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

<?php echo "<h2 style='font-weight:bold;font-size:25px;margin:20px;'>$name</h2>";?>

<div class='panel panel-info' style='float:left;margin:20px; margin-left:40px; width:700px;'>
	<div class="panel-heading"><h3>高频词云图</h3></div> <!-- 输出新闻列表-->
	<div class="panel-body">
	<div id="graph" style="width:560px; height:310px; margin:0 auto; padding-left:50px; "> <!----------------------- Word Cloud 1------------------>
		<?php
			$sql = "SELECT * FROM topic WHERE id = '$id'";
			$result = mysql_query($sql);
			$row = mysql_fetch_object($result);
			$split = explode('	',$row->hot_words);
			$arr = Array();
			for($i = 0; $i < count($split); $i++)
			{
				$split[$i] = explode('/', $split[$i]);
			}
			shuffle($split);

			$arr1 = Array();
			for($i = 0; $i < count($split); $i++)
			{
				$arr1[] = $split[$i][0];
			}
			
			if (function_exists('json_encode'))
			{
			$str=json_encode($arr1);
			}
			else
			{
			include("./JSON.php");
			$json = new JSON();
			$str=$json->encode($arr1);
			}
		?>
	</div>
	<script>
	var arr = eval(<?=$str?>);  var fill = d3.scale.category20();

	  d3.layout.cloud().size([500, 300])
		  .words(arr.map(function(d) {
			return {text: d, size: 5 + Math.random() * 40};
		  }))
		  .padding(1)
		  .rotate(function() {return ~~(Math.random() * 5) * 30 - 90; })
		  .font("Microsoft Yahei")
		  .fontSize(function(d) { return d.size; })
		  .on("end", draw)
		  .start();

	  function draw(words) {
		d3.select("#graph").append("svg")
			.attr("width", 500)
			.attr("height", 400)
		  .append("g")
			.attr("transform", "translate(250,150)")
		  .selectAll("text")
			.data(words)
		  .enter().append("text")
			.style("font-size", function(d) { return d.size + "px"; })
			.style("font-family", "Microsoft Yahei")
			.style("fill", function(d, i) { return fill(i); })
			.attr("text-anchor", "middle")
			.attr("transform", function(d) {
			  return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
			})
			.text(function(d) { return d.text; });
	  }
	</script>
	</div>
</div>
<div class='panel panel-info' style='float:right; margin:20px; margin-right:40px;width:450px;height:350px;'>
	<div class="panel-heading"><h3>相关实体</h3></div> <!-- 输出新闻列表-->
	<div class="panel-body">
	<?php
	for ($i = 0;$i < count($hotEntity); $i++)
	{
		$str = $hotEntity[$i][1];
		$sql = "SELECT id FROM entity where keyword = '$str'";
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result)[0];
		echo "<a href='entity.php?id=$row' style='font-size:26px;'>$str</a>&nbsp;";
	}
	?>
	</div>
</div>

<div style='clear:both'></div>

<div class='panel panel-success' style='float:left;width:380px; margin-left:40px;margin-right:5px'>
   <div class="panel-heading"><h3>实体热度图</h3></div>
   <div class="panel-body" style='font-size:16px;'>
	<div id="barChart" style="margin-left:0px; height:300px;width:280px; "></div>
	</div>
</div>


<div class='panel panel-success' style='float:left;width:380px; margin-left:80px;margin-left:5px'>
   <div class="panel-heading"><h3>话题热度图</h3></div>
   <div class="panel-body" style='font-size:16px;'>
		<canvas id="curveChart" width="300" height="300"></canvas>
	</div>
</div>

<div class='panel panel-success' style='float:left;width:380px; margin-left:20px;margin-right:5px'>
   <div class="panel-heading"><h3>新闻来源图</h3></div>
   <div class="panel-body" style='font-size:16px;'>
		<div id="doughnutChart" style="height:300px;width:360px; "></div>
	</div>
</div>
<div style='clear:both'></div>
<!--
<div class='panel panel-info' style='width:800px; margin:0 auto;'>
	<div class="panel-heading"><h3>实体关系图</h3></div> 
	<div class="panel-body">-->
		<div class='relation_graph'>
				<?php
			$id = $_GET['id'];
		echo "<script type=\"text/javascript\" charset=\"utf-8\">
		DrawEntityMap1(" . $id .");
		</script>" ?>
		</div>
<!--
	</div>
</div>-->

<div style='clear:both'></div>

<div class='panel panel-success' style='margin:20px'>
  <div class="panel-heading"><h3>新闻时间轴</h3></div>
   <div class="panel-body">
		<div class='time_axis'>
		<h2 class="ss-subtitle">News Timeline</h2>
			<?php $lrDecision = 0; ?>
		<div id="ss-container" class="ss-container">	
    <div class="ss-row">
        <div class="ss-left">
            <h2 id="2h">Today</h2>
        </div>
        <div class="ss-right">
            <h2>News Deliver</h2>
        </div>
    </div>
	<?php
	$id = $_GET['id'];
	$sql = "SELECT * FROM news WHERE topic_id = '$id'  ORDER BY pubtime DESC LIMIT 20;";
	$result = mysql_query($sql);
	while($row = mysql_fetch_object($result))
	{?>
                <div class="ss-row ss-medium">
                    <div class="ss-left">
                    	<?php  
                    		if ($lrDecision == 0)
                    		{
                    			echo 
                    				"<a href='news_content.php?id=$row->id' class='ss-circle ss-circle-1'> $row->title </a>" ;
                    		}
                    		else
                    		{
                    			echo
									"<h3>
                    				<span>$row->pubtime</span>
									<span>$row->source</span>
                    				<a href='news_content.php?id=$row->id'> $row->title </a>
									</h3>";
                    		}
                    	?>
                    </div>
                    <div class="ss-right">
                        <?php  
                    		if ($lrDecision == 1)
                    		{
                    			echo 
                    				"<a href='news_content.php?id=$row->id' class='ss-circle ss-circle-1'>$row->title</a>" ;
								$lrDecision = 0;
							}
                    		else
                    		{
                    			echo
									"<h3>
                    				<span>$row->pubtime</span>
									<span>$row->source</span>
                    				<a href='news_content.php?id=$row->id'>$row->title</a>
									</h3>";
								$lrDecision = 1;
							}
                    	?>
                    </div>
                </div>
	<?php } ?>
    </div>
	</div>
	<div style='clear:both'></div>
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

// Curve Chart
// Get the context of the canvas element we want to select
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


<script>
$(function () {

	var coverArray;
	coverArray = eval('<?php echo json_encode($type);?>');
	
	for (i=0;i<coverArray.length;i++)
		coverArray[i][1] = parseInt(coverArray[i][1])
    $('#doughnutChart').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: ''
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
            name: 'Delivered amount',
            data: coverArray
        }]
    });
});
</script>

<script type="text/javascript">
		$(function() {

			var $sidescroll	= (function() {
					
					// the row elements
				var $rows			= $('#ss-container > div.ss-row'),
					// we will cache the inviewport rows and the outside viewport rows
					$rowsViewport, $rowsOutViewport,
					// navigation menu links
					$links			= $('#ss-links > a'),
					// the window element
					$win			= $(window),
					// we will store the window sizes here
					winSize			= {},
					// used in the scroll setTimeout function
					anim			= false,
					// page scroll speed
					scollPageSpeed	= 2000 ,
					// page scroll easing
					scollPageEasing = 'easeInOutExpo',
					// perspective?
					hasPerspective	= false,
					
					perspective		= hasPerspective && Modernizr.csstransforms3d,
					// initialize function
					init			= function() {
						
						// get window sizes
						getWinSize();
						// initialize events
						initEvents();
						// define the inviewport selector
						defineViewport();
						// gets the elements that match the previous selector
						setViewportRows();
						// if perspective add css
						if( perspective ) {
							$rows.css({
								'-webkit-perspective'			: 600,
								'-webkit-perspective-origin'	: '50% 0%'
							});
						}
						// show the pointers for the inviewport rows
						$rowsViewport.find('a.ss-circle').addClass('ss-circle-deco');
						// set positions for each row
						placeRows();
						
					},
					// defines a selector that gathers the row elems that are initially visible.
					// the element is visible if its top is less than the window's height.
					// these elements will not be affected when scrolling the page.
					defineViewport	= function() {
					
						$.extend( $.expr[':'], {
						
							inviewport	: function ( el ) {
								if ( $(el).offset().top < winSize.height ) {
									return true;
								}
								return false;
							}
						
						});
					
					},
					// checks which rows are initially visible 
					setViewportRows	= function() {
						
						$rowsViewport 		= $rows.filter(':inviewport');
						$rowsOutViewport	= $rows.not( $rowsViewport )
						
					},
					// get window sizes
					getWinSize		= function() {
					
						winSize.width	= $win.width();
						winSize.height	= $win.height();
					
					},
					// initialize some events
					initEvents		= function() {
						
						// navigation menu links.
						// scroll to the respective section.
						$links.on( 'click.Scrolling', function( event ) {
							
							// scroll to the element that has id = menu's href
							$('html, body').stop().animate({
								scrollTop: $( $(this).attr('href') ).offset().top
							}, scollPageSpeed, scollPageEasing );
							
							return false;
						
						});
						
						$(window).on({
							// on window resize we need to redefine which rows are initially visible (this ones we will not animate).
							'resize.Scrolling' : function( event ) {
								
								// get the window sizes again
								getWinSize();
								// redefine which rows are initially visible (:inviewport)
								setViewportRows();
								// remove pointers for every row
								$rows.find('a.ss-circle').removeClass('ss-circle-deco');
								// show inviewport rows and respective pointers
								$rowsViewport.each( function() {
								
									$(this).find('div.ss-left')
										   .css({ left   : '0%' })
										   .end()
										   .find('div.ss-right')
										   .css({ right  : '0%' })
										   .end()
										   .find('a.ss-circle')
										   .addClass('ss-circle-deco');
								
								});
							
							},
							// when scrolling the page change the position of each row	
							'scroll.Scrolling' : function( event ) {
								
								// set a timeout to avoid that the 
								// placeRows function gets called on every scroll trigger
								if( anim ) return false;
								anim = true;
								setTimeout( function() {
									
									placeRows();
									anim = false;
									
								}, 10 );
							
							}
						});
					
					},
					// sets the position of the rows (left and right row elements).
					// Both of these elements will start with -50% for the left/right (not visible)
					// and this value should be 0% (final position) when the element is on the
					// center of the window.
					placeRows		= function() {
						
							// how much we scrolled so far
						var winscroll	= $win.scrollTop(),
							// the y value for the center of the screen
							winCenter	= winSize.height / 2 + winscroll;
						
						// for every row that is not inviewport
						$rowsOutViewport.each( function(i) {
							
							var $row	= $(this),
								// the left side element
								$rowL	= $row.find('div.ss-left'),
								// the right side element
								$rowR	= $row.find('div.ss-right'),
								// top value
								rowT	= $row.offset().top;
							
							// hide the row if it is under the viewport
							if( rowT > winSize.height + winscroll ) {
								
								if( perspective ) {
								
									$rowL.css({
										'-webkit-transform'	: 'translate3d(-75%, 0, 0) rotateY(-90deg) translate3d(-75%, 0, 0)',
										'opacity'			: 0
									});
									$rowR.css({
										'-webkit-transform'	: 'translate3d(75%, 0, 0) rotateY(90deg) translate3d(75%, 0, 0)',
										'opacity'			: 0
									});
								
								}
								else {
								
									$rowL.css({ left 		: '-50%' });
									$rowR.css({ right 		: '-50%' });
								
								}
								
							}
							// if not, the row should become visible (0% of left/right) as it gets closer to the center of the screen.
							else {
									
									// row's height
								var rowH	= $row.height(),
									// the value on each scrolling step will be proporcional to the distance from the center of the screen to its height
									factor 	= ( ( ( rowT + rowH / 2 ) - winCenter ) / ( winSize.height / 2 + rowH / 2 ) ),
									// value for the left / right of each side of the row.
									// 0% is the limit
									val		= Math.max( factor * 50, 0 );
									
								if( val <= 0 ) {
								
									// when 0% is reached show the pointer for that row
									if( !$row.data('pointer') ) {
									
										$row.data( 'pointer', true );
										$row.find('.ss-circle').addClass('ss-circle-deco');
									
									}
								
								}
								else {
									
									// the pointer should not be shown
									if( $row.data('pointer') ) {
										
										$row.data( 'pointer', false );
										$row.find('.ss-circle').removeClass('ss-circle-deco');
									
									}
									
								}
								
								// set calculated values
								if( perspective ) {
									
									var	t		= Math.max( factor * 75, 0 ),
										r		= Math.max( factor * 90, 0 ),
										o		= Math.min( Math.abs( factor - 1 ), 1 );
									
									$rowL.css({
										'-webkit-transform'	: 'translate3d(-' + t + '%, 0, 0) rotateY(-' + r + 'deg) translate3d(-' + t + '%, 0, 0)',
										'opacity'			: o
									});
									$rowR.css({
										'-webkit-transform'	: 'translate3d(' + t + '%, 0, 0) rotateY(' + r + 'deg) translate3d(' + t + '%, 0, 0)',
										'opacity'			: o
									});
								
								}
								else {
									
									$rowL.css({ left 	: - val + '%' });
									$rowR.css({ right 	: - val + '%' });
									
								}
								
							}	
						
						});
					
					};
				
				return { init : init };
			
			})();
			
			$sidescroll.init();
			
		});
		</script>

</body>
</html>