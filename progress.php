<div class="progress" style="height: 20px;">
  <?php
    $sql = "SELECT count(*) FROM $task WHERE personId=$personId AND markedlabel>0";
    $result = mysql_query($sql);
    $total_tag = mysql_fetch_array($result); 
    $answer = $total_tag[0] / $total * 100;
  ?>
  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $answer?>" 
  aria-valuemin="0" aria-valuemax="100" 
  style="width: <?php echo $answer?>%;line-height: 20px;">
    标注进度：已完成<?php echo $answer?>%
  </div>
</div>