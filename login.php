<?php
	
	//开启session
	session_start();

	//定义一个常量，用来授权调用includes里的文件
	define('IN_TG', true);
	//定义一个常量，用来指定本页的内容
	define('SCRIPT', 'login');
	//引入公共文件
	require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

	if (isset($_POST['login']) && $_GET['action'] == 'login') {
		
		if (strcasecmp($_POST['code'], $_SESSION['code'])) {
			alert_back('验证码输入失败！');
		} else {

			$html = array();
			$html['username'] = $_POST['username'];
			$html['password'] = sha1($_POST['password']);
			$html['time'] = $_POST['time'];

			$result = $mysqli->query("SELECT 
											username,
											password,
											level
										FROM 
											user
										WHERE
											username = '{$html['username']}'
										AND
											password = '{$html['password']}'
										LIMIT 
											1");
			$row = $result->fetch_assoc();

			if ($result->num_rows) {
				// 生成cookie
				setcookie('username', $html['username'], time() + $html['time']);
				$mysqli->close();
				//如果是管理员，就生成$_SESSION['admin']
				if ($row['level'] == 1) {
					$_SESSION['admin'] = $row['username'];
				} 
				location('恭喜你，登录成功！', 'index.php');
			} else {
				$mysqli->close();
				alert_back('账号或密码错误，请重新登录！');
			}	

		}


	}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线购物系统--登录</title>
	<script type="text/javascript" src="js/login.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/login.css" />
</head>
<body>
	<?php 
		require ROOT_PATH."includes/header.inc.php";
	?>

	<div id="login">
		<h2>登陆</h2>
			<form action="login.php?action=login" method="post" name="login">
				<dl>
					<dd></dd>
					<dd>用 户 名: <input type="text" name="username" class="text" /></dd>
					<dd>密 &nbsp; 码：<input type="password" name="password" class="text" /></dd>	
					<dd>
						<input type="radio" name="time" value="0"> 不保留 </input>
						<input type="radio" name="time" value="86400" checked="checked" > 保留一天 </input>
						<input type="radio" name="time" value="604800"> 保留一周 </input>
						<input type="radio" name="time" value="2592000"> 保留一个月 </input>
					</dd>	
					<dd>验 证 码: <input type="text" name="code" class="text code" /><img src="code.php" onclick="this.src='code.php?'+Math.random()" id="vcode" /></dd>
					<dd>
						<input type="submit" name="login" value="登录" class="button"></input>
						<input type="button" name="register" value="注册" class="button" id="location"></input>
					</dd>
				</dl>
			</form>
	</div>

	<?php 
		require ROOT_PATH."includes/footer.inc.php";
	?>

</body>
</html>