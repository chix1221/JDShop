<?php

	//开启session
	session_start();

	//定义一个常量，用来授权调用includes里的文件
	define('IN_TG', true);
	//定义一个常量，用来指定本页的内容
	define('SCRIPT', 'member');
	//引入公共文件
	require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

//修改用户资料
if (isset($_POST['modify']) && $_GET['action'] == 'modify') {
	$html = array();
	$html['username'] = $_POST['username'];
	$html['password'] = sha1($_POST['password']);
	$html['sex'] = $_POST['sex'];
	$html['address'] = $_POST['address'];
	$html['phone'] = $_POST['phone'];
	$html['email'] = $_POST['email'];

	//在修改用户之前，判断用户是否存在
	$result = $mysqli->query("SELECT 
						username 
					FROM 
						user 
					WHERE 
						username = '{$html['username']}'
					LIMIT 1");
	if ($mysqli->affected_rows == -1) { //查询错误返回-1
		$mysqli->close();
		alert_back('该用户不存在！');
	} else {
		//修改用户
		$mysqli->query("UPDATE user SET 
										password='{$html['password']}', 
										sex='{$html['sex']}',
										address='{$html['address']}',
										phone='{$html['phone']}',
										email='{$html['email']}'
									WHERE
										username='{$_COOKIE['username']}'
									LIMIT
										1
								");
		if ($mysqli->affected_rows) {
			$mysqli->close();
			location('恭喜你，修改成功！', 'member_modify.php');
		} else {
			$mysqli->close();
			alert_back('很遗憾，修改失败！');
		}				
	}
}


//是否正常登陆
if (isset($_COOKIE['username'])) {
	//从数据库获取结果集
	$result = $mysqli->query("SELECT
									userid,
									password,
									username,
									sex,
									level,
									address,
									phone,
									email,
									reg_time
								FROM
									user
								WHERE
									username='{$_COOKIE['username']}'
								LIMIT
									1
							");
	$rows = array();
	$rows = $result->fetch_assoc();

	if ($result->num_rows) {
		$html = array();
		$html['username'] = $rows['username'];
		$html['password'] = $rows['password'];
 		$html['sex'] = $rows['sex'];
		$html['level'] = $rows['level'];
		$html['address'] = $rows['address'];
		$html['email'] = $rows['email'];
		$html['phone'] = $rows['phone'];
		$html['reg_time'] = $rows['reg_time'];
		switch ($rows['level']) {
			case 0:
				$html['level'] = '普通会员';
				break;
			case 1:
				$html['level'] = '管理员';
				break;
			default:
				$html['level'] = '出错了';
		}
	} else {
		echo '操作数据库不成功';
	}
} else {
	echo '非正常登陆';
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线购物系统--修改资料</title>
	<script type="text/javascript" src="js/login.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/member_modify.css" />
</head>
<body>
<?php 
	require ROOT_PATH."includes/header.inc.php";
?>

	<div id="member">
		<!--引入side栏-->
		<?php 
			require ROOT_PATH."includes/member.inc.php";
		?>
		<div id="member_main">
		 	<h2>用户管理中心</h2>
		 	<form name="modify" action="?action=modify" method="post" >
				<dl>
					<dd>用 户 名：<?php echo $html['username'];  ?></dd>
					<dd>密    码：<input type="password" name="password" value="" class="text" />(留空则不修改)</dd>
			<?php
				if ($html['sex'] == '男') {
					echo '<dd>性    别：<input type="radio" name="sex" value="男" checked="checked" /> 男 <input type="radio" name="sex" value="女" /> 女</dd>';
				} else if ($html['sex'] == '女') {
					echo '<dd>性    别：<input type="radio" name="sex" value="男" /> 男 <input type="radio" name="sex" value="女" checked="checked" /> 女</dd>';
				}
			?>
					<dd>手机号码：<input type="text" name="phone" value="<?php echo $html['phone'];   ?>"class="text" /></dd>
					<dd>地    址：<input type="text" name="address" value="<?php echo $html['address']; ?>" class="text" /></dd>				
					<dd>电子邮件：<input type="text" name="email" value="<?php echo $html['email'];  ?>" class="text" /></dd>	
					<dd><input type="submit" name="modify" value="修改资料" /></dd>
				</dl>			 		
		 	</form>
		</div>
	</div>

<?php 
	require ROOT_PATH."includes/footer.inc.php";
?>

</body>
</html>