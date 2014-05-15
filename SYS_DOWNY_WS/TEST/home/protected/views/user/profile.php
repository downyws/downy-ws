<h1><?php echo $title; ?></h1>
<div class="section">
	<form class="form-dl" id="profile-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('user/profile'); ?>" onsubmit="return false;">
	<dl>
		
		<dt><span>*</span>真实姓名</dt>
		<dd class="cc real_name">
			<span class="ctrl"><input type="text" name="real_name" /></span>
			<span class="msg real_name"></span>
			<span class="tips"></span>
		</dd>

		<dt><span>*</span>邮箱</dt>
		<dd class="cc email">
			<span class="ctrl"><input type="text" name="email" /></span>
			<span class="msg email"></span>
			<span class="tips"></span>
		</dd>

		<dt><span></span>&nbsp;</dt>
		<dd class="cc email">
			<span class="ctrl">&nbsp;</span>
			<span class="msg email"></span>
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
			<span class="tips"></span>
		</dd>

		<dt><span>*</span>手机号码</dt>
		<dd class="cc mobile">
			<span class="ctrl"><input type="text" name="mobile" /></span>
			<span class="msg mobile"></span>
			<span class="tips"></span>
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
			<span class="ctrl"><input type="text" name="identity" /></span>
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
			<span class="msg region_id" data-focus-obj="select[name=region_state]"></span>
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
			<span class="msg language" data-focus-obj="select[name=language]"></span>
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

		<dt><span></span></dt>
		<dd class="cc">
			<input type="submit" value="保存" />
		</dd>
	</dl>

	</form>
</div>
<script type="text/javascript">
(function (){

	var setDetailHeight = function (){
		var height = 0;
		var div = $('.body').parent();
		div.prevAll().each(function (){
			height += $(this).outerHeight();
		});

		height = $('nav').height() - height;
		height -= div.outerHeight() - div.height();

		$('.body').css({height: height, overflow: 'auto'});
	};
	setDetailHeight();

	var FIELDS = [
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
		['邮编', 'zip', ['input[name=zip]']]
	];

	// 修改
	$('#profile-form').submit(function (){
		var post = true;
		var need_break = false;
		for(var k in FIELDS){
			need_break = false;
			$.fn.errorMsg(FIELDS[k][1], '');
			if(typeof(FIELDS[k][2]) == 'object' && FIELDS[k][2].length > 0){
				for(var _k in FIELDS[k][2]){
					if(need_break){
						break;
					}
					switch(typeof(FIELDS[k][2][_k])){
						case 'string':
							if($.trim($(this).find(FIELDS[k][2][_k]).val()) == ''){
								$.fn.errorMsg(FIELDS[k][1], '请填写' + FIELDS[k][0]);
								post = false;
								need_break = true;
							}
							break;
						case 'function':
							var data = FIELDS[k][2][_k]();
							if(typeof(data) == 'object' && data.length == 2){
								$.fn.errorMsg(data[0], data[1]);
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
						$.fn.errorMsg(k, msg);
					}
				}else if(res.success && !res.message){
					alert('保存成功');
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

	var USER = <?php echo CJavaScript::jsonEncode($data['user']);?>;
	$('input[name=real_name]').val(USER.real_name);
	$('input[name=email]').val(USER.email);
	var AUTHOR = <?php echo CJavaScript::jsonEncode($data['author']);?>;
	$('input[name=degree]').each(function(){
		if($(this).val() == AUTHOR.degree){
			$(this).attr('checked', 'checked');
		}
	});
	$('input[name=organization]').val(AUTHOR.organization);
	$('input[name=phone]').val(AUTHOR.phone);
	$('input[name=mobile]').val(AUTHOR.mobile);
	$('input[name=gender]').each(function(){
		if($(this).val() == AUTHOR.gender){
			$(this).attr('checked', 'checked');
		}
	});
	$('input[name=identity]').val(AUTHOR.identity);
	$('input[name=address]').val(AUTHOR.address);
	$('input[name=zip]').val(AUTHOR.zip);
	$('input[name=title]').val(AUTHOR.title);
	$('select[name=language] option').each(function(){
		if($(this).val() == AUTHOR.language){
			$(this).attr('selected', 'selected');
		}
	});
	$('input[name=subject]').val(AUTHOR.subject);
	$('textarea[name=feature]').val(AUTHOR.feature);
	$('textarea[name=brief]').val(AUTHOR.brief);

	$.fn.regionSel(parseInt(AUTHOR.region_id) ? AUTHOR.region_id : 1, 'select[name=region_state]', 'select[name=region_city]', 'select[name=region_district]');
})();
</script>
