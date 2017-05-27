<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'good_show');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快


//删除商品，还要删除商品图片
if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
	//验证权限
	if (isset($_COOKIE['username']) && isset($_SESSION['admin']) && $_COOKIE['username'] == $_SESSION['admin']) {
		//查询要删除商品信息
		$result = $mysqli->query("SELECT
										goodId,
										goodImg,
										goodSid
									FROM
										good
									WHERE
										goodId='{$_GET['id']}'
									LIMIT
										1");
		if (!!$rows = $result->fetch_assoc()) {
			$clean = array();
			$clean['goodId'] = $rows['goodId'];
			$clean['goodImg'] = $rows['goodImg'];
			$clean['goodSid'] = $rows['goodSid'];

			//首先删除图片的数据库信息
			$mysqli->query("DELETE FROM good WHERE goodId='{$clean['goodId']}'");
			//如果成功
			if ($mysqli->affected_rows) {
				//删除图片物理地址
				if (file_exists($clean['goodImg'])) {
					//删除文件函数
					unlink($clean['goodImg']);
				} else {
					alert_back('磁盘里已不存在此商品的图片！');
				}
				$mysqli->close();
				location('商品删除成功！', 'good_show.php?id='.$clean['goodSid']);		
			} else {
				$mysqli->close();
				alert_back('商品删除失败！');			
			}							 
		}

	} else {
		exit('非法操作！');
	}
	
}


//根据传过来的id取值
if ($_GET['id']) {
	$result = $mysqli->query("SELECT 
						tg_id,
						tg_type
					FROM
						tg_dir
					WHERE
						tg_id='{$_GET['id']}'
					LIMIT 
						1
			");
	if (!!$_rows = $result->fetch_assoc()) {
		$_dirhtml = array();
		$_dirhtml['id'] = $_rows['tg_id'];
		$_dirhtml['type'] = $_rows['tg_type'];
	} else {
		alert_back('不存在此商品目录！');
	}
} else {
	alert_back('非法操作！');
}


//取值
if ($_GET['id']) {
	$result = $mysqli->query("SELECT 
									tg_id,
									tg_type
								FROM
									tg_dir
								WHERE
									tg_id='{$_GET['id']}'
								LIMIT 
									1");
	if (!!$_rows = $result->fetch_assoc()) {
		$_dirhtml = array();
		$_dirhtml['id'] = $_rows['tg_id'];
		$_dirhtml['type'] = $_rows['tg_type'];

	} else {
		alert_back('不存在此商品目录！');
	}
} else {
	alert_back('非法操作！');
}


//设置缩略图的百分比参数
$percent = 0.3;

//分页模块
global $_pagenum, $_pagesize, $_num; //设置全局变量
$_num = $mysqli->query("SELECT goodId FROM good WHERE goodSid='{$_GET['id']}'")->num_rows;
page($_num, 8); //第一个参数获取总条数，第二个参数每页多少条


//获取结果集，从商品库里提取商品
$result = $mysqli->query("SELECT 
							goodId,
							goodName,
							goodType,											
							goodPrice,
							goodMadePlace,
							goodImg,
							goodContent,
							goodSid,
							goodBuyCount,
							goodCommentCount
						FROM 
							good
						WHERE
							goodSid='{$_dirhtml['id']}'
						ORDER BY
							goodAddTime DESC
						LIMIT
							{$_pagenum},{$_pagesize}
						");
	


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>商品展示</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/good_show.css">
</head>
<body>
<?php 
	require ROOT_PATH."includes/header.inc.php";
?>

	<div id="photo">
		<h2><?php echo $_dirhtml['type']; ?></h2>
	<?php 
		$html = array(); 	
		while (!!$rows = $result->fetch_assoc()) { 
			$html['goodId'] = $rows['goodId'];
			$html['goodName'] = $rows['goodName'];
			$html['goodType'] = $rows['goodType'];			
			$html['goodPrice'] = $rows['goodPrice'];
			$html['goodMadePlace'] = $rows['goodMadePlace'];			
			$html['goodImg'] = $rows['goodImg'];
			$html['goodContent'] = $rows['goodContent'];
			$html['goodBuyCount'] = $rows['goodBuyCount'];
			$html['goodCommentCount'] = $rows['goodCommentCount'];
	?>
		<dl>
			<dt><a href="good_detail.php?id=<?php echo $html['goodId']; ?>"><img src="thumb.php?filename=<?php echo $html['goodImg']; ?>&percent=<?php echo $percent; ?>" title="点击进入" /></a></dt>
			<dd><a href="good_detail.php?id=<?php echo $html['goodId']; ?>"><?php echo $html['goodName']; ?></a></dd>
			<dd><span style="font-weight:bold;font-size:18px;color:red">￥<?php echo $html['goodPrice']; ?></span></dd>
			<dd><a href="###"><strong>有[<?php echo $html['goodBuyCount']; ?>]个人购买</strong></a> ｜ <a href="###"><strong>累计评价[<?php echo $html['goodCommentCount']; ?>]</strong></a></dd>
		<?php 
			if (isset($_COOKIE['username']) && isset($_SESSION['admin']) && $_COOKIE['username'] == $_SESSION['admin']) {
		?>		
			<dd>[<a href="good_show.php?action=delete&id=<?php echo $html['goodId']; ?>">删除</a>]</dd>
		<?php } ?>
		</dl>
	<?php } ?>

		<p><a href="good_add_img.php?id=<?php echo $_dirhtml['id']; ?>">上传商品图片</a></p>

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