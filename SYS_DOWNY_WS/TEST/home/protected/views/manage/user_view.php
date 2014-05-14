<form id="user-view-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('manage/userView'); ?>" onsubmit="return false;">
	<div class="fl">
		<h2>基本信息</h2>
		<?php if($data['id']){ ?>
			<table>
				<tr><th>编号</th><td><?php echo $data['id']; ?><input type="hidden" name="id" value="<?php echo $data['id']; ?>" /></td></tr>
				<tr class="real_name"><th>真实姓名</th><td><input type="text" name="real_name" value="<?php echo $data['real_name']; ?>" /><span class="msg real_name"></span></td></tr>
				<tr><th>用户名</th><td><?php echo $data['username']; ?></td></tr>
				<tr class="email"><th>邮箱</th><td><input type="text" name="email" value="<?php echo $data['email']; ?>" /><span class="msg email"></span></td></tr>
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
	</div>
	<div class="fr">
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
})();
</script>
