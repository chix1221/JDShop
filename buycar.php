<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'buycar');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快


//删除
if ($_GET['action'] == 'delete') {
	//验证是否是登录用户
	if (isset($_COOKIE['username'])) {

		if (isset($_GET['id'])) {
			//删除数据
			$mysqli->query("DELETE FROM buycar WHERE bcId='{$_GET['id']}'");			
		} else {
			//获取待删除购物车中数据行的id
			$clean = array();
			$clean['ids'] = implode(',', $_POST['ids']);
			//删除数据
			$mysqli->query("DELETE FROM buycar WHERE bcId IN ({$clean['ids']})");
		}

		//删除数据前先验证
		if ($mysqli->affected_rows) {
			$mysqli->close();
			location('删除成功！', 'buycar.php');				
		} else {
			$mysqli->close();
			alert_back('非法登录！');
		}

	} else {
		alert_back('非法操作！');
	}
}


//从购物车里面提取商品信息
if (isset($_COOKIE['username'])) {

	//取得当前用户ID
	$result = $mysqli->query("SELECT userid FROM user WHERE username='{$_COOKIE['username']}' LIMIT 1");
	if (!!$row = $result->fetch_assoc()) {
		$userId = $row['userid'];
	}

	//查询购物车数据库中的信息
	$result = $mysqli->query("SELECT
									bcId,
									userId,
									goodId,
									goodCount,
									goodImg
								FROM
									buycar
								WHERE
									userId='{$userId}'");


} else {
	alert_back('非法登录！');
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>在线购物系统--购物车</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/buycar.css" />
	<script type="text/javascript" src="js/jquery-2.2.1.min.js"></script>
	<script type="text/javascript" src="js/buycar.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH."includes/header.inc.php";
?>

	<div id="buycar">
		<h2>购物车</h2>
		<form action="?action=delete" method="post">
			<table>
				<tr>
					<th>选择</th>
					<th>商品</th>
					<th>单价</th>
					<th>数量</th>
					<th>小计(元)</th>
					<th>操作</th>
				</tr>
				<?php
				$total = 0;
				while(!!$rows = $result->fetch_assoc()) {
					//根据购物车中的信息查询具体商品信息
					$result_good = $mysqli->query("SELECT
														goodName,
														goodPrice
													FROM
														good
													WHERE
														goodId='{$rows['goodId']}'
													LIMIT
														1");
					
					while(!!$rows_good = $result_good->fetch_assoc()) {
						$total += $rows_good['goodPrice'] * $rows['goodCount'];
				?>
					<tr>	
						<td><input type="checkbox" name="ids[]" value="<?php echo $rows['bcId']; ?>" /></td>
						<td class="good"><a href="good_detail.php?id=<?php echo $rows['goodId']; ?>"><img src="<?php echo $rows['goodImg']; ?>" alt="" /><span><?php echo $rows_good['goodName']; ?></span></a></td>
						<td class="per">¥<?php echo $rows_good['goodPrice']; ?></td>
						<td class="number"><?php echo $rows['goodCount']; ?></td>
						<td class="count">¥<?php echo $rows_good['goodPrice'] * $rows['goodCount']; ?></td>
						<td class="delete"><a href="buycar.php?action=delete&id=<?php echo $rows['bcId']; ?>">删除</a></td>	
					</tr>
				<?php } } ?>

				<tr>
					<td><label for="all">全选<input type="checkbox" name="chkall" id="all" /></label></td>
					<td colspan="4"><span>¥<?php echo $total; ?></span> <a href="notorder.php">去结算</a></td>
					<td><input type="submit" value="批删除" /></td>
				</tr>
			</table>
		</form>
	</div>

<?php 
	require ROOT_PATH."includes/footer.inc.php";
?>
</body>
</html>