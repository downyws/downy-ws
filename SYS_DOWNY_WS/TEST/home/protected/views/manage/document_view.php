<form id="document-view-form" action="<?php echo Yii::app()->request->baseUrl . $this->createUrl('manage/documentView'); ?>" onsubmit="return false;">
	<div class="div-rows">
		<table>
			<tr><th>编号</th><td><?php echo $data['id']; ?><input type="hidden" name="id" value="<?php echo $data['id']; ?>" /></td></tr>
			<tr class="column"><th>栏目</th><td>
				<span class="ctrl"><select name="column">
					<?php foreach($column as $v){ ?>
						<option <?php echo ($v['id'] == $data['column']) ? 'selected="selected"' : ''; ?> value="<?php echo $v['id']; ?>"><?php echo $v['title']; ?></option>
					<?php } ?>
				</select></span>
				<span class="msg column" data-focus-obj="select"></span>
			</td></tr>
			<tr class="title"><th>标题</th><td>
				<span class="ctrl"><input type="text" class="w300" name="title" value="<?php echo $data['title']; ?>" /></span>
				<span class="msg title"></span>
			</td></tr>
			<tr class="content"><th>内容</th><td>
				<span class="ctrl"><textarea class="w450 h200" name="content"><?php echo $data['content']; ?></textarea></span>
				<span class="msg content"></span>
			</td></tr>
			<tr class="code"><th>标识</th><td>
				<span class="ctrl"><input type="text" class="w300" name="code" value="<?php echo $data['code']; ?>" /></span>
				<span class="msg code"></span>
			</td></tr>
		</table>
	</div>
	<div class="div-rows tac"><input type="submit" value="保存" /></div>
</form>
<script type="text/javascript">
(function (){
	// 保存
	$('#document-view-form').submit(function (){
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
						$.fn.errorMsg(k, msg);
					}
				}
			}else{
				grid.reloadRows(id);
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
})();
</script>
