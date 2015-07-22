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
if (!strstr($task, "Sec")) {
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
if ($row0->RedoCate == NULL) {
  $sql3 = "SELECT * FROM category WHERE cateId=$row0->Cate AND superCateId=0";
}
else $sql3 = "SELECT * FROM category WHERE cateId=$row0->RedoCate AND superCateId=0";
$result3 = mysql_query($sql3);
$row3 = mysql_fetch_object($result3);
?>

<html>
  <head>
    <?php include "head.php"; ?>
    <title>新闻内容</title>
  </head>
  <body>
    <?php
      include "navbar.php";
    ?>
    <div class="container" role="main">
      <div class="jumbotron">
        <h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
      </div>
      <div class="content" style='margin-bottom:20px;'>
        <?php
          $sql = "SELECT * FROM NewsList WHERE docId='$row0->docId'";
          $result = mysql_query($sql);
          $row = mysql_fetch_object($result);
          echo "<div align='center'><h4>$row->title</h4></div>";
          echo "<div class='newscontent'><pre style='font-size:16px;padding:15px 30px;'>$row->content</pre></div>";
        ?>
      </div>
      <div align="center" style="margin-bottom:20px; width:600px; margin:auto;">
        <form action="updateSec.php" name="category" class="form-horizontal" method="get">
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
              <select class="form-control" name="Cate" id="Cate" 
              onchange="changeSubCate(this.value)"
              disabled="disabled"
              style="margin-left:auto;margin-right:auto;margin-bottom:20px;">
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              二级类别
            </label>
            <div class="col-sm-10">
              <select class="form-control" name="SubCate"
              style="margin-left:auto;margin-right:auto;margin-bottom:20px;">
                <option></option>
              </select>
            </div>
          </div>
          <input class='btn btn-warning' type="submit" value="提交">
        </form>
      </div>
      <div align="center">
        <div class="btn-group" role="group">
        <input type='button' class='btn btn-info' onClick='javascript:changeCate();' id='button_content'
        value='修改一级类别'>
        <?php
        $sentence = "<a class='btn btn-success' role ='button' href='newsSecContent.php?task=$task&surfaceId=";
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
    <?php
      //initalization
      $sql = "select * from category where cateLevel=1";
      $result = mysql_query($sql);
      echo "<script type=\"text/javascript\">\n";
      echo "var category = new Array();\n";
      echo "var dic = new Array();\n";
      echo "var reverse_dic = new Array();\n";
      
      echo "var cate_init = \"$row3->cateLabel\";\n";
      $number = 0;
      while ($row = mysql_fetch_object($result)) {
        echo "category[$number] = new Array();\n";
        echo "dic[\"$row->cateLabel\"] = $number;\n";
        echo "reverse_dic[$number] = \"$row->cateLabel\";\n";
        $sql1 = "select * from category where cateLevel=2 and superCateId=$row->cateId";
        $result1 = mysql_query($sql1);
        $number_sub = 0;
        while ($row1 = mysql_fetch_object($result1)) {
          echo "category[$number][$number_sub] = \"$row1->cateLabel\";\n";
          $number_sub += 1;
        }
        $number += 1;
      }
      echo "</script>";
    ?>
    <script type="text/javascript">
      function changeCate() {
        var obj = document.getElementById('Cate'); 
        obj.disabled = !obj.disabled;
        var Obj = document.getElementById('button_content');
        if (obj.disabled == false) {
          Obj.value = "不修改一级类别";
        }
        else Obj.value = "修改一级类别";
      }
      function CateStart() {
        var l = category.length;
        for (i = 0; i < l; i += 1) {
          document.category.Cate.options.add(new Option(reverse_dic[i]));
        }
        document.category.Cate.value = cate_init;
        var index = dic[cate_init];
        var len = category[index].length;
        for (i = 0; i < len; i += 1) {
          document.category.SubCate.options.add(new Option(category[index][i]));
        }
      }
      if(document.attachEvent)   
        window.attachEvent("onload", CateStart);   
      else   
        window.addEventListener('load', CateStart, false);   
      function changeSubCate(str) {
        var index = dic[str];
        var e = document.category.SubCate;
        optionsClear(e);
        var len = category[index].length;
        for (i = 0; i < len; i++) {
          e.options.add(new Option(category[index][i]));
        }
      }
      function optionsClear(e) {
          e.options.length = 1;
      }
    </script>
  </body>
</html>