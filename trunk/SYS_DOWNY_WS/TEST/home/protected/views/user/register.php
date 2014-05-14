<div class="fl b_logbox" style="width:650px;padding:20px;">
	<h2 style="text-align:center;font-size:28px;">注 册</h2>
	<form class="form-dl" id="register-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/register'); ?>" onsubmit="return false;">
		<dl>
			<dt><span>*</span>用户名</dt>
			<dd class="cc username">
				<span class="ctrl"><input type="text" name="username" /></span>
				<span class="msg username"></span>
				<span class="tips"></span>
			</dd>

			<dt><span>*</span>密码</dt>
			<dd class="cc password">
				<span class="ctrl"><input type="password" name="password" /></span>
				<span class="msg password"></span>
				<span class="tips">密码长度6到10位</span>
			</dd>

			<dt><span>*</span>确认密码</dt>
			<dd class="cc cfm_password">
				<span class="ctrl"><input type="password" name="cfm_password" /></span>
				<span class="msg cfm_password"></span>
				<span class="tips">两次输入的密码必须一致</span>
			</dd>

			<dt><span>*</span>邮箱</dt>
			<dd class="cc email">
				<span class="ctrl"><input type="text" name="email" value="<?php echo $request['email']; ?>" /></span>
				<span class="msg email"></span>
				<span class="tips"></span>
			</dd>

			<dt><span>*</span>真实姓名</dt>
			<dd class="cc real_name">
				<span class="ctrl"><input type="text" name="real_name" /></span>
				<span class="msg real_name"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span>*</span>学位</dt>
			<dd class="cc degree">
				<span class="ctrl">
					<?php foreach($degree_list as $k => $v){ ?>
						<?php echo $v; ?><input type="radio" name="degree" value="<?php echo $k; ?>" />
					<?php } ?>
				</span>
				<span class="msg degree"></span>
				<span class="tips"></span>
			</dd>

			<dt><span>*</span>工作单位</dt>
			<dd class="cc organization">
				<span class="ctrl"><input type="text" name="organization" /></span>
				<span class="msg organization"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span>*</span>电话号码</dt>
			<dd class="cc phone">
				<span class="ctrl"><input type="text" name="phone" /></span>
				<span class="msg phone"></span>
				<span class="tips">区号-电话号码</span>
			</dd>

			<dt><span>*</span>手机号码</dt>
			<dd class="cc mobile">
				<span class="ctrl"><input type="text" name="mobile" /></span>
				<span class="msg mobile"></span>
				<span class="tips">电话和手机号码任填其一即可</span>
			</dd>
		
			<dt><span>*</span>性别</dt>
			<dd class="cc gender">
				<span class="ctrl">
					<?php foreach($gender_list as $k => $v){ ?>
						<?php echo $v; ?><input type="radio" name="gender" value="<?php echo $k; ?>" />
					<?php } ?>
				</span>
				<span class="msg gender"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span>*</span>身份证号码</dt>
			<dd class="cc identity">
				<span class="ctrl"><input type="text" name="identity" maxlength="18" /></span>
				<span class="msg identity"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span>*</span>地区</dt>
			<dd class="cc region_id">
				<span class="ctrl">
					<select name="region_state"><option value="0">请选择</option></select>
					<select name="region_city"><option value="0">请选择</option></select>
					<select name="region_district"><option value="0">请选择</option></select>
				</span>
				<span class="msg region_id"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span>*</span>通讯地址</dt>
			<dd class="cc address">
				<span class="ctrl"><input type="text" name="address" /></span>
				<span class="msg address"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span>*</span>邮编</dt>
			<dd class="cc zip">
				<span class="ctrl"><input type="text" name="zip" /></span>
				<span class="msg zip"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span></span>职称</dt>
			<dd class="cc title">
				<span class="ctrl"><input type="text" name="title" /></span>
				<span class="msg title"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span></span>工作语言</dt>
			<dd class="cc language">
				<span class="ctrl">
					<select name="language"><option value="0">请选择</option>
					<?php foreach($language_list as $k => $v){ ?>
						<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
					<?php } ?>
					</select>
				</span>
				<span class="msg language"></span>
				<span class="tips"></span>
			</dd>
		
			<dt><span></span>研究方向</dt>
			<dd class="cc subject">
				<span class="ctrl"><input type="text" name="subject" /></span>
				<span class="msg subject"></span>
				<span class="tips"></span>
			</dd>

			<dt><span></span>专长</dt>
			<dd class="cc feature">
				<span class="ctrl"><textarea name="feature"></textarea></span>
				<span class="msg feature"></span>
				<span class="tips"></span>
			</dd>

			<dt><span></span>个人简介</dt>
			<dd class="cc brief">
				<span class="ctrl"><textarea name="brief"></textarea></span>
				<span class="msg brief"></span>
				<span class="tips"></span>
			</dd>

			<dt><span>*</span>验证码</dt>
			<dd class="cc captcha">
				<span class="ctrl"><input type="text" name="captcha" /></span></span>
				<span class="msg captcha"></span>
				<span class="tips"></span>
			</dd>

			<dt><span></span></dt>
			<dd class="cc agree">
				<span class="ctrl"><input type="checkbox" name="agree" /></span>
				<span class="tips">我已阅读并接受《投稿须知》</span>
				<span class="msg agree"></span>
			</dd>
			
			<dt><span></span></dt>
			<dd class="cc">
				<input type="submit" />
			</dd>

		</dl>
	</form>
