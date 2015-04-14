<div class="modal fade" id="sourceModal<?php echo $index?>" tabindex="-1" aria-labelledby="sModalLabel<?php echo $index?>" aria-hidden="true">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
      </button>
      <p class="modal-title" id="sModalLabel<?php echo $index?>" style="font-size: 25px;">来源统计</p>
    </div>
    <div class="modal-body">
      <div class="table">
        <table class="table table-hover table-bordered">
          <thead>
            <?php
              foreach ($row13 as $source) {
                echo "<th>$source</th>";
              }
            ?>  
          </thead>
          <tbody>
            <?php
            echo "<tr>";
            foreach ($row13 as $source) {
              $sql14 = "SELECT COUNT(*) FROM NewsList WHERE cate = $cate_id AND source = $source";
              $result14 = mysql_query($sql14);
              $row14 = mysql_fetch_array($result14);
              echo "<td>$row14[0]</td>";
            }
            echo "</tr>";
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>