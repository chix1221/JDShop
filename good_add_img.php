<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'good_add_img');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

//必须是管理员才能登录
manage_login();	

if ($_GET['action'] == 'addimg') {

	//接收数据
	$_clean = array();
	$_clean['name'] = $_POST['name'];
	$_clean['type'] = $_POST['type'];
	$_clean['price'] = $_POST['price'];
	$_clean['madePlace'] = $_POST['madePlace'];
	$_clean['url'] = $_POST['url'];
	$_clean['content'] = $_POST['content'];
	$_clean['sid'] = $_POST['sid'];

	//写入数据库
	$result = $mysqli->query("INSERT INTO good
										(
											goodName,
											goodType,											
											goodPrice,
											goodMadePlace,
											goodImg,
											goodContent,
											goodSid,
											goodAddTime
										)
									VALUES 
										(
											'{$_clean['name']}',
											'{$_clean['type']}',
											'{$_clean['price']}',
											'{$_clean['madePlace']}',
											'{$_clean['url']}',
											'{$_clean['content']}',
											'{$_clean['sid']}',
											NOW()
										)");


	if ($mysqli->affected_rows) {
		$mysqli->close();
		location('商品添加成功！', 'good_show.php?id='.$_clean['sid'].'');
	} else {
		$mysqli->close();
		alert_back('商品添加失败！');		
	}

}


//取到商品目录的id和dir
if ($_GET['id']) {
	$result = $mysqli->query("SELECT 
									tg_id,
									tg_dir,
									tg_type
								FROM
									tg_dir
								WHERE
									tg_id='{$_GET['id']}'
								LIMIT 
									1
								");
	if (!!$_rows = $result->fetch_assoc()) {
		$_html = array();
		$_html['id'] = $_rows['tg_id'];
		$_html['dir'] = $_rows['tg_dir'];
		$_html['type'] = $_rows['tg_type'];	
	} else {
		alert_back('不存在此商品目录！');
	}
} else {
	alert_back('非法操作！');
}



?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线购物系统--添加商品</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/good_add_img.css" />
	<script type="text/javascript" src="js/good_add_img.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH."includes/header.inc.php";
	?>

	<div id="photo">
		<h2>添加商品</h2>
		<form action="?action=addimg" method="post" name="up">
			<dl>
				<dd>商品名称：<input type="text" name="name" class="text" /></dd>
				<input type="hidden" name="type" class="text" value="<?php echo $_html['type']; ?>" />
				<dd>商品价格：<input type="text" name="price" class="text" /></dd>
				<dd>商品产地：<input type="text" name="madePlace" class="text" /></dd>				
				<dd>图片地址：<input type="text" name="url" id="url" readonly="readonly" class="text" /> <a href="javascript:;" title="<?php echo $_html['dir']?>" id="up">上传</a></dd>		
				<dd>商品描述：<textarea name="content"></textarea></dd>
				<dd><input type="submit" class="submit" value="添加商品" /></dd>
			</dl>
			<input type="hidden" name="sid"  value="<?php echo $_html['id']; ?>" />
		</form>

	</div>

	<?php 
		require ROOT_PATH."includes/footer.inc.php";
	?>

</body>
</html>