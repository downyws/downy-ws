<h1><?php echo $title; ?></h1>
<div class="section">
	<form class="form-dl" id="system-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('manage/system'); ?>" onsubmit="return false;">
	<dl>
		<?php foreach($config as $v){ ?>
		<dt><span></span><?php echo $v['key']; ?></dt>
		<dd class="cc username">
			<span class="ctrl"><input type="text" name="<?php echo $v['key']; ?>" value="<?php echo $v['value']; ?>" /></span>
			<span class="msg <?php echo $v['key']; ?>"></span>
			<span class="tips"></span>
		</dd>
		<?php } ?>

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
		<?php foreach($config as $v){ ?>
		['<?php echo $v['key'];?>', '<?php echo $v['key'];?>', ['input[name=<?php echo $v['key'];?>]']],
		<?php } ?>
	];
	// 注册
	$('#system-form').submit(function (){
		var post = true;
		for(var k in FIELDS){
			errorMsg(FIELDS[k][1], '');
			if(typeof(FIELDS[k][2]) == 'object' && FIELDS[k][2].length > 0){
				for(var _k in FIELDS[k][2]){
					if($.trim($(this).find(FIELDS[k][2][_k]).val()) == ''){
						errorMsg(FIELDS[k][1], '请填写值');
						post = false;
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
