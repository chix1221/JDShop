window.onload = function () {
	var up = document.getElementById('up');
	up.onclick = function () {
		centerWindow('upimg.php?dir=' + this.title + '/', 'up', 100, 440);
	};
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function () {
		//能用客户端去验证的尽量用客户端去验证
		if (fm.name.value.length < 2 || fm.name.value.length > 20) {
			alert('名称不得小于2位或者大于20位');
			fm.name.value = ''; //清空
			fm.name.focus(); //将焦点移动到表单字段
			return false;
		}		
		if (fm.url.value == '') {
			alert('地址不得为空！');
			fm.url.focus(); //将焦点移动到表单字段
			return false;
		}	
		return true;
	};
};
function centerWindow(url, name, height, width) {
	var top = (screen.height - height) / 2;
	var left = (screen.width - width) / 2;
	window.open(url, name, 'height=' + height + ',width=' + width + ',top=' + top + ',left=' + left);
}