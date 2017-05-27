<?php

//开启session
session_start();

//定义一个常量，用来授权调用includes里的文件
define('IN_TG', true);
//定义一个常量，用来指定本页的内容
define('SCRIPT', 'manage_member');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快

//必须是管理员才能登录
manage_login();

//分页模块
global $_pagenum, $_pagesize, $_num; //设置全局变量
$_num = $mysqli->query("SELECT userid FROM user")->num_rows;
page($_num, 10); //第一个参数获取总条数，第二个参数每页多少条


//如果点击提交按钮
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
	//获取待删除用户id
	$clean = array();
	$clean['ids'] = implode(',', $_POST['ids']);

	//删除数据
	$mysqli->query("DELETE FROM user WHERE userid IN ({$clean['ids']})");

	//删除数据前先验证
	if ($mysqli->affected_rows) {
		$mysqli->close();
		location('用户删除成功！', 'manage_member.php');				
	} else {
		$mysqli->close();
		alert_back('非法登录！');
	}


}

//从数据库获取结果集
$result = $mysqli->query("SELECT
								userid,
								username,
								sex,
								level,
								address,
								phone,
								email
							FROM
								user
							ORDER BY 
								reg_time ASC
							LIMIT
								{$_pagenum},{$_pagesize}
						");

?>


<!DOCTYPE html>
<html>
<head>
	<title>在线购物系统--用户管理</title>
	<link rel="stylesheet" type="text/css" href="styles/1/basic.css" />
	<link rel="stylesheet" type="text/css" href="styles/1/manage_member.css" />
	<script type="text/javascript" src="js/manage_member.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH."includes/header.inc.php";
?>

	<div id="member">
		<!--引入side栏-->
		<?php 
			require ROOT_PATH."includes/manage.inc.php";
		?>
		<div id="member_main">
			<h2>会员管理中心</h2>
			<form action="?action=delete" method="post"> <!-- 提交给自己 -->
			<table cellspacing="1">
				<tr><th>ID号</th><th>用户名</th><th>用户等级</th><th>手机号</th><th>地址</th><th>操作</th></tr>
				<?php 
					$html = array();
					while ($rows = $result->fetch_assoc()) { 
						$html['userid'] = $rows['userid'];
						$html['username'] = $rows['username'];
						$html['email'] = $rows['email'];
						$html['level'] = $rows['level'];
						$html['sex'] = $rows['sex'];
						$html['address'] = $rows['address'];
						$html['phone'] = $rows['phone'];
				?>
				<tr>
					<td class="user"><?php echo $html['userid'] ?></td>
					<td class="user"><?php echo $html['username'] ?></td>
					<td class="user"><?php echo $html['level'] ?></td>
					<td class="user"><?php echo $html['phone'] ?></td>
					<td class="user"><?php echo $html['address'] ?></td>	
					<td><input type="checkbox" name="ids[]" value="<?php echo $html['userid'];?>" /></td>
				</tr>

				
				<?php } ?>
				<tr><td colspan="6"><label for="all">全选<input type="checkbox" name="chkall" id="all" /></label><input type="submit" value="批删除" /></td></tr>			
			</table>	
			</form>
			<?php
				//调用分页模块
				paging(2);
			?>				
		</div>
		
	</div>

<?php 
	require ROOT_PATH."includes/footer.inc.php";
?>
</body>
</html>
