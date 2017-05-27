<?php


	//数据库连接
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PWD', '');
	define('DB_NAME', 'shop');	

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);

	global $mysqli;

    //设置编码，没有此句可能会在数据库迁移中出现中文乱码
    mysqli_set_charset($mysqli, "utf8");

?>