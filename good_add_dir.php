<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'good_add_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

//必须是管理员才能登录
manage_login();


//添加目录
if ($_GET['action'] == 'adddir') {

	//接收数据
	$_clean = array();
	$_clean['type'] = $_POST['type'];
	$_clean['face'] = $_POST['face'];
	$_clean['content'] = $_POST['content'];
	$_clean['dir'] = time();

	//先检查一下主目录是否存在
	if (!is_dir('photo')) {
		mkdir('photo', 0777);
	}	

	//再在这个主目录里创建你定义的商品相册目录
	if (!is_dir('photo/'.$_clean['dir'])) {
		mkdir('photo/'.$_clean['dir']);
	}	

	//把当前的商品目录信息写入数据库
	$result = $mysqli->query("INSERT INTO tg_dir(
											tg_type,
											tg_face,
											tg_content,
											tg_dir,
											tg_date
										)
									VALUES(
											'{$_clean['type']}',
											'{$_clean['face']}',
											'{$_clean['content']}',
											'photo/{$_clean['dir']}',
											NOW()
										)");

	if ($mysqli->affected_rows) {
		$mysqli->close();
		location('添加目录成功！', 'good.php');
	} else {
		$mysqli->close();
		alert_back('目录添加失败！');
	}
		
}



?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线购物系统--添加商品</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/good_add_dir.css" />
</head>
<body>
	<?php 
		require ROOT_PATH."includes/header.inc.php";
	?>

	<div id="photo">
		<h2>添加相商品目录</h2>
		<form action="?action=adddir" method="post">
			<dl>
				<dd>商品类型：<input type="text" name="type" class="text" /></dd>
				<dd>相册封面：<input type="text" name="face" class="text"/><!--(*放一个地址就行了)--></dd>
				<dd>类型简介：<textarea name="content"></textarea></dd>
				<dd><input type="submit" name="good" value="添加商品目录" class="submit" ></dd>
			</dl>			
		</form>
	</div>

	<?php 
		require ROOT_PATH."includes/footer.inc.php";
	?>

</body>
</html>