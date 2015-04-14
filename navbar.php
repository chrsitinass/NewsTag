<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" style="font-size:25px;" href="#">ICST-新闻聚合网站</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active" style="font-size:16px; color:#fff"><a href="indexPerson.php">个人主页</a></li>
				<li><p class="navbar-text">当前标注任务：<?php echo $task?></p></li>
				<li><a href="indexPerson.php">更换任务</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><p class="navbar-text">Hi, <?php echo $_SESSION['realname']?>!</p></li>
				<li>
					<a href="#" data-toggle="modal" data-target="#myModal">
						公告
					</a>
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
						 aria-labelledby="myModalLabel" aria-hidden="true">
						 <div class="modal-dialog">
								<div class="modal-content">
									 <div class="modal-header">
											<button type="button" class="close" 
												 data-dismiss="modal" aria-hidden="true">
														&times;
											</button>
											<p class="modal-title" id="myModalLabel" style="font-size: 25px;">
												 最新公告
											</p>
									 </div>
									 <div class="modal-body">
									 	<?php
									 		include "forAdmin/news.php";
									 	?>
									 </div>
									 <div class="modal-footer">
											<button type="button" class="btn btn-primary" 
												 data-dismiss="modal">关闭
											</button>
									 </div>
								</div><!-- /.modal-content -->
							</div>
					</div><!-- /.modal -->
				</li>
				<li><a href="indexTag.php">注销</a></li>
			</ul>
		</div>
	</div>
</div>