<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'good_modify_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

//修改商品目录信息
if ($_GET['action'] == 'modify') {

	//接收数据
	$_clean = array();
	$_clean['id'] = $_POST['id'];
	$_clean['type'] = $_POST['type'];
	$_clean['face'] = $_POST['face'];
	$_clean['content'] = $_POST['content'];

	//修改数据
	$result = $mysqli->query("UPDATE 
									tg_dir
								SET 					
									tg_type='{$_clean['type']}',
									tg_face='{$_clean['face']}',
									tg_content='{$_clean['content']}'
								WHERE	
									tg_id='{$_clean['id']}'
								LIMIT	
									1
			");

	if ($mysqli->affected_rows) {
		$mysqli->close();
		location('修改目录成功！', 'good.php');
	} else {
		$mysqli->close();
		alert_back('修改目录失败！');
	}	






}

//读出数据
if ($_GET['id']) {
	$result = $mysqli->query("SELECT 
										tg_id,
										tg_type,
										tg_face,
										tg_content
									FROM 
										tg_dir
									WHERE 
										tg_id='{$_GET['id']}'	
								");
	if (!!$_rows = $result->fetch_assoc()) {
		$_html = array();
		$_html['id'] = $_rows['tg_id'];
		$_html['type'] = $_rows['tg_type'];
		$_html['face'] = $_rows['tg_face'];
		$_html['content'] = $_rows['tg_content'];
	} else {
		alert_back('不存在此相册！');
	}	
} else {
	alert_back('非法操作！');
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线购物系统--登录</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/good_modify_dir.css" />
</head>
<body>
	<?php 
		require ROOT_PATH."includes/header.inc.php";
	?>

	<div id="photo">
		<h2>修改相册目录</h2>
		<form action="?action=modify" method="post">
			<dl>
				<dd>相册类型：<input type="text" name="type" class="text" value="<?php echo $_html['type'] ?>" /></dd>
				<dd>相册封面：<input type="text" name="face" class="text" value="<?php echo $_html['face'] ?>" /><!--(*放一个地址就行了)--></dd>
				<dd>相册简介：<textarea name="content"><?php echo $_html['content'] ?></textarea></dd>
				<dd><input type="submit" name="photo" value="修改目录" class="submit" ></dd>
			</dl>	
			<input type="hidden" value="<?php echo $_html['id'] ?>" name="id" />	
		</form>

	</div>

	<?php 
		require ROOT_PATH."includes/footer.inc.php";
	?>

</body>
</html>