<?php

//管理员登录
function manage_login() {
	//必须是管理员才能登录
	if (!(isset($_COOKIE['username'])) || !(isset($_SESSION['admin'])) || $_COOKIE['username'] != $_SESSION['admin']) {
		alert_back('非法登录！');
	}	
}

//js返回上一层页面
function alert_back($info) {
	echo "<script type='text/javascript'>alert('".$info."');history.back();</script>";
	exit();
}

//js实现跳转页面
function location($info, $url) {
	if (!empty($info)) {
		echo "<script type='text/javascript'>
				alert('".$info."');
				location.href='$url';
			</script>";
		exit();
	} else {
		header('Location:'.$url);
	}
}

//验证码检验
function check_code($first_code, $end_code) {
	if (strcasecmp($first_code, $end_code)  != 0) { 
		alert_back('验证码输入失败!');
	} 
}



/**
*返回分页函数
*/
function paging() {
	global $_page, $_pageabsolute, $_num, $_id; //设置全局变量
	if (isset($_GET['id'])) {
		$_id = 'id='.$_GET['id'].'&';
	} else {
		$_id = '';
	}
	echo '<div id="page_text">';
		echo '<ul>';
			echo '<li>'.$_page.'/'.$_pageabsolute.'页</li>';
			echo '<li>共有<strong>'.$_num.'</strong>条数据</li>';
				if ($_page == 1) {
					echo '<li>首页</li>';
					echo '<li>上一页</li>';						
				} else {
					echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page=1">首页</a></li>';
					echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'">上一页</a></li>';		
				}
				if ($_page == $_pageabsolute) {
					echo '<li>下一页</li>';
					echo '<li>尾页</li>';								
				} else {
					echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'">下一页</a></li>';
					echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_pageabsolute).'">尾页</a></li>';		
				}
		echo '</ul>';
	echo '</div>';
}


function page($_num, $_size) { //第一个参数数据库连接，第二个参数sql语句，第三个参数每页大小
	//将里面的所有变量取出来，外部可以访问
	global $_page, $_pagenum, $_pagesize, $_num, $_pageabsolute;
	if (isset($_GET['page'])) {
		$_page = $_GET['page'];
		if (empty($_page) || $_page <= 0 || !is_numeric($_page)) {
			$_page = 1;
		} else {
			$_page = intval($_page);
		}
	} else {
		$_page = 1;
	}
	$_pagesize = $_size; //每页多少条
	//首先要得到所有数据的总和
	if ($_num == 0) { //当数据库清零时
		$_pageabsolute = 1;
	} else {
		$_pageabsolute = ceil($_num / $_pagesize);
	}
	if ($_page > $_pageabsolute) {
		$_page = $_pageabsolute;
	}
	$_pagenum = ($_page - 1) * $_pagesize;	
}


/**
 * 缩略图函数
 */
function thumb($_filename, $_percent) {
	//生成png标头文件
	header('Content-type:image/png');
	$_n = explode('.', $_filename);
	//获取文件信息，长和高
	list($_width, $_height) = getimagesize($_filename);
	//生成微缩的长和高
	$_new_width = $_width * $_percent;
	$_new_height = $_height * $_percent;
	//创建一个以新百分比长度的画布
	$_new_image = imagecreatetruecolor($_new_width, $_new_height);
	//按照已有的图片创建一个画布
	switch ($_n[1]) {
		case 'jpg':
			$_image = imagecreatefromjpeg($_filename);
			break;
		case 'png':
			$_image = imagecreatefrompng($_filename);
			break;
		case 'gif':
			$_image = imagecreatefromgif($_filename);
			break;
	}
	//将原图采集后重新复制到新图上，就缩略了
	imagecopyresampled($_new_image, $_image, 0, 0, 0, 0, $_new_width, $_new_height, $_width, $_height);

	imagepng($_new_image);

	imagedestroy($_new_image);
	imagedestroy($image);
}


/**
 * 删除非空目录
 */
function remove_dir($dirName) {
    if(! is_dir($dirName))
    {
        return false;
    }
    $handle = @opendir($dirName);
    while(($file = @readdir($handle)) !== false)
    {
        if($file != '.' && $file != '..')
        {
            $dir = $dirName . '/' . $file;
            is_dir($dir) ? _remove_dir($dir) : @unlink($dir);
        }
    }
    closedir($handle);
    return rmdir($dirName) ;
} 


?>