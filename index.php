<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'index');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快


//分页模块
global $_pagenum, $_pagesize, $_num; //设置全局变量
$_num = $mysqli->query("SELECT goodId FROM good")->num_rows;
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
						ORDER BY
							goodAddTime DESC
						LIMIT
							{$_pagenum},{$_pagesize}
						");

//获取结果集，从商品类别库里提取商品目录
$result_dir = $mysqli->query("SELECT
							tg_id,
							tg_type
						FROM 
							tg_dir
						ORDER BY
							tg_date	ASC
						LIMIT
							9
						");


?>


<!DOCTYPE html>
<html>
<head>
	<title>在线购物系统--首页</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/index.css" />
	<script type="text/javascript" src="js/index.js"></script>
</head>
<body>

	<?php 
		include "includes/header.inc.php";
	?>

	<div id="sidebar">
		<h2>商品分类</h2>
		<dl>
			<?php
			$dirhtml = array();
			while (!!$rows = $result_dir->fetch_assoc()) {
				$dirhtml['type'] = $rows['tg_type'];
				$dirhtml['id'] = $rows['tg_id'];
				echo '<dd><a href="good_show.php?id='.$dirhtml['id'].'">'.$dirhtml['type'].'</a></dd>';
			}
			?>
		</dl>
	</div>

	<div id="container">
	    <div id="list" style="left: -600px;">
	        <img src="img/5.jpg" alt="1"/>
	        <img src="img/1.jpg" alt="1"/>
	        <img src="img/2.jpg" alt="2"/>
	        <img src="img/3.jpg" alt="3"/>
	        <img src="img/4.jpg" alt="4"/>
	        <img src="img/5.jpg" alt="5"/>
	        <img src="img/1.jpg" alt="5"/>
	    </div>
	    <div id="buttons">
	        <span index="1" class="on"></span>
	        <span index="2"></span>
	        <span index="3"></span>
	        <span index="4"></span>
	        <span index="5"></span>
	    </div>
	    <a href="javascript:;" id="prev" class="arrow">&lt;</a>
	    <a href="javascript:;" id="next" class="arrow">&gt;</a>
	</div>

	<div id="main">
		<h2>商品展示</h2>
		<div id="photo">
			<?php 
				$html = array(); 	
				while ($rows = $result->fetch_assoc()) { 
					$html['goodId'] = $rows['goodId'];
					$html['goodName'] = $rows['goodName'];
					$html['goodPrice'] = $rows['goodPrice'];
					$html['goodType'] = $rows['goodType'];
					$html['goodImg'] = $rows['goodImg'];
					$html['goodMadePlace'] = $rows['goodMadePlace'];
					$html['goodBuyCount'] = $rows['goodBuyCount'];
					$html['goodCommentCount'] = $rows['goodCommentCount'];
			?>
				<dl>
					<dt><a href="good_detail.php?id=<?php echo $html['goodId']; ?>"><img src="<?php echo $html['goodImg']; ?>" alt="商品" height="180" width="180" /></a></dt>
					<dd><a href="good_detail.php?id=<?php echo $html['goodId']; ?>"><?php echo $html['goodName']; ?></a></dd>
					<dd><span>¥<?php echo $html['goodPrice']; ?></span></dd>
				</dl>	
			<?php } ?>	
		</div>
		<?php
			//调用分页模块
			paging(2);
		?>
	</div>


	<?php 
		include "includes/footer.inc.php";
	?>

</body>
</html>