</div>
<script type="text/javascript">
(function (){
	var FIELDS = [
		['用户名', 'username', ['input[name=username]']],
		['密码', 'password', ['input[name=password]', function(){
			if($('input[name=password]').val().length < 6){
				return ['password', '密码至少6位'];
			}
		}]],
		['确认密码', 'cfm_password', [function(){
			if($('input[name=password]').val() != $('input[name=cfm_password]').val()){
				return ['cfm_password', '确认密码与密码不一致'];
			}
		}]],
		['邮箱', 'email', ['input[name=email]', function(){
			var re = /\w@\w*\.\w/;
			if(!re.test($('input[name=email]').val())){
				return ['email', '邮箱格式错误'];
			}
		}]],
		['真实姓名', 'real_name', ['input[name=real_name]']],
		['学位', 'degree', [function(){
			var checked = false;
			$('input[name=degree]').each(function(){
				if($(this).is(':checked')){
					checked = true;
				}
			});
			if(!checked){
				return ['degree', '请选择学位'];
			}
		}]],
		['工作单位', 'organization', ['input[name=organization]']],
		['电话号码', 'phone', [function(){
			if(
				($.trim($('input[name=phone]').val()) == '') &&
				($.trim($('input[name=mobile]').val()) == '')
			){
				return ['phone', '电话和手机必须填写其一'];
			}
		}]],
		['手机号码', 'mobile', [function(){
			if(
				($.trim($('input[name=phone]').val()) == '') &&
				($.trim($('input[name=mobile]').val()) == '')
			){
				return ['mobile', '电话和手机必须填写其一'];
			}
		}]],
		['性别', 'gender', [function(){
			var checked = false;
			$('input[name=gender]').each(function(){
				if($(this).is(':checked')){
					checked = true;
				}
			});
			if(!checked){
				return ['gender', '请选择性别'];
			}
		}]],
		['身份证号码', 'identity', ['input[name=identity]']],
		['地区', 'region_id', [function(){
			if($('select[name=region_state]').val() == 0){
				return ['region_id', '请选择地区'];
			}
		}]],
		['通讯地址', 'address', ['input[name=address]']],
		['邮编', 'zip', ['input[name=zip]']],
		['验证码', 'captcha', ['input[name=captcha]']],
		['接受《投稿须知》', 'agree', [function(){
			if(!$('input[name=agree]').is(':checked')){
				return ['agree', '请先接受投稿须知'];
			}
		}]]
	];
	
	// 注册
	$('#register-form').submit(function (){
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
					location.href = res.url || '/';
				}
			}, 'json');
		}

		return false;
	});

	// 会员名 邮箱 AJAX检查
	$('input[name=username], input[name=email]').blur(function(){
		var key = $(this).attr('name');
		var val = $(this).val();

		if(val != ''){
			$.ajax({
				url: '/user/fieldExists',
				dataType: 'json',
				cache: false,
				data: {key: key, val: val},
				success: function(res){
					if(!res.success){
						errorMsg(key, res.message);
					}
				}
			});
		}
	});

	// 地区
	$.fn.regionSel(1, 'select[name=region_state]', 'select[name=region_city]', 'select[name=region_district]');

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
