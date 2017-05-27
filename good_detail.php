<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'good_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快





if (isset($_GET['id'])) {
	
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
								goodId='{$_GET['id']}'
							LIMIT
								1
							");

	if (!!$rows = $result->fetch_assoc()) {
		$html = array(); 
		$html['goodId'] = $rows['goodId'];
		$html['goodName'] = $rows['goodName'];
		$html['goodType'] = $rows['goodType'];			
		$html['goodPrice'] = $rows['goodPrice'];
		$html['goodMadePlace'] = $rows['goodMadePlace'];			
		$html['goodImg'] = $rows['goodImg'];
		$html['goodContent'] = $rows['goodContent'];
		$html['goodBuyCount'] = $rows['goodBuyCount'];
		$html['goodCommentCount'] = $rows['goodCommentCount'];
		$html['goodSid'] = $rows['goodSid'];
	}

} else {
	alert_back('非法操作！');
}


//添加商品到购物车
if (isset($_GET['id']) && $_GET['action'] == 'buycar') {
	if (isset($_COOKIE['username'])) {
		//取得当前用户ID
		$result = $mysqli->query("SELECT userid FROM user WHERE username='{$_COOKIE['username']}' LIMIT 1");
		if (!!$row = $result->fetch_assoc()) {
			$userId = $row['userid'];
		}
		//首先查询该商品是否在购物车中
		$result = $mysqli->query("SELECT bcId,goodId FROM buycar WHERE goodId='{$html['goodId']}' LIMIT 1");
		

		//如果在查到商品在购物车，则goodCount+1
		if (!!$row = $result->fetch_assoc()) {
			$mysqli->query("UPDATE 
								buycar 
							SET
								goodCount=goodCount+1
							WHERE
								goodId='{$row['goodId']}'
							LIMIT
								1");
		}
		//如果没查到，则goodCount=1
		else {
			$mysqli->query("INSERT INTO buycar(
												userId,
												goodId,
												goodCount,
												goodImg
											)
											VALUES(
												'{$userId}',
												'{$html['goodId']}',
												1,
												'{$html['goodImg']}'
											)");
		}

		if ($mysqli->affected_rows == 1) {
			$mysqli->close();
			location('商品加入购物车成功！', 'buycar.php');
		} else {
			$mysqli->close();
			alert_back('商品加入购物车失败！');
		}

	} else {
		alert_back('非法登录！');
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>在线购物系统--商品详情</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/good_detail.css" />
</head>
<body>
<?php 
	require ROOT_PATH."includes/header.inc.php";
?>

	<div id="photo">
	<h2>商品详情</h2>
		<dl class="detail">
			<dt><img src="<?php echo $html['goodImg']; ?>" alt="图片详情" /></dt>
			<dd>商品名称：<span><?php echo $html['goodName']; ?></span></dd>
			<dd>商品价格：<span>￥<?php echo $html['goodPrice']; ?></span></dd>
			<dd>商品产地：<?php echo $html['goodMadePlace']; ?></dd>
			<dd>商品简介：<?php echo $html['goodContent']; ?></dd>			
			<dd>累计购买 <a href="###"><?php echo $html['goodBuyCount']; ?></a> 累计评价 <a href="###"><?php echo $html['goodCommentCount']; ?></a></dd>
			<dd>[<a href="good_detail.php?action=buycar&id=<?php echo $html['goodId']; ?>">加入购物车</a>]</dd>
			<dd>[<a href="good_show.php?id=<?php echo $html['goodSid']; ?>">返回列表]</a></dd>
		</dl>
		<p id="line"></p>


	</div>

<?php 
	require ROOT_PATH."includes/footer.inc.php";
?>
</body>
</html>