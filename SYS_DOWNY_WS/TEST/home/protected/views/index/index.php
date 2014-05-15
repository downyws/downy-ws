<div class="intro">
	<ul>
		<li class="current"><?php echo $documents['GUIDE_POSTING']['title']; ?></li>
		<li><?php echo $documents['BRIEF_MAGAZINE']['title']; ?></li>
		<li><?php echo $documents['LATEEST_NOTICE']['title']; ?></li>
		<li><?php echo $documents['AGMT_COPYRIGHT']['title']; ?></li>
	</ul>
	<div class="content">
		<div><?php echo $documents['GUIDE_POSTING']['content']; ?></div>
		<div><?php echo $documents['BRIEF_MAGAZINE']['content']; ?></div>
		<div><?php echo $documents['LATEEST_NOTICE']['content']; ?></div>
		<div><?php echo $documents['AGMT_COPYRIGHT']['content']; ?></div>
	</div>
</div>
<div class="login">
	<h2>老用户登录</h2>
	<form id="login-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/login'); ?>" onsubmit="return false;">
		<dl>
			<dt>用户名：</dt>
			<dd><input type="text" class="text" name="username" /></dd>
			<dt>密码：</dt>
			<dd><input type="password" class="text" name="password" /></dd>
			<dt>验证码：</dt>
			<dd class="captcha_img">
				<input type="text" class="captcha" name="captcha" />
			</dd>
		</dl>
		<input type="submit" class="submit" value="登录">
		<a href="<?php echo $this->createUrl('user/recover'); ?>">忘记密码</a>
	</form>
</div>
<div class="register">
	<h2>新用户注册</h2>
	<form id="register-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/register'); ?>" onsubmit="return false;">
		<dl>
			<dt>Email：</dt>
			<dd><input type="text" class="text" name="email" /></dd>
		</dl>
		<input type="submit" class="submit" value="继续注册&nbsp;&gt;&gt;">
	</form>
</div>
<script type="text/javascript">
(function (){
	// 登录
	$('#login-form').submit(function (){
		var fields = [['username', '用户名'], ['password', '密码'], ['captcha', '验证码']];
		for(var k in fields){
			if($.trim($(this).find('input[name=' + fields[k][0] + ']').val()) == ''){
				alert('请输入' + fields[k][1]);
				$(this).find('input[name=' + fields[k][0] + ']').focus();
				return false;
			}
		}

		$.post($(this).attr('action'), $(this).serialize(), function(res){
			if(!res.success){
				alert(res.message);
			}else{
				location.href = res.url || '/';
			}
		}, 'json');

		return false;
	});

	// 注册
	$('#register-form').submit(function (){
		var fields = ['email'];
		var params = {};
		for(var k in fields){
			params[fields[k]] = $(this).find('input[name=' + fields[k] + ']').val();
		}
		location.href = $.fn.createUrl($(this).attr('action'), params);
	});

	// 验证码
	var obj = $('.login form dl dd.captcha_img');
	obj.append('<img />');
	obj.on('click', 'img', function(){
		$.ajax({
			url: '/user/captcha?refresh=1',
			dataType: 'json',
			cache: false,
			success: function(data) {
				obj.find('img').attr('src', data['url']);
				$('body').data('captcha.hash', [data['hash1'], data['hash2']]);
			}
		});
		return false;
	});
	obj.find('img').click()
		.css({'vertical-align':'middle', 'width':'100px', 'height':'36px'})
		.attr('title', '如果看不清楚验证码，请点击本图片框刷新．不区分大小写');

	$('.intro').tab('ul li', '.content div', {fade:true, speed: 3000, css: 'current'});
})();
</script>
