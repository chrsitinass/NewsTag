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
							//$id_map_label = array();
							$sql9 = "SELECT DISTINCT(cate) FROM NewsList WHERE cate > 0";
							$result9 = mysql_query($sql9);
							$row9 = mysql_fetch_array($result9);
							/*
							$sql11 = "SELECT COUNT(*) FROM NewsList WHERE cate > 0";
							$result11 = mysql_query($sql11);
							$row11 = mysql_fetch_array($result11);
							$total = $row11[0];
							*/
							
							$sql13 = "SELECT DISTINCT(source) FROM NewsList WHERE cate > 0";
							$result13 = mysql_query($sql13);
							$row13 = mysql_fetch_array($result13);
							foreach ($row13 as $source) {
								echo "<th>$source</th>";
							}

							$index = 0;
							foreach ($row9 as $cate_id) {
								echo "<tr>";
								echo "<td>$cate_id</td>";
								$sql10 = "SELECT cateLabel FROM category WHERE cateId = $cate_id AND superCateId = 0";
								$result10 = mysql_query($sql10);
								$row10 = mysql_fetch_object($result10);
								echo "<td>$row10->cateLabel</td>";
								//array_push($id_map_label, $row10->cateLabel);
								$sql12 = "SELECT COUNT(*) FROM NewsList WHERE cate = $cate_id";
								$result12 = mysql_query($sql12);
								$row12 = mysql_fetch_array($result12);
								echo "<td>$row12[0]</td>";
								//echo "<td>$row12[0] / $total * 100 %</td>";
								echo "<td><a href='#' data-toggle='modal' data-target='#sourceModal$index'>查看</a></td>";
								echo "<td><a href='#' data-toggle='modal' data-target='#seCateModal$index'>查看</a></td>";
								echo "</tr>";
								include "sourceModal.php";
								include "seCateModal.php";
								$index = $index + 1;
							}
						?>
					</tbody>
				</table>
			</div>		
		</div>
	</div>
</div>