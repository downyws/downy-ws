<div class="fl b_logbox">
	<h2>注 册</h2>
	<form id="register-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/register'); ?>" onsubmit="return false;">
		<!--<dl><dt></dt><dd></dd>....<input type=submit>-->

		*用户名
		<input type="text" name="username" />
		<span class="username"></span>
		4-20个字符(包括小写字母、数字、下划线、中文)，一个汉字为两个字符，推荐使用中文会员名。一旦注册成功会员名不能修改。
		<hr />

		*密码
		<input type="password" name="password" />
		<span class="password"></span>
		密码长度6到10位
		<hr />

		*确认密码
		<input type="password" name="cfm_password" />
		<span class="cfm_password"></span>
		 两次输入的密码必须一致
		<hr />
		
		*邮箱
		<input type="text" name="email" />
		<span class="email"></span>
		<hr />

		*真实姓名
		<input type="text" name="real_name" />
		<span class="real_name"></span>
		<hr />
		
		*学位
		<?php foreach($degree_list as $k => $v){ ?>
			<?php echo $v; ?><input type="radio" name="degree" value="<?php echo $k; ?>" />
		<?php } ?>
		<span class="degree"></span>
		<hr />
		
		*工作单位
		<input type="text" name="organization" />
		<span class="organization"></span>
		<hr />
		
		*电话号码
		<input type="text" name="phone" />
		<span class="phone"></span>
		区号-电话号码
		<hr />
		
		*手机号码
		<input type="text" name="mobile" />
		<span class="mobile"></span>
		电话和手机号码任填其一即可
		<hr />
		
		*性别
		男<input type="radio" name="gender" value="1" />
		女<input type="radio" name="gender" value="2" />
		<span class="gender"></span>
		<hr />
		
		*身份证号码
		<input type="text" name="identity" />
		<span class="identity"></span>
		<hr />
		
		*地区	
		<select name="region_state"><option>省</option></select>
		<select name="region_city"><option>市</option></select>
		<select name="region_district"><option>区</option></select>
		<span class="region"></span>
		<hr />
		
		*通讯地址
		<input type="text" name="address" />
		<span class="address"></span>
		<hr />
		
		*邮编
		<input type="text" name="zip" />
		<span class="zip"></span>
		<hr />
		
		职称
		<input type="text" name="title" />
		<hr />
		
		工作语言
		<input type="text" name="language" />
		<hr />
		
		研究方向
		<input type="text" name="subject" />
		<hr />

		专长
		<textarea name="feature"></textarea>
		<hr />

		个人简介
		<textarea name="brief"></textarea>
		<hr />

		*验证码
		<div class="captcha_img"><input type="text" name="captcha" /></div>
		<span class="captcha"></span>
		<hr />

		我已阅读并接受《投稿须知》
		<input type="checkbox" name="agree" />
		<span class="agree"></span>
		<hr />

		<input type="submit" />
	</form>
</div>
<style>input{border:1px solid #FF0000;}</style>
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
		['地区', 'region', [function(){
			if($('select[name=region_state]').val() == 0){
				return ['region', '请选择地区'];
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
					for(var k in res.errors){
						errorMsg(k, res.errors[k]);
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
	var obj = $('form .captcha_img');
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
		.css({'vertical-align':'middle', 'width':'100px', 'height':'36px'})
		.attr('title', '如果看不清楚验证码，请点击本图片框刷新．不区分大小写');

	// 得到焦点移除错误信息
	for(var k in FIELDS){
		$('.' + FIELDS[k][1]).click(function(){
			$(this).html('coding...');
		});
	}

	// 错误信息
	var errorMsg = function(field, msg){
		if(msg == ''){
			$('.' + field).html('');
		}else{
			$('.' + field).html('<img src="/images/false.png" title="' + msg + '" />');
		}
	}
})();
</script>
