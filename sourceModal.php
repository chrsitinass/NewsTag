<div class="modal fade" id="sourceModal<?php echo $index?>" tabindex="-1" aria-labelledby="sModalLabel<?php echo $index?>" aria-hidden="true">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
      </button>
      <p class="modal-title" id="sModalLabel<?php echo $index?>" style="font-size: 25px;">来源统计</p>
    </div>
    <div class="modal-body">
        <table class="table table-hover table-bordered" >
          <tbody>
            <?php
              while (($l < $total_length) AND ($tag_cate[$l] == $key)) {
                echo "<tr>";
                echo "<td>$Tagged[$l]</td>";
                echo "<td>$tag_cnt[$l]</td>";
                echo "</tr>";
                $l = $l + 1;
              }
            ?>
          </tbody>
        </table>
    </div>
  </div>
</div>