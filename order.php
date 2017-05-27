<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'order');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快



//从订单库中提取当前用户的信息
if (isset($_COOKIE['username'])) {

	//1.首先根据当前用户查到当前用户的用户ID
	$result_user = $mysqli->query("SELECT userid FROM user WHERE username='{$_COOKIE['username']}'");
	if (!!$row_user = $result_user->fetch_assoc()) {
		$html_userId = $row_user['userid'];
	} else {
		$mysqli->close();
		alert_back('当前用户不存在！');
	}

	//2.然后再根据当前用户ID从订单库中提取数据，包括商品ID
	$result_order = $mysqli->query("SELECT
										orderId,
										userId,
										goodIds,
										goodCounts,
										payMethod,
										orderDate
									FROM
										order_two
									WHERE
										userId='{$html_userId}'
									ORDER BY
										orderDate DESC");


} else {
	alert_back('非法操作！');
}







?>

<!DOCTYPE html>
<html>
<head>
	<title>在线购物系统--我的订单</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/order.css" />
</head>
<body>
<?php 
	require ROOT_PATH."includes/header.inc.php";
?>

	<div id="order">
		<h2>订单详情</h2>

		<?php
			//循环有多少张订单
			$html_order = array();
			while (!!$rows_order = $result_order->fetch_assoc()) {
				$html_order['orderId'] = $rows_order['orderId'];
				$html_order['userId'] = $rows_order['userId'];
				$html_order['goodIds'] = $rows_order['goodIds'];
				$html_order['goodCounts'] = $rows_order['goodCounts'];
				$html_order['payMethod'] = $rows_order['payMethod'];
				$html_order['orderDate'] = $rows_order['orderDate'];

				//根据订单库中的userId查找订单的收货人
				$result_user = $mysqli->query("SELECT username FROM user WHERE userid='{$html_order['userId']}'");
				if (!!$row_user = $result_user->fetch_assoc()) {
					$html_username = $row_user['username'];
				} else {
					$mysqli->close();
					alert_back('当前订单收货人不存在！');
				}

				//将订单中每种商品的数量字符串形式解析成索引数组形式
				$html_order['goodCounts'] = explode(',', $html_order['goodCounts']);
				//给索引复初始值
				$i = 0;

				//3.根据商品ID和商品数量提取每个商品的信息
				$result_good = $mysqli->query("SELECT
													goodId,
													goodName,
													goodPrice,
													goodImg
												FROM
													good
												WHERE
													goodId
												IN
													({$html_order['goodIds']})");


		?>

		<dl>
			<dt><span><?php echo $html_order['orderDate']; ?> &nbsp; 订单号：<?php echo $html_order['orderId']; ?></span></dt>
			<dt><span>收货人：<?php echo $html_username; ?> &nbsp; 订单金额：<span id="total"></span> &nbsp; 支付方式: <?php echo $html_order['payMethod']; ?></span></dt>
			
			<?php
				//订单金额
				//global $total;
				$total = 0;
				//循环每张订单中的商品
				$html_good = array();
				while (!!$rows_good = $result_good->fetch_assoc()) {
					$html_good['goodId'] = $rows_good['goodId'];
					$html_good['goodName'] = $rows_good['goodName'];
					$html_good['goodPrice'] = $rows_good['goodPrice'];
					$html_good['goodImg'] = $rows_good['goodImg'];
					$total += $html_good['goodPrice'] * $html_order['goodCounts'][$i];
			?>
			<dd>
				<a href="good_detail.php?id=<?php echo $html_good['goodId']; ?>"><img src="<?php echo $html_good['goodImg']; ?>" /></a>
				<span class="right">总额：¥<?php echo $html_good['goodPrice'] * $html_order['goodCounts'][$i]; ?></span>
				<span class="right"><?php echo $html_username; ?></span>
				<span class="right">数量：<?php echo $html_order['goodCounts'][$i++]; ?></span>
				<span class="goodname"><a href="good_detail.php?id=<?php echo $html_good['goodId']; ?>"><?php echo $html_good['goodName']; ?></a></span>
			</dd>
			<p class="line"></p>
			<?php } ?>
			<dd><span class="total">订单金额：¥<?php echo $total; ?></span></dd>
		</dl>	
		<?php } ?>

	</div>

<?php 
	require ROOT_PATH."includes/footer.inc.php";
?>
</body>
</html>