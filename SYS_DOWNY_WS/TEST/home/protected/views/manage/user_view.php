<form id="user-view-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('manage/userView'); ?>" onsubmit="return false;">
	<div class="fl">
		<h2>基本信息</h2>
		<?php if($data['id']){ ?>
			<table>
				<tr><th>编号</th><td><?php echo $data['id']; ?><input type="hidden" name="id" value="<?php echo $data['id']; ?>" /></td></tr>
				<tr class="real_name"><th>真实姓名</th><td><input type="text" name="real_name" value="<?php echo $data['real_name']; ?>" /><span class="msg real_name"></span></td></tr>
				<tr><th>用户名</th><td><?php echo $data['username']; ?></td></tr>
				<tr><th>邮箱</th><td><?php echo $data['email']; ?></td></tr>
				<tr><th>密码</th><td><input type="button" value="重置密码" class="reset_pwd" /></td></tr>
				<tr><th>访问时间</th><td><?php echo date('Y-m-d', $data['visit_time']); ?></td></tr>
			</table>
		<?php }else{ ?>
			<table>
				<tr><th>编号</th><td>-<input type="hidden" name="id" value="0" /></td></tr>
				<tr class="real_name"><th>真实姓名</th><td><input type="text" name="real_name" /><span class="msg real_name"></span></td></tr>
				<tr class="username"><th>用户名</th><td><input type="text" name="username" /><span class="msg username"></span></td></tr>
				<tr class="email"><th>邮箱</th><td><input type="text" name="email" /><span class="msg email"></span></td></tr>
				<tr class="password"><th>密码</th><td><input type="password" name="password" /><span class="msg password"></span></td></tr>
				<tr><th>访问时间</th><td>-</td></tr>
			</table>
		<?php } ?>
		<h2>角色</h2>
		<table>
			<?php 
				$exists = [];
				if($data['id'])
				{
					foreach($data['roles'] as $v)
					{
						$exists[] = $v['name'];
					}
				}
			?>
			<?php foreach($roles as $v){ ?>
			<tr><th><?php echo $v['name']; ?></th><td><input type="checkbox" name="role[]" <?php echo (in_array($v['name'], $exists) ? 'checked="checked"' : ''); ?> value="<?php echo $v['name']; ?>" /></td></tr>
			<?php } ?>
		</table>
	</div>
	<div class="fr">
		<h2>会员信息</h2>
		<table>
			<tr class="degree"><th>学位</th><td>
				<?php foreach($degree_list as $k => $v){ ?>
					<?php echo $v; ?><input type="radio" name="degree" value="<?php echo $k; ?>" />
				<?php } ?>
				<span class="msg degree"></span>
			</td></tr>
			<tr class="organization"><th>工作单位</th><td><input type="text" name="organization" /><span class="msg organization"></span></td></tr>
			<tr class="phone"><th>电话号码</th><td><input type="text" name="phone" /><span class="msg phone"></span></td></tr>
			<tr class="mobile"><th>手机号码</th><td><input type="text" name="mobile" /><span class="msg mobile"></span></td></tr>
			<tr class="gender"><th>性别</th><td>
				<?php foreach($gender_list as $k => $v){ ?>
					<?php echo $v; ?><input type="radio" name="gender" value="<?php echo $k; ?>" />
				<?php } ?>
				<span class="msg gender"></span>
			</td></tr>
			<tr class="identity"><th>身份证号码</th><td><input type="text" name="identity" /><span class="msg identity"></span></td></tr>
			<tr class="region_id"><th>地区</th><td>
				<select name="region_state"><option value="0">请选择</option></select>
				<select name="region_city"><option value="0">请选择</option></select>
				<select name="region_district"><option value="0">请选择</option></select>
				<span class="msg region_id"></span>
			</td></tr>
			<tr class="address"><th>通讯地址</th><td><input type="text" name="address" /><span class="msg address"></span></td></tr>
			<tr class="zip"><th>邮编</th><td><input type="text" name="zip" /><span class="msg zip"></span></td></tr>
			<tr class="title"><th>职称</th><td><input type="text" name="title" /><span class="msg title"></span></td></tr>
			<tr class="language"><th>工作语言</th><td>
				<?php foreach($language_list as $k => $v){ ?>
					<?php echo $v; ?><input type="radio" name="language" value="<?php echo $k; ?>" />
				<?php } ?>
				<span class="msg language"></span>
			</td></tr>
			<tr class="subject"><th>研究方向</th><td><input type="text" name="subject" /><span class="msg subject"></span></td></tr>
			<tr class="feature"><th>专长</th><td><textarea name="feature"></textarea><span class="msg feature"></span></td></tr>
			<tr class="brief"><th>个人简介</th><td><textarea name="brief"></textarea><span class="msg brief"></span></td></tr>
		</table>
	</div>
	<div class="cc"><input type="submit" value="保存" /></div>
</form>
<script type="text/javascript">
(function (){
	// 重置
	$('.reset_pwd').click(function(){
		if(confirm('确认重置密码？')){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo Yii::app()->request->baseUrl . $this->createUrl('manage/userResetPwd', ['id' => $data['id']]); ?>',
				async: false,
				cache: false,
				success: function(res){
					alert(res.message);
				}
			});
		}
	});

	// 保存
	$('#user-view-form').submit(function (){
		var id = parseInt($('input[name=id]').val());
		$.post($(this).attr('action'), $(this).serialize(), function(res){
			if(res.message){
				alert(res.message);
			}
			if(!res.success){
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
				if(id){
					grid.reloadRows(id);
				}else{
					window.location.href = '<?php echo Yii::app()->request->baseUrl . $this->createUrl('manage/user'); ?>';
				}
			}
		}, 'json');
		return false;
	});

	// 得到焦点移除错误信息
	$('span.msg').each(function(){
		$(this).parent().bind('click keyup', function(){
			$(this).find('span.msg').html('');
		});
	});

	// 错误信息
	var errorMsg = function(field, msg){
		if(msg == ''){
			$('span.msg.' + field).html('');
		}else{
			$('span.msg.' + field).html('<img src="/images/false.png" title="' + msg + '" />');
		}
	}

	// 初始化
	<?php if($data['id']){ ?>
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
		$('input[name=language]').each(function(){
			if($(this).val() == AUTHOR.language){
				$(this).attr('checked', 'checked');
			}
		});
		$('input[name=subject]').val(AUTHOR.subject);
		$('textarea[name=feature]').val(AUTHOR.feature);
		$('textarea[name=brief]').val(AUTHOR.brief);
	<?php } ?>
	$.fn.regionSel(<?php echo ($data['id'] ? $data['author']['region_id'] : 1); ?>, 'select[name=region_state]', 'select[name=region_city]', 'select[name=region_district]');
})();
</script>
