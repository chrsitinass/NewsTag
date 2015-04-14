<?php
include "conn.php"; // 连接数据库
error_reporting( E_ALL&~E_NOTICE );
$id = $_GET['id'];
$name = $_GET['name'];
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

<div class='panel panel-info' style='margin:20px;'>
	<div class="panel-heading"><h2>		<p><?php 
		   $id = $_GET['id'];
		   $sql = "SELECT keyword FROM entity where id = $id";
		   $result = mysql_query($sql);
		   $name = mysql_fetch_row($result)[0];
		   echo $name;
		   ?></p></h2></div> <!-- 输出新闻列表-->
	<div class="panel-body">

	</div>
</div>

<div class='panel panel-success' style='float:left;width:450px; margin-left:20px;margin-right:5px'>
   <div class="panel-heading">
   <h3>实体出现时间折线图</h3>
   <canvas id="curveChart" width="400" height="300"></canvas>
   </div>
   <div class="panel-body" style='font-size:16px;'>

	</div>
</div>

<div class='panel panel-success' style='float:right;width:360; margin-right:20px;margin-left:5px'>
   <div class="panel-heading"><h3>实体相关新闻来源</h3></div>
   <div class="panel-body" style='font-size:16px;'>
		<div id="doughnutChart" style="height:300px;width:300px; "></div>
	</div>
</div>

<div style='clear:both'></div>

<div class='panel panel-info' style='margin:20px'>
  <div class="panel-heading"><h3>相关话题列表</h3></div>
   <div class="panel-body">
	<?php
	$id = $_GET['id'];
	$sql = "SELECT DISTINCT topic_id, count(*) FROM entity_topic where entity_id = '$id' GROUP BY topic_id ORDER BY count( * ) DESC LIMIT 1,11";
	$result = mysql_query($sql);
	while($row = mysql_fetch_object($result))
	{
		$sql2 = "SELECT name FROM topic where id = '$row->topic_id'";
		$result2 = mysql_query($sql2);
		$row2 = mysql_fetch_object($result2);
		echo "<li><a href='topic.php?id=$row->topic_id&name=$row2->name'>$row2->name</a></li>";
	}
	
	?>
	</div>
</div>

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
	$sql = "SELECT news.id, news.title, news.pubtime, news.source FROM news, entity_news WHERE entity_id = '$id' AND news.id = entity_news.news_id ORDER BY pubtime DESC LIMIT 20;";
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


<?php		
	$entityName = $_GET['name'];
	$entityID = $_GET['id'];
	$newsRes = mysql_query("SELECT DATE(pubtime) as pubtime, count(*) as cnt from news WHERE Date_SUB(CURDATE(), INTERVAL 15 DAY) <= DATE(pubtime) AND id IN (SELECT news_id FROM entity_news WHERE entity_id= ". $entityID .")  GROUP BY DATE(pubtime) ORDER BY DATE(pubtime)");
	$myDate = array();
	$newsCnt = array();
	
	while($row = mysql_fetch_object($newsRes))
	{
		array_push($myDate, $row->pubtime);
		array_push($newsCnt, $row->cnt);
	}
	
	//For Doughnut Chart
	$sourceRes = mysql_query("SELECT COUNT(*) as cnt, news.source FROM news, entity_news WHERE news.id = entity_news.news_id AND entity_news.entity_id = " . $entityID . " GROUP BY news.source ORDER BY COUNT(*);");
	$type = array();
	while($row = mysql_fetch_object($sourceRes))
	{
		array_push($type, array($row->source,$row->cnt));
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