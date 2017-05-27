//等待网页加载完成之后再执行
window.onload = function() {

	var fm = document.getElementsByTagName('form')[0];

	fm.onsubmit = function () {
		//能用客户端去验证的尽量用客户端去验证
		if (fm.username.value.length < 2 || fm.username.value.length > 20) {
			alert('用户名不得小于2位或者大于20位');
			fm.username.value = ''; //清空
			fm.username.focus(); //将焦点移动到表单字段
			return false;
		}		
		//用户名不得包含敏感字符
		if (/[<>\'\"\ ]/.test(fm.username.value)) {
			alert('用户名不得包含敏感字符!');
			fm.username.value = ''; //清空
			fm.username.focus(); //将焦点移动到表单字段
			return false;			
		}	
		//密码验证
		if (fm.password.value.length < 6) {
			alert('密码不得小于6位');
			fm.password.value = ''; //清空
			fm.password.focus(); //将焦点移动到表单字段
			return false;
		}		
		//验证码验证
		if (fm.code.value.length != 4) {
			alert('验证码不为四位!');
			fm.code.value = ''; //清空
			fm.code.focus(); //将焦点移动到表单字段
			return false;		
		}

	}

	var register = document.getElementById('location');
	register.onclick = function () {
		location.href = 'register.php';
	}

}