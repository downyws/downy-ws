<h1><?php echo $title; ?></h1>
<div class="section">
	<form class="form-dl" id="password-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/password'); ?>" onsubmit="return false;">
	<dl>
		<dt><span></span>原密码</dt>
		<dd class="cc old_password">
			<span class="ctrl"><input type="password" name="old_password" /></span>
			<span class="msg old_password"></span>
			<span class="tips"></span>
		</dd>

		<dt><span></span>新密码</dt>
		<dd class="cc password">
			<span class="ctrl"><input type="password" name="password" /></span>
			<span class="msg password"></span>
			<span class="tips"></span>
		</dd>

		<dt><span></span>确认密码</dt>
		<dd class="cc cfm_password">
			<span class="ctrl"><input type="password" name="cfm_password" /></span>
			<span class="msg cfm_password"></span>
			<span class="tips"></span>
		</dd>

		<dt><span></span></dt>
		<dd class="cc">
			<input type="submit" value="保存" />
		</dd>
	</dl>
	</form>
</div>
<script type="text/javascript">
(function (){
	var FIELDS = [
		['旧密码', 'old_password', ['input[name=old_password]']],
		['新密码', 'password', ['input[name=password]', function(){
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
	// 注册
	$('#password-form').submit(function (){
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
				if(res.message){
					alert(res.message);
				}
				if(!res.success && res.errors){
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
				}else if(res.success && !res.message){
					alert('保存成功');
				}
				if(res.success){
					window.location.reload();
				}
			}, 'json');
		}

		return false;
	});

	// 得到焦点移除错误信息
	for(var k in FIELDS){
		$('span.msg.' + FIELDS[k][1]).parent().bind('click keyup', function(){
			$(this).find('span.msg').html('');
		});
	}

	// 错误信息
	var errorMsg = function(field, msg){
		if(msg == ''){
			$('span.msg.' + field).html('');
		}else{
			$('span.msg.' + field).html('<img src="/images/false.png" title="' + msg + '" />');
		}
	}
})();
</script>
