<?php
	//开启session
	session_start();
	
?>

<div id="header">
	<h1><a href="index.php">在线购物系统</a></h1>
	<ul>
		<li><a href='index.php'>首页</a></li>
		<li><a href='good.php'>商品</a></li>

<?php if (!isset($_COOKIE['username'])) { ?>
		<li><a href='register.php'>注册</a></li>
		<li><a href='login.php'>登陆</a></li>
<?php } ?>

<?php if (isset($_COOKIE['username'])) { ?>
		<li><a href='member.php'><?php echo $_COOKIE['username']; ?>▪个人中心</a></li>
<?php if (isset($_COOKIE['username']) && $_COOKIE['username'] == $_SESSION['admin']) { ?>
		<li><a href='manage.php'>管理</a></li>
<?php } ?>	
		<li><a href='logout.php'>退出</a></li>
<?php } ?>

	</ul>
</div>