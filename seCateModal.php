<div class="modal fade" id="seCateModal<?php echo $index?>" tabindex="-1" aria-labelledby="seModalLabel<?php echo $index?>" aria-hidden="true">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
      </button>
      <p class="modal-title" id="seModalLabel<?php echo $index?>" style="font-size: 25px;">子类统计</p>
    </div>
    <div class="modal-body">
      <div class="table">
        <table class="table table-hover table-bordered">
          <tbody>
            <?php
            echo "<tr>";
            $sql15 = "SELECT DISTINCT(cateId, cateLabel) FROM category WHERE superCateId = $cate_id";
            $result15 = mysql_query($sql15);
            while ($row15 = mysql_fetch_object($result15)) {
              echo "<th>$row15->cateLabel</th>";
              $sql16 = "SELECT COUNT(*) FROM NewsList WHERE subCate = $row15->cateId";
              $result16 = mysql_query($sql16);
              $row16 = mysql_fetch_array($result16);            
              echo "<th>$row16[0]</th>";
            }
            echo "</tr>";
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>