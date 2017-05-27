<?php
	
	//开启session
	session_start();

	require "./class/vcode.class.php";

	//构造方法
	$vcode = new Vcode(80, 25, 4);
	//将验证码放到服务器自己的空间保存一份
	$_SESSION['code'] = $vcode->getcode();	
	//将验证码图片输出
	$vcode->outimg();


?>