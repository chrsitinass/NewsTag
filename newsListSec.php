<div class="table">
	<table class="table table-hover table-bordered">
		<thead>
			<th width="50px">编号</th>
			<th width="55%">题目</th>
			<th width="100px">一级类别</th>
			<th width="130px">修改的一级类别</th>
			<th>二级类别</th>
			<th width="100px">内容</th>
		</thead>
		<tbody>
			<?php
			$from = ($page - 1) * 100 + 1;
			$to = $page * 100;
			$sql = "SELECT * FROM $task WHERE personId=$personId AND surfaceId BETWEEN $from AND $to";
			$result = mysql_query($sql);
			while($row = mysql_fetch_object($result)) {
				echo "<tr>";
				echo "<td>$row->surfaceId</td>";
				$sql2 = "SELECT * FROM NewsList WHERE docId='$row->docId'";
				$result2 = mysql_query($sql2);
				$row2 = mysql_fetch_object($result2);
				echo "<td>$row2->title</td>";
				$sql3 = "SELECT * FROM category WHERE superCateId=0 AND cateId=$row->Cate";
				$result3 = mysql_query($sql3);
				$row3 = mysql_fetch_object($result3);
				echo "<td>$row3->cateLabel</td>";
				$CateId = $row->Cate;
				if ($row->RedoCate == NULL) {
					echo "<td>未修改</td>";
				}
				else {
					$sql4 = "SELECT * FROM category WHERE superCateId=0 AND cateId=$row->RedoCate";
					$result4 = mysql_query($sql4);
					$row4 = mysql_fetch_object($result4);
					echo "<td>$row4->cateLabel</td>";
					$CateId = $row->RedoCate;
				}
				$output = "标注";
				$button = "primary";
				if ($row->SubCate>0) {
					$sql3 = "SELECT * FROM category WHERE superCateId=$CateId AND cateId=$row->SubCate";
					$result3 = mysql_query($sql3);
					$row3 = mysql_fetch_object($result3);
					$output = "查看";
					$button = "info";
					echo "<td>$row3->cateLabel</td>";
				}
				else {
					echo "<td></td>";
				}
				echo "<td><a href='newsSecContent.php?task=$task&surfaceId=$row->surfaceId'".
				" class='btn btn-xs btn-$button' role ='button'>$output</a></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>