<?php
include "connect.php";
session_start();
if ($_SESSION['username'] == "") {
  header('Location: indexTag.php?flag=3');
  exit;
}
//Get username and task and surfaceId 
$surfaceId = $_GET['surfaceId'];
$task = $_GET['task'];
$username = $_SESSION['username'];
//check
if (strstr($task, "Sec")) {
  header("Location: indexPerson.php?flag=1");
  exit; 
}
$sql1 = "SELECT * FROM editTask WHERE username='$username' AND task='$task'";
$result1 = mysql_query($sql1);
$number1 = mysql_num_rows($result1);
if ($number1 == 0) {
  header("Location: indexPerson.php?flag=1");
  exit;
}
//Get personId and total
$row1 = mysql_fetch_object($result1);
$personId = $row1->personId;
$sql2 = "SELECT count(*) FROM $task WHERE personId=$personId";
$result2 = mysql_query($sql2);
$row2 = mysql_fetch_array($result2);
$total = $row2[0];

$sql0 = "SELECT * FROM $task WHERE personId=$personId AND surfaceId=$surfaceId";
$result0 = mysql_query($sql0);
$row0 = mysql_fetch_object($result0);
$string = "标注";
$flag = 0;
if ($row0->markedlabel>0) {
  $string = "更新标注";
  $flag = 1;
}
$tagged = $_GET['tagged'];
if ($tagged == 1) {
  echo "<script language='javascript' type='text/javascript'>alert('$string 成功！')</script>";
}
?>

<html>
  <head>
    <?php include "head.php"; ?>
    <title>新闻内容</title>
  </head>
  <body>
    <?php include "navbar.php"; ?> 
    <div class="container" role="main">
      <div class="jumbotron">
        <h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
      </div>
      <?php
        if ($flag) {
          $sql3 = "SELECT cateLabel FROM category where superCateId=0 AND cateId=$row0->markedlabel";
          $result3 = mysql_query($sql3);
          $row3 = mysql_fetch_object($result3);
          echo "<div class='alert alert-danger' role='alert' style='width: 100%;".
          " margin-left: auto; margin-right: auto; font-size: 15px;'>".
          "<b>注意：当前文章您已经标注过，上次标注的类别为：$row3->cateLabel ！</b></div>";
        }
      ?>
      <div class="content" style='margin-bottom:20px;'>
        <?php
          $sql = "SELECT * FROM NewsList WHERE docId='$row0->docId'";
          $result = mysql_query($sql);
          $row = mysql_fetch_object($result);
          echo "<div align='center'><h4>$row->title</h4></div>";
          echo "<div class='newscontent'><pre style='font-size:16px;padding:15px".
          " 30px;'>$row->content</pre></div>";
        ?>
      </div>
      <div align="center" style="margin-bottom:20px; width:600px; margin:auto;">
        <form action="update.php" class="form-horizontal" method="get">
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
              文章Id
            </label>
            <div class="col-sm-10">
              <input class="form-control" type="text" value="<?php echo $surfaceId?>"
              name="surfaceId" style="margin-bottom: 20px;" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              一级类别
            </label>
            <div class="col-sm-10">
              <select class="form-control" name="ccncLabel" 
              style="margin-left:auto;margin-right:auto;margin-bottom:20px;">
                <option></option>
                <?php
                  $sql = "SELECT * FROM category where superCateId=0";
                  $result = mysql_query($sql);
                  while($row = mysql_fetch_object($result)) {
                    echo "<option>$row->cateLabel</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          <input class='btn btn-warning' type="submit" value="<?php echo $string?>">
        </form>
      </div>
      <div align="center">
      <div class="btn-group" role="group">
      <?php
      $sentence = "<a class='btn btn-s btn-success' role ='button' href='newsContent.php?task=$task&surfaceId=";
      if ($surfaceId == $total) {
        $temp = $surfaceId - 1;
        echo $sentence."$temp'>返回上一篇文章</a>";
      } else if ($surfaceId == 1) {
        $temp = $surfaceId + 1;
        echo $sentence."$temp'>查看下一篇文章</a>";
      }
      else {
        $temp = $surfaceId - 1;
        echo $sentence."$temp'>返回上一篇文章</a>";
        $temp = $surfaceId + 1;
        echo $sentence."$temp'>查看下一篇文章</a>";
      }
      $page = intval(($surfaceId - 1) / 100) + 1;
      echo "<a class='btn btn-primary' role ='button' href='indexTask.php?task=$task&page=$page'>返回新闻列表</a>";
      ?>
      </div>
      </div>
    </div>
    <footer>
      <div class="center">
        <center>Copyright: ICST @ PKU</center>
        <center>Chrome 10 / Safari 5 / Opera 11 or higher version, 
        with 1024x768 or higher resolution for best views.</center>
      </div>
    </footer>
  </body>
</html>