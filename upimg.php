<?php

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'upimg');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

//必须是会员才能进入
if (!$_COOKIE['username']) {
	_alert_back('非法登录！');
}

//执行图片上传功能
if ($_GET['action'] == 'up') {

	//设置图片上传的类型
	$_files = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/xpng', 'image/gif');

	//判断类型是否是数组里的一种
	if (is_array($_files)) {
		if (!in_array($_FILES['userfile']['type'], $_files)) {
			alert_back('上传图片必须是jpg,png,gif中的一种！');
		}
	}

	//判断文件错误类型
	if ($_FILES['userfile']['error'] > 0) {
		switch ($_FILES['userfile']['error']) {
			case 1: alert_back('上传文件超过约定值1');
				break;
			case 2: alert_back('上传文件超过约定值2');
				break;
			case 3: alert_back('部分文件被上传');
				break;
			case 4: alert_back('没有任何文件被上传！');
				break;
		}
		exit;
	}

	//判断配置大小
	if ($_FILES['userfile']['size'] > 1000000) {
		alert_back('上传的文件不得超过1M');
	}

	//获取文件的扩展名
	$_fn = explode('.', $_FILES['userfile']['name']);

	//移动文件
	if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
		$tmpfile = $_FILES['userfile']['tmp_name'];
		$srcname = $_POST['dir'].date("YmdHis").rand(100, 999).'.'.$_fn[1];
		//将临时目录下的上传文件，复制到指定的目录下，指定的名字就可以完成上传
		if (move_uploaded_file($tmpfile, $srcname)) {
			echo "<script>alert('上传成功！');window.opener.document.getElementById('url').value='".$srcname."'; window.close();</script>";
			exit();
		} else {
			echo "上传失败！<br>";
		}
	} else {
		alert_back('上传的临时文件不存在！');
	}	

}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线购物系统--上传图片</title>
</head>
<body>
	<div id="upimg" style="padding:20px">
		<form enctype="multipart/form-data" action="upimg.php?action=up" method="post">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			<input type="hidden" name="dir" value="<?php echo $_GET['dir']; ?>" />
			选择图片: <input type="file" name="userfile" />
			<input type="submit" value="上传" />
		</form>
	</div>
</body>
</html>