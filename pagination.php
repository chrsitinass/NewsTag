<div class="row">
	<div class="col-md-6">
		<div class="pagination">
			<ul>
				<?php
					$total_page = ceil($total / 100);
					$former = $page;
					$later = $page;
					if ($page > 1) {
						$former = $page - 1;
					}
					if ($page < $total_page) {
						$later = $page + 1;
					}
					echo "<li class='previous'><a href='indexTask.php?task=$task&page=$former'>".
					"<image src='resources/flat-ui/img/icons/png/previous.png'></a></li>";
					if ($total_page <= 12) {
						for ($i = 1; $i <= $total_page; $i++) {
							if ($page == $i) {
								echo "<li class='active'><a href='#'>$i</a></li>";
							} else {
								echo "<li><a href='indexTask.php?task=$task&page=$i'>$i</a></li>";
							}
						}
					}
					else {
						if ($page <= 5 || $page >= $total_page - 4) {
							for ($i = 1; $i <= 5; $i++) {
								if ($page == $i) {
									echo "<li class='active'><a href='#'>$i</a></li>";
								} else {
									echo "<li><a href='indexTask.php?task=$task&page=$i'>$i</a></li>";
								}
							}
							echo "<li>...<li>";
							for ($i = $total_page - 4; $i <= $total_page; $i++) {
								if ($page == $i) {
									echo "<li class='active'><a href='#'>$i</a></li>";
								} else {
									echo "<li><a href='indexTask.php?task=$task&page=$i'>$i</a></li>";
								}
							}
						} else {
							for ($i = 1; $i <= 3; $i++) {
								echo "<li><a href='indexTask.php?task=$task&page=$i'>$i</a></li>";
							}
							echo "<li>...<li>";
							echo "<li><a href='indexTask.php?task=$task&page=$former'>$former</a></li>";
							echo "<li class='active'><a href='#'>$page</a></li>";
							echo "<li><a href='indexTask.php?task=$task&page=$later'>$later</a></li>";
							echo "<li>...<li>";
							for ($i = $total_page - 2; $i <= $total_page; $i++) {
								echo "<li><a href='indexTask.php?task=$task&page=$i'>$i</a></li>";
							}						
						}
					}
					echo "<li class='next'><a href='indexTask.php?task=$task&page=$later'>".
					"<image src='resources/flat-ui/img/icons/png/next.png'></a></li>";
				?>
			</ul>
		</div>
	</div>
	<div class="col-md-2" style="padding-top:20px;">
		<div class="input-group">
			<input type="text" class="form-control" name="page" id="page<?php echo $num?>"
			placeholder="输入页码">
			<span class="input-group-btn">
				<button class="btn btn-info" type="submit" 
				onClick="javascript:gotoPage(<?php echo $num?>);">GO</button>
			</span>
		</div>
	</div>
	<div class="col-md-4" style="padding-top:20px;">
		<div class="input-group">
			<input type="text" class="form-control" name="surfaceId" id="surfaceId<?php echo $num?>"
			placeholder="请输入要查找的文章编号">
			<span class="input-group-btn">
				<button class="btn btn-default" type="submit" 
				onClick="javascript:gotoContent(<?php echo $num?>);">查询</button>
			</span>
		</div>
	</div>
	<?php
		echo "<script type=\"text/javascript\">";
		echo "var task = \"$task\";";
		echo "var total = $total;";
		echo "var total_page = $total_page;";
		echo "</script>";
	?>
	<script type="text/javascript">
		function gotoPage(num) {
			var obj;
			if (num == 1) 
				obj = document.getElementById('page1');
			else
				obj = document.getElementById('page2');
			if (obj.value) {
				if (1 <= obj.value && obj.value <= total_page) {
					window.open('indexTask.php?task=' + task + '&page=' + obj.value.toString());
				}
				else {
					alert("输入不在页面范围");
				}
			}
		}
		function gotoContent(num) {
			var obj;
			if (num == 1)
				obj = document.getElementById('surfaceId1');
			else
				obj = document.getElementById('surfaceId2');
			if (obj.value) {
				if (1 <= obj.value && obj.value <= total) {
					if (task.indexOf("Sec") > 0) {
						window.open('newsSecContent.php?task=' + task + '&surfaceId=' + obj.value.toString());
					} else {
						window.open('newsContent.php?task=' + task + '&surfaceId=' + obj.value.toString());
					}
				}
				else {
					alert("输入不在文章范围");
				}
			}			
		}
	</script>
</div>