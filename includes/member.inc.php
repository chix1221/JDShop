<?php

//防止恶意调用，即通过地址栏的URL
if (!defined('IN_TG')) {	
	exit('Access Defined!');
}

?>

	<div id="member_sidebar">
		<h2>中心导航</h2>
		<dl id="account">
			<dt>账号管理</dt>
			<dd><a href="member.php">个人信息</a></dd>
			<dd><a href="member_modify.php">修改资料</a></dd>
		</dl>
		<dl id="other">
			<dt>其他</dt>
			<dd><a href="order.php">订单查询</a></dd>
			<dd><a href="buycar.php">购物车</a></dd>
			<dd><a href="###">...</a></dd>
		</dl>
	</div>