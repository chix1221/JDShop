<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'notorder');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

if (!isset($_COOKIE['username'])) {
	alert_back('非法操作！');
}

//根据$_COOKIE['username']从数据库中信息
if (isset($_COOKIE['username'])) {
	//用户信息
	$result_user = $mysqli->query("SELECT 
										userid,
										username,
										address,
										phone
									FROM
										user
									WHERE
										username='{$_COOKIE['username']}'
									LIMIT
										1");
	if (!!$row_user = $result_user->fetch_assoc()) {
		$user_html = array();
		$user_html['id'] = $row_user['userid'];
		$user_html['username'] = $row_user['username'];
		$user_html['address'] = $row_user['address'];
		$user_html['phone'] = $row_user['phone'];
	}

	//该用户的购物车信息
	$result_buycar = $mysqli->query("SELECT
											bcId,
											goodId,
											goodCount,
											goodImg
										FROM
											buycar
										WHERE
											userId='{$user_html['id']}'");



} else {
	alert_back('非法登录！');
}


//根据表单提交来的数据将表单确认信息写入订单数据库
if ($_GET['action'] == 'notorder') {
	//判断当前用户是否登录
	if (isset($_COOKIE['username'])) {
		//接收表单发送过来的数据
		$html = array();
		$html['userId'] = $user_html['id'];
		$html['goodIds'] = implode(',', $_POST['ids']);
		$html['goodCounts'] = implode(',', $_POST['goodCounts']);
		$html['payMethod'] = $_POST['payMethod'];

		//插入订单数据库，即order_two
		$mysqli->query("INSERT INTO
								order_two(
									userId,
									goodIds,
									goodCounts,
									payMethod,
									orderDate
								)
								VALUES(
									'{$html['userId']}',
									'{$html['goodIds']}',
									'{$html['goodCounts']}',
									'{$html['payMethod']}',
									NOW()
								)");

		if ($mysqli->affected_rows == 1) {
			//如果提交订单成功，那么就将购物车中的商品删掉,即删除某个用户的购物车
			$mysqli->query("DELETE FROM
										buycar
									WHERE
										userId='{$html['userId']}'");
			if ($mysqli->affected_rows) {
				$mysqli->close();
				location('提交订单成功！点击跳转到订单页面！', 'order.php');
			} else {
				$mysqli->close();
				alert_back('购物车清空失败！');
			}
		} else {
			$mysqli->close();
			alert_back('提交订单失败！');
		}

	} else {
		alert_back('非法登录！');
	}
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>在线购物系统--核对订单</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/notorder.css" />
</head>
<body>
<?php 
	require ROOT_PATH."includes/header.inc.php";
?>

	<div id="buycar">
		<h2>核对订单</h2>
		<form action="?action=notorder" method="post">
			<dl>
				<dt>收货人信息</dt>
				<dd>
					<p><?php echo $user_html['username'].' '.$user_html['address']. ' '.$user_html['phone']; ?></p>
					<span class="message"><a href="###">编辑</a></span>
				</dd>
				<p class="line"></p>

				<dt>支付方式</dt>
				<dd>
					<input type="radio" name="payMethod" value="货到付款" checked="checked" /> 货到付款
					<input type="radio" name="payMethod" value="微信支付" /> 微信支付
					<input type="radio" name="payMethod" value="京东支付" /> 京东支付
					<input type="radio" name="payMethod" value="在线支付" /> 在线支付
				</dd>
				<p class="line"></p>

				<dt>送货清单</dt>
				<?php
					//商品总计
					$total = 0;
					while (!!$row_buycar = $result_buycar->fetch_assoc()) {
						$buycar_html = array();
						$buycar_html['bcId'] = $row_buycar['bcId'];
						$buycar_html['goodId'] = $row_buycar['goodId'];
						$buycar_html['goodCount'] = $row_buycar['goodCount'];
						$buycar_html['goodImg'] = $row_buycar['goodImg'];

						//取得订单中个商品的信息
						$result_good = $mysqli->query("SELECT
															goodId,
															goodName,
															goodPrice
														FROM
															good
														WHERE
															goodId='{$buycar_html['goodId']}'");

						if (!!$rows_good = $result_good->fetch_assoc()) {
							$good_html = array();
							$good_html['goodId'] = $rows_good['goodId'];
							$good_html['goodName'] = $rows_good['goodName'];
							$good_html['goodPrice'] = $rows_good['goodPrice'];
						}
						$total += $good_html['goodPrice'] * $buycar_html['goodCount'];		
					
				?>
				<!-- 传值 -->
				<input type="hidden" name="ids[]" value="<?php echo $good_html['goodId']; ?>" />
				<input type="hidden" name="goodCounts[]" value="<?php echo $buycar_html['goodCount']; ?>" />
				<dd class="order">
					<a href="good_detail.php?id=<?php echo $buycar_html['goodId']; ?>"><img src="<?php echo $buycar_html['goodImg']; ?>" /></a>
					<span class="right">x<?php echo $buycar_html['goodCount']; ?></span>
					<span class="right">¥<?php echo $good_html['goodPrice']; ?></span>
					<span class="goodname"><a href="good_detail.php?id=<?php echo $buycar_html['goodId']; ?>"><?php echo $good_html['goodName']; ?></a></span>
				</dd>
				<?php } ?>
			</dl>
			<div class="bottom">
				<p class="count">应付总额： ¥<?php echo $total; ?></p>
				<p>寄送至：<?php echo $user_html['address']; ?> &nbsp; 收货人：<?php echo $user_html['username']; ?> &nbsp; 联系： <?php echo $user_html['phone']; ?></p>
			</div>
			<div class="button">
				<input type="submit" name="order" value="提交订单">
			</div>
			
		</form>
	</div>

<?php 
	require ROOT_PATH."includes/footer.inc.php";
?>
</body>
</html>