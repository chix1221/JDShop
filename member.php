<?php

	//开启session
	session_start();

	//定义一个常量，用来授权调用includes里的文件
	define('IN_TG', true);
	//定义一个常量，用来指定本页的内容
	define('SCRIPT', 'member');
	//引入公共文件
	require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

//是否正常登陆
if (isset($_COOKIE['username'])) {
	//从数据库获取结果集
	$result = $mysqli->query("SELECT
									userid,
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
	<title>在线购物系统--个人信息</title>
	<script type="text/javascript" src="js/login.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/member.css" />
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
		 	<h2>会员管理中心</h2>
			<dl>
				<dd>用 户 名：<?php echo $html['username'];  ?></dd>
				<dd>性    别：<?php echo $html['sex'];  ?></dd>
				<dd>手机号码：<?php echo $html['phone'];  ?></dd>
				<dd>地    址：<?php echo $html['address'];  ?></dd>				
				<dd>电子邮件：<?php echo $html['email'];  ?></dd>
				<dd>注册时间：<?php echo $html['reg_time'];  ?></dd>
				<dd>身    份：<?php echo $html['level'] ?></dd>
			</dl>		 	
		</div>
	</div>

<?php 
	require ROOT_PATH."includes/footer.inc.php";
?>

</body>
</html>