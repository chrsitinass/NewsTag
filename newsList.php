<div class="table">
  <table class="table table-hover table-bordered">
    <thead>
      <th width="50px">编号</th>
      <th width="70%">题目</th>
      <th>一级类别</th>
      <th width="100px">内容</th>
    </thead>
    <tbody>
      <?php
      $from = ($page - 1) * 100 + 1;
      $to = $page * 100;
      $sql = "SELECT * FROM $task WHERE personId=$personId AND surfaceId BETWEEN $from and $to";
      $result = mysql_query($sql);
      while($row = mysql_fetch_object($result)) {
        echo "<tr>";
        echo "<td>$row->surfaceId</td>";
        $sql2 = "SELECT * FROM NewsList WHERE docId='$row->docId'";
        $result2 = mysql_query($sql2);
        $row2 = mysql_fetch_object($result2);
        echo "<td>$row2->title</td>";
        $output = "标注";
        $button = "primary";
      if ($row->markedlabel>0) {
          $sql3 = "SELECT * FROM category WHERE superCateId=0 AND cateId=$row->markedlabel";
          $result3 = mysql_query($sql3);
          $row3 = mysql_fetch_object($result3);
          $output = "查看";
          $button = "info";
          echo "<td>$row3->cateLabel</td>";
        }
        else {
          echo "<td></td>";
        }
        echo "<td><a href='newsContent.php?task=$task&surfaceId=$row->surfaceId'".
        " class='btn btn-xs btn-$button' role ='button'>$output</a></td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>