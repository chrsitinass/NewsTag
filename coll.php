<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">
      <a data-toggle="collapse" data-parent="#accordion" 
        href="#collapse<?php echo $id?>" style="color: #2C3E50;">
        <?php
          if ($ok == 0) echo $task,"(已下线)";
          else echo $task;
        ?>
      </a>
    </h3>
  </div>
  <div id="collapse<?php echo $id?>" class="panel-collapse collapse">
    <div class="panel-body">
      <?php
        include "admin_table.php";
      ?>
    </div>
  </div>
</div>