<div class="table">
<table class="table table-hover table-bordered">
  <thead>
    <th>personId</th>
    <th>用户名</th>
    <th>真实姓名</th>
    <th>完成进度</th>
  </thead>
  <tbody>
    <?php
    $sql3 = "SELECT * FROM editTask where task='$task'";
    $result3 = mysql_query($sql3);

    $check = "markedlabel";
    if (strstr($task, "Sec")) {
      $check = "SubCate";
    }
    while ($row3 = mysql_fetch_object($result3)){
      echo "<tr>";
      $i = $row3->personId; 
      $sql = "SELECT count(*) FROM $task where personId=$i AND $check>0";
      $result = mysql_query($sql);
      $total = mysql_fetch_array($result);
      
      echo "<td>$i</td>";
      
      $sql = "SELECT * FROM editTask WHERE personId=$i AND task='$task'";
      $result = mysql_query($sql);
      $row = mysql_fetch_object($result);
      echo "<td>$row->username</td>";
      
      $sql = "SELECT * FROM user WHERE username='$row->username'";
      $result = mysql_query($sql);
      $row = mysql_fetch_object($result);
      echo "<td>$row->realname</td>";
      
      echo "<td>";
      if ($row3->editable == 0) {
        $ok = 0;
        echo "已下线，";
      }
      
      $ok = 1;
      $sql = "SELECT count(*) FROM $task where personId=$i AND $check>0";
      $result = mysql_query($sql);
      $total = mysql_fetch_array($result); 
      $ttt = $total[0];
      echo "$total[0] / ";
      
      $sql = "SELECT count(*) FROM $task where personId=$i";
      $result = mysql_query($sql);
      $total = mysql_fetch_array($result); 
      echo "$total[0]";
  
      echo "</td>";
      if ($ok == 0 || $ttt == $total[0]) {
        array_push($finish_name, $row->username);
        array_push($finish_task, $task);
        array_push($finish_realname, $row->realname);
        array_push($finish_personId, $i);
      }
      echo "</tr>";
    }
    ?>
  </tbody>
</table>
</div>