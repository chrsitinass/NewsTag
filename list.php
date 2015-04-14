<?php
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );
session_start();
if ($_SESSION['username'] == "" || $_SESSION['personId'] == "" || $_SESSION['task'] == "") {
	header('Location: indexTag.php?flag=3');
	exit;
}
$personId = $_SESSION['personId'];
$username = $_SESSION['username'];
$task = $_SESSION['task'];
$total = $_SESSION['total'];
$page = $_GET['page'];
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta charset="utf-8">
<meta name="viewport" content="width=1000, initial-scale=1.0, maximum-scale=1.0">

<link rel="stylesheet" href="resources/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="resources/flat-ui/css/flat-ui.css">
<link rel="stylesheet" href="resources/base/css/base.css">

<script src="resources/flat-ui/js/vendor/jquery.min.js"></script>
<script src="resources/flat-ui/js/flat-ui.js"></script>
<script src="resources/flat-ui/assets/js/application.js"></script>
<script src="resources/base/js/submission.js"></script>

<title>ICST-新闻聚合网站-供标注</title>
</head>
<body>
	<?php
		include "head.php";
	?>
<div class="container">
  <div class="jumbotron">
    <h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
  </div>
	<div style='font-size:15px; text-align:center; width:1100px;margin: auto;'>
    <div class="page-header">
      <h2>新闻列表</h2>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="pagination">
          <ul>
            <?php
              $total_page = ceil($total / 100);
              $former = $page;
              $later = $page;
              if ($page > 1) {
                $former = $page - 1;
              }
              if ($page < $total_page) {
                $later = $page + 1;
              }
              echo "<li class='previous'><a href='list.php?page=$former'>&laquo;</a></li>";
              for ($i = 1; $i <= $total_page; $i++) {
                if ($page == $i) {
                  echo "<li class='active'><a href='#'>$i</a></li>";
                }
                else {
                  echo "<li><a href='list.php?page=$i'>$i</a></li>";
                }
              }
              echo "<li class='next'><a href='list.php?page=$later'>&raquo;</a></li>";
            ?>
          </ul>
        </div>
      </div>
      <div class="col-md-4" style="padding-top:20px;">
        <form class="form-horizontal" role="form" action="queryTemp_result.php" method="get">
          <div class="input-group">
            <input type="text" class="form-control" name="surfaceId" placeholder="请输入要查找的文章编号">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit">查询</button>
            </span>
          </div><!-- /input-group -->
        </form>
      </div>
    </div>

    <div class="progress" style="height: 20px;">
      <?php
        $sql = "SELECT count(*) FROM $task where personId=$personId AND markedlabel IS NOT NULL AND markedlabel>0";
        $result = mysql_query($sql);
        $total_tag = mysql_fetch_array($result); 
        $answer = $total_tag[0] / $total * 100;
      ?>
      <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $answer?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $answer?>%;line-height: 20px;">
        标注进度：已完成<?php echo $answer?>%
      </div>
    </div>
	<div class="table">
		<table class="table table-hover table-bordered">
			<thead>
				<th width="50px">编号</th>
				<th>题目</th>
				<th>标注的类别</th>
       <th width="100px">内容</th>
			</thead>
			<tbody>
				<?php
				$from = ($page - 1) * 100 + 1;
				$to = $page * 100;
				$sql = "SELECT * FROM $task where personId=$personId AND surfaceId BETWEEN $from and $to";
				$result = mysql_query($sql);
				while($row = mysql_fetch_object($result)) {
					echo "<tr>";
					echo "<td>$row->surfaceId</td>";
					$sql2 = "SELECT * FROM NewsList where docId='$row->docId'";
					$result2 = mysql_query($sql2);
					$row2 = mysql_fetch_object($result2);
					echo "<td>$row2->title</td>";
          $output = "标注";
          $button = "primary";
          if ($row->markedlabel>0) {
						$sql3 = "SELECT * FROM category where superCateId=0 AND cateId=$row->markedlabel";
						$result3 = mysql_query($sql3);
						$row3 = mysql_fetch_object($result3);
            $output = "查看";
            $button = "info";
						echo "<td>$row3->cateLabel</td>";
					}
					else {
						echo "<td></td>";
					}
					echo "<td><a href='newsTemp_content.php?surfaceId=$row->surfaceId' class='btn btn-xs btn-$button' role ='button'>$output</a></td>";
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>
    <div class="row">
      <div class="col-md-6">
        <div class="pagination">
          <ul>
            <?php
              echo "<li class='previous'><a href='list.php?page=$former'>&laquo;</a></li>";
              for ($i = 1; $i <= $total_page; $i++) {
                if ($page == $i) {
                  echo "<li class='active'><a href='#'>$i</a></li>";
                }
                else {
                  echo "<li><a href='list.php?page=$i'>$i</a></li>";
                }
              }
              echo "<li class='next'><a href='list.php?page=$later'>&raquo;</a></li>";
            ?>
          </ul>
        </div>
      </div>
      <div class="col-md-4" style="padding-top:20px;">
        <form class="form-horizontal" role="form" action="queryTemp_result.php" method="get">
          <div class="input-group">
            <input type="text" class="form-control" name="surfaceId" placeholder="请输入要查找的文章编号">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit">查询</button>
            </span>
          </div><!-- /input-group -->
        </form>
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