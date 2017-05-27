<?php

//防止恶意调用，即通过地址栏的URL
if (!defined('IN_TG')) {	
	exit('Access Defined!');
}

?>

<div id="member_sidebar">
	<h2>管理导航</h2>
	<dl id="account">
		<dt>系统管理</dt>
		<dd><a href="manage.php">后台首页</a></dd>
		<dd><a href="###">系统设置</a></dd>
	</dl>
	<dl id="good">
		<dt>商品管理</dt>
		<dd><a href="###">添加商品</a></dd>
		<dd><a href="###">删除商品</a></dd>
		<dd><a href="###">更新商品</a></dd>
		<dd><a href="###">查询商品</a></dd>				
	</dl>
	<dl id="other">
		<dt>会员管理</dt>
		<dd><a href="manage_member.php">会员列表</a></dd>
		<dd><a href="###">职务设置</a></dd>
	</dl>		
</div>