<?php
	
	//开启session
	session_start();

	//定义一个常量，用来授权调用includes里的文件
	define('IN_TG', true);
	//定义一个常量，用来指定本页的内容
	define('SCRIPT', 'register');
	//引入公共文件
	require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快


	if (isset($_POST['register']) && $_GET['action'] == 'register') {

		//验证码，防止恶意注册，跨站攻击
		if (strtoupper($_SESSION['code']) != strtoupper($_POST['code'])) { 
			alert_back('验证码输入失败！');
		} else {

			$html = array();
			$html['username'] = $_POST['username'];
			$html['password'] = sha1($_POST['password']);
			$html['sex'] = $_POST['sex'];
			$html['address'] = $_POST['address'];
			$html['phone'] = $_POST['phone'];
			$html['email'] = $_POST['email'];

			//在新增用户之前，判断用户名是否存在
			$mysqli->query("SELECT 
								username 
							FROM 
								user 
							WHERE 
								username = '{$html['username']}'
							LIMIT 1");
			if ($mysqli->affected_rows) {
				$mysqli->close();
				alert_back('该用户已存在，请重新注册！');
			} else {
				//新增用户
				$mysqli->query("INSERT INTO user(
												username, 
												password, 
												sex,
												address,
												phone,
												email,
												reg_time
											)
											values(
												'{$html['username']}',
												'{$html['password']}',
												'{$html['sex']}',
												'{$html['address']}',
												'{$html['phone']}',
												'{$html['email']}',
												NOW()
											)");

				if ($mysqli->affected_rows) {
					$mysqli->close();
					location('恭喜你，注册成功！', 'login.php');
				} else {
					$mysqli->close();
					alert_back('很遗憾，注册失败！');
				}				
			}

		}

	} 


?>
<!DOCTYPE html>
<html>
<head>
	<title>在线购物系统--注册</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/register.css" />
	<script type="text/javascript" src="js/register.js"></script>
</head>
<body>

	<?php 
		require ROOT_PATH."includes/header.inc.php";
	?>

	<div id="register">
		<h2>会员注册</h2>
		<form action="register.php?action=register" method="post">
			<dl>
				<dt>请认真填写已下内容</dt>
				<dd>用 户 名：<input type="text" name="username" class="text" />(*必填，至少两位)</dd>
				<dd>密 &nbsp; 码：<input type="password" name="password" class="text" />(*必填，至少六位)</dd>
				<dd>确认密码：<input type="password" name="notpassword" class="text" />(*必填，同上)</dd>
				<dd>性 &nbsp; 别：<input type="radio" name="sex" value="男" checked="checked" />男 <input type="radio" name="sex" value="女" />女</dd>
				<dd>联系地址：<input type="text" name="address" class="text" /></dd>
				<dd>手机号码：<input type="text" name="phone" class="text" /></dd>
				<dd>电子邮箱：<input type="text" name="email" class="text" /></dd>
				<dd>验 证 码: <input type="text" name="code" class="text code" /> <img src="code.php" onclick="this.src='code.php?'+Math.random()" id="vcode" /></dd>
				<dd><input type="submit" name="register" class="submit" value="提交" /></dd>
			</dl>
		</form>
	</div>

	<?php 
		require ROOT_PATH."includes/footer.inc.php";
	?>

</body>
</html>