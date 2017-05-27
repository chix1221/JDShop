<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'good');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快


//分页模块
global $_pagenum, $_pagesize, $_num; //设置全局变量
$_num = $mysqli->query("SELECT tg_id FROM tg_dir")->num_rows;
page($_num, 10); //第一个参数获取总条数，第二个参数每页多少条




//删除商品目录
if ($_GET['action'] == 'delete') {

	//验证是否是管理员
	if (isset($_COOKIE['username']) && isset($_SESSION['admin']) && $_COOKIE['username'] == $_SESSION['admin']) {
	
		$result = $mysqli->query("SELECT 
										tg_dir
									FROM
										tg_dir
									WHERE
										tg_id='{$_GET['id']}'
									LIMIT
										1");
		if (!!$_rows = $result->fetch_assoc()) {
			
			//3.删除磁盘的目录
			if (file_exists($_rows['tg_dir'])) {
				//删除非空目录
				if (remove_dir($_rows['tg_dir'])) {
					//删除目录
					//1.删除目录里图片的数据库
					$mysqli->query("DELETE FROM good WHERE goodSid='{$_GET['id']}'");
					//2.删除这个目录的数据库
					$mysqli->query("DELETE FROM tg_dir WHERE tg_id='{$_GET['id']}'");			
					$mysqli->close();
					location('商品目录删除成功！', 'good.php');
				} else {
					$mysqli->close();
					alert_back('商品目录删除失败！');
				}
			}			

		} else {
			alert_back('不存在此商品目录！');
		}

	} else {
		exit('非法操作！');
	}

}

//从商品种类数据库提取数据
$result = $mysqli->query("SELECT 
								tg_id,
								tg_type,
								tg_face
							FROM 
								tg_dir 
							ORDER BY 
								tg_date DESC 
							LIMIT 
								{$_pagenum},{$_pagesize}
						");


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>在线购物系统--商品目录</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/good.css" />
</head>
<body>
	<?php 
		require ROOT_PATH."includes/header.inc.php";
	?>

	<div id="photo">
		<h2>商品目录</h2>
		<?php 
			$_html = array();
			while (!!$_rows = $result->fetch_assoc()) { 
				$_html['id'] = $_rows['tg_id'];
				$_html['type'] = $_rows['tg_type'];
				$_html['face'] = $_rows['tg_face'];
				if (empty($_html['face'])) {
					$_html['face_html'] = '';
				} else {
					$_html['face_html'] = '<img src="'.$_html['face'].'" alt="'.$_html['name'].'" title="点击进入" />';
				}	

		?>		
			<dl>
				<dt><a href="good_show.php?id=<?php echo $_html['id'] ?>"><?php echo $_html['face_html']; ?></a></dt>
				<dd><a href="good_show.php?id=<?php echo $_html['id'] ?>"><?php echo $_html['type']; ?></a></dd>
				<?php
					if (isset($_SESSION['admin']) && isset($_COOKIE['username']) && $_SESSION['admin'] == $_COOKIE['username']) {
				?>				
				<dd>[<a href="good_modify_dir.php?id=<?php echo $_html['id'] ?>">修改</a>] [<a href="good.php?action=delete&id=<?php echo $_html['id'] ?>">删除</a>]</dd>
				<?php } ?>				
			</dl>

		<?php } ?>

		<?php
			if (isset($_SESSION['admin']) && isset($_COOKIE['username']) && $_SESSION['admin'] == $_COOKIE['username']) {
		?>
				<p><a href="good_add_dir.php">添加商品目录</a></p>
		<?php } ?>
		<?php
			//调用分页模块
			paging(2);
		?>		
	</div>

	<?php 
		require ROOT_PATH."includes/footer.inc.php";
	?>

</body>
</html>