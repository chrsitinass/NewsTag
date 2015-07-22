<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">
      <a data-toggle="collapse" href="#check" style="color: #2C3E50;">
        统计
      </a>
    </h3>
  </div>
  <div id="check" class="panel-collapse collapse">
    <div class="panel-body">
      <div class="table">
        <table class="table table-hover table-bordered">
          <thead>
            <th>一级类别ID</th>
            <th>一级类别</th>
            <th>数量</th>
            <th>来源统计</th>
            <th>二级类别统计</th>
          </thead>
          <tbody>
            <?php
              $sql9 = "SELECT a.ccncCate, b.cateLabel, count(*) AS cnt FROM NewsList a".
              " LEFT JOIN category b on a.ccncCate = b. cateId WHERE a.ccncCate = b.cateId".
              " AND a.source != 'xhs.trs' GROUP BY a.ccncCate ORDER BY a.ccncCate";
              $result9 = mysql_query($sql9);
                            
              $sql11 = "SELECT Tagged, COUNT(*) AS cnt, ccncCate FROM NewsList GROUP BY ccncCate, Tagged ORDER BY ccncCate;";
              $result11 = mysql_query($sql11);
              
              $sql10 = "SELECT ccncCate, count(*) AS cnt FROM NewsList".
              " WHERE source != 'xhs.trs' AND locate('Sec',Tagged) <= 0".
              " GROUP BY ccncCate ORDER BY ccncCate;";
              $result10 = mysql_query($sql10);
              
              $Tagged = array();
              $tag_cnt = array();
              $tag_cate = array();
              $total_length = 0;
              $secNum = array();
              $secId = array();

              while ($row11 = mysql_fetch_object($result11)) {
                array_push($Tagged, $row11->Tagged);
                array_push($tag_cnt, $row11->cnt);
                $tag_cate[$total_length] = $row11->ccncCate;
                $total_length += 1;
              }
              
              while ($row10 = mysql_fetch_object($result10)) {
                if ($row10->ccncCate > 0) {
                  array_push($secId, $row10->ccncCate);
                  array_push($secNum, $row10->cnt);
                }
              }

              $index = 0;
              $id = 0;
              $cate_id = array();
              while ($row9 = mysql_fetch_object($result9)) {
                array_push($cate_id, $row9->ccncCate);
                
                echo "<tr>";
                echo "<td>$row9->ccncCate</td>";
                //$sql10 = "SELECT cateLabel FROM category WHERE cateId = $cate_id AND superCateId = 0";
                //$result10 = mysql_query($sql10);
                //$row10 = mysql_fetch_object($result10);
                echo "<td>$row9->cateLabel</td>";
                //array_push($id_map_label, $row10->cateLabel);
                //$sql12 = "SELECT COUNT(*) FROM NewsList WHERE ccncCate = $cate_id";
                //$result12 = mysql_query($sql12);
                //$row12 = mysql_fetch_array($result12);
                echo "<td>$row9->cnt</td>";
                //echo "<td>$row12[0] / $total * 100 %</td>";
                echo "<td><a href='#' data-toggle='modal' data-target='#sourceModal$index'>查看</a></td>";
                //echo "<td><a href='#' data-toggle='modal' data-target='#seCateModal$index'>查看</a></td>";
                if ($secId[$id] == $row9->ccncCate) {
                  echo "<td>$secNum[$id]</td>";
                  $id = $id + 1;
                } else {
                  echo "<td>0</td>";
                }
                echo "</tr>";
                //include "seCateModal.php";
                $index = $index + 1;
              }
            ?>
          </tbody>
        </table>
      </div>    
    </div>
  </div>
  <?php
    $index = 0;
    $l = 1;
    while ($tag_cate[$l] != 1) $l++;
    foreach ($cate_id as $key) {
      include "sourceModal.php";
      $index = $index + 1;
    }
  ?>
</div>