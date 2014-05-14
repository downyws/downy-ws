<div class="fl b_logbox" style="width:650px;padding:20px;">
	<?php if(isset($error)){ ?>
		<h2 style="text-align:center;font-size:28px;">找回密码</h2>
		重置链接有错或已过期。请您重新找回密码。
	<?php }else if(isset($code)){ ?>
		<h2 style="text-align:center;font-size:28px;">设置新密码</h2>
		<form class="form-dl" id="recover-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/recover'); ?>" onsubmit="return false;">
			<input type="hidden" name="key" value="<?php echo $key; ?>" />
			<input type="hidden" name="code" value="<?php echo $code; ?>" />
			<dl>
				<dt><span></span>新密码</dt>
				<dd class="cc email">
					<span class="ctrl"><input type="password" name="password" /></span>
					<span class="msg password"></span>
					<span class="tips"></span>
				</dd>

				<dt><span></span>确认密码</dt>
				<dd class="cc email">
					<span class="ctrl"><input type="password" name="cfm_password" /></span>
					<span class="msg cfm_password"></span>
					<span class="tips"></span>
				</dd>

				<dt><span></span></dt>
				<dd class="cc">
					<input type="submit" value="确定" />
				</dd>
			</dl>
		</form>
		<script type="text/javascript">
		var FIELDS = [
			['密码', 'password', ['input[name=password]', function(){
				if($('input[name=password]').val().length < 6){
					return ['password', '密码至少6位'];
				}
			}]],
			['确认密码', 'cfm_password', [function(){
				if($('input[name=password]').val() != $('input[name=cfm_password]').val()){
					return ['cfm_password', '确认密码与密码不一致'];
				}
			}]]
		];
		(function (){
			// 修改密码
			$('#recover-form').submit(function (){
				var post = true;
				var need_break = false;
				for(var k in FIELDS){
					need_break = false;
					errorMsg(FIELDS[k][1], '');
					if(typeof(FIELDS[k][2]) == 'object' && FIELDS[k][2].length > 0){
						for(var _k in FIELDS[k][2]){
							if(need_break){
								break;
							}
							switch(typeof(FIELDS[k][2][_k])){
								case 'string':
									if($.trim($(this).find(FIELDS[k][2][_k]).val()) == ''){
										errorMsg(FIELDS[k][1], '请填写' + FIELDS[k][0]);
										post = false;
										need_break = true;
									}
									break;
								case 'function':
									var data = FIELDS[k][2][_k]();
									if(typeof(data) == 'object' && data.length == 2){
										errorMsg(data[0], data[1]);
										post = false;
										need_break = true;
									}
									break;
							}
						}
					}
				}

				if(post){
					$.post($(this).attr('action'), $(this).serialize(), function(res){
						if(!res.success){
							alert(res.message);
						}else{
							window.location.href = res.url;
						}
					}, 'json');
				}

				return false;
			});

		})();
		</script>
	<?php }else{ ?>
		<h2 style="text-align:center;font-size:28px;">找回密码</h2>
		<form class="form-dl" id="recover-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/recover'); ?>" onsubmit="return false;">
			<dl>
				<dt><span></span>邮箱</dt>
				<dd class="cc email">
					<span class="ctrl"><input type="text" name="email" /></span>
					<span class="msg email"></span>
					<span class="tips"></span>
				</dd>

				<dt><span></span>验证码</dt>
				<dd class="cc captcha">
					<span class="ctrl"><input type="text" name="captcha" /></span></span>
					<span class="msg captcha"></span>
					<span class="tips"></span>
				</dd>

				<dt><span></span></dt>
				<dd class="cc">
					<input type="submit" value="确定" />
				</dd>
			</dl>
		</form>
		<script type="text/javascript">
		var FIELDS = [
			['邮箱', 'email', ['input[name=email]', function(){
				var re = /\w@\w*\.\w/;
				if(!re.test($('input[name=email]').val())){
					return ['email', '邮箱格式错误'];
				}
			}]],
			['验证码', 'captcha', ['input[name=captcha]']]
		];
		(function (){			
			// 注册
			$('#recover-form').submit(function (){
				var post = true;
				var need_break = false;
				for(var k in FIELDS){
					need_break = false;
					errorMsg(FIELDS[k][1], '');
					if(typeof(FIELDS[k][2]) == 'object' && FIELDS[k][2].length > 0){
						for(var _k in FIELDS[k][2]){
							if(need_break){
								break;
							}
							switch(typeof(FIELDS[k][2][_k])){
								case 'string':
									if($.trim($(this).find(FIELDS[k][2][_k]).val()) == ''){
										errorMsg(FIELDS[k][1], '请填写' + FIELDS[k][0]);
										post = false;
										need_break = true;
									}
									break;
								case 'function':
									var data = FIELDS[k][2][_k]();
									if(typeof(data) == 'object' && data.length == 2){
										errorMsg(data[0], data[1]);
										post = false;
										need_break = true;
									}
									break;
							}
						}
					}
				}

				if(post){
					$.post($(this).attr('action'), $(this).serialize(), function(res){
						if(!res.success){
							if(res.interval){
								alert('该邮箱已经发送过重置邮件，您可以请等待' + res.interval + '秒后再次发送。');
								$('form .captcha .ctrl img').click();
							}
							if(res.message){
								alert(res.message);
							}
							if(res.errors){
								for(var k in res.errors){
									var msg = '';
									if(typeof(res.errors[k]) == 'object'){
										for(var _k in res.errors[k]){
											msg += res.errors[k][_k] + "\n";
										}
									}else if(typeof(res.errors[k]) == 'string'){
										msg = res.errors[k];
									}else{
										alert('无法解析返回信息');
									}
									errorMsg(k, msg);
								}
							}
						}else{
							$('form').html('重置密码链接发送成功，请检查您的邮箱。');
						}
					}, 'json');
				}

				return false;
			});

			// 验证码
			var obj = $('form .captcha .ctrl');
			obj.append('<img />');
			obj.on('click', 'img', function(){
				$.ajax({
					url: '/user/captcha?refresh=1',
					dataType: 'json',
					cache: false,
					success: function(data){
						obj.find('img').attr('src', data['url']);
						$('body').data('captcha.hash', [data['hash1'], data['hash2']]);
					}
				});
				return false;
			});
			obj.find('img').click()
				.css({'vertical-align':'middle', 'width':'100px', 'height':'36px', 'margin-left':'10px'})
				.attr('title', '如果看不清楚验证码，请点击本图片框刷新．不区分大小写');
		})();
		</script>
	<?php } ?>
</div>
<script type="text/javascript">
(function (){
	if(typeof(FIELDS) != 'undefined'){
		// 得到焦点移除错误信息
		for(var k in FIELDS){
			$('span.msg.' + FIELDS[k][1]).parent().bind('click keyup', function(){
				$(this).find('span.msg').html('');
			});
		}
	}
})();

// 错误信息
var errorMsg = function(field, msg){
	if(msg == ''){
		$('span.msg.' + field).html('');
	}else{
		$('span.msg.' + field).html('<img src="/images/false.png" title="' + msg + '" />');
	}
}
</script>
