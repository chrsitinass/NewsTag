<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['password']);
unset($_SESSION['personId']);
include "connect.php";
error_reporting( E_ALL&~E_NOTICE );
$flag = $_GET['flag'];
if ($flag == 3) {
  echo "<script language='javascript' type='text/javascript'>alert('请重新登录')".
  "</script>";
}
?>

<html>
  <head>
    <?php
      include "head.php";
    ?>
    <title>ICST-新闻聚合网站-供标注</title>
  </head>

  <body>
    <div class="container theme-showcase" role="main">
      <div class="jumbotron">
        <h1>ICST-新闻聚合网站<small style="color: #2C3E50;">-供标注</small></h1>
      </div>
      <div class="article medium-article">
        <div align="center"
        style='font-size:15px; text-align:center; width:400px; margin-left: auto; margin-right: auto;'>
          <form action="checkPassword.php" class="form-horizontal" method="get">
            <div class="login-form">
              <div class="article-title"><h4>登录</h4></div>
              <table class="form two-col" id="login-table">
                <tr>
                  <td><label><b>用户名</b></label></td>
                  <td>
                    <input name="username" type="text" placeholder="请输入用户名" 
                    class="form-control" autofocus required>
                  </td>
                </tr>
                <tr>
                  <td><label><b>密码</b></label></td>
                  <td>
                    <input name="password" type="password" class="form-control"
                    placeholder="请输入密码" required>
                  </td>
                </tr>
              </table>
              <input class='btn btn-primary btn-large btn-block' type="submit" 
              style="margin-bottom:20px;" value="提交">
            </div>
          </form>
        </div>
      </div>
      <?php
        if ($flag == 1) {
          echo "<script language='javascript' type='text/javascript'>alert('请输入正确的用户名和密码')</script>";
        }
      ?>
    </div>
    <footer>
      <div class="center">
      <center>Copyright: ICST @ PKU</center>
      <center>Chrome 10 / Safari 5 / Opera 11 or higher version, 
      with 1024x768 or higher resolution for best views.</center>
      </div>
    </footer>
  </body>
</html>