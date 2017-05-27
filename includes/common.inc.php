<?php
	
	//防止恶意调用，即通过地址栏的URL
	if (!defined('IN_TG')) {	
		exit('Access Defined!');
	}

	//设置字符集编码
	header("Content-type:text/html;charset=utf-8");
	//屏蔽Notice错误
	error_reporting(E_ALL ^ E_NOTICE);
	//转换硬路径常量
	define('ROOT_PATH', substr(dirname(__FILE__), 0, -8));

	//拒绝php低版本
	if (PHP_VERSION < '4.1.0') {
		exit('PHP Version is too Low!');
	}

	//引入核心函数文件
	require ROOT_PATH.'includes/global.func.php';
	//引入数据库连接文件
	require ROOT_PATH.'includes/mysql.func.php';
	//引入服务器端验证文件
	require ROOT_PATH.'includes/check.func.php';



?>
