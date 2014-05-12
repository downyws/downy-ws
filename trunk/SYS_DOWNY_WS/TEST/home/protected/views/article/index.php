<h1><?php echo $title; ?></h1>
<div class="section">
	<form class="filter" method="post">
		<label>关键词：<input type="text" name="keywords" value="<?php echo $filters['keywords']; ?>"></label> <input type="submit" value="搜索">
	</form>
</div>
<div class="section">
	<div class="list">
	</div>
</div>
<div class="section cc">
	<div class="detail">
	</div>
</div>
<script type="text/javascript" src="/js/jquery-datagrid.js"></script>
<script type="text/javascript">

$(function (){
	var struct = <?php echo json_encode([[
		'title' => 'ID',
		'field' => 'id',
		'width' => 30,
	], [
		'title' => '编号',
		'field' => 'sn',
		'width' => 70,
	], [
		'title' => '标题',
		'field' => 'title',
		'width' => 300,
	], [
		'title' => '状态',
		'field' => 'status',
		'width' => 50,
	], [
		'title' => '作者',
		'field' => 'author',
		'width' => 60,
	], [
		'title' => '工作单位',
		'field' => 'organization',
		'width' => 175,
	], [
		'title' => '手机/固定电话',
		'field' => 'phone',
		'width' => 250,
	], [
		'title' => 'Email',
		'field' => 'email',
		'width' => 180,
	], [
		'title' => '投稿日期',
		'field' => 'create_time',
		'width' => 80,
	]]); ?>

	var data = <?php echo json_encode($list); ?>;

	var setDetailHeight = function (){
		var height = 0;
		var div = $('.detail').parent();
		div.prevAll().each(function (){
			height += $(this).outerHeight();
		});

		height = $('nav').height() - height;
		height -= div.outerHeight() - div.height();
		
		$('.detail').css({height: height});
	};

	var height = $('.body').height() - 400;
	if(height <= 200)
	{
		height = 200;
	}
	var grid = $(".list").css({height: height}).dataGrid({
		struct: struct,
		data: data,
		pageSize: 10,
		width: 60,
		defaultCustom: function(){
			var default_width = 50;
			var res = {width: default_width, cols: []};
			for(i = 0; i < this.struct.length; i++){
				res.cols.push({field: this.struct[i].field, width: this.struct[i].width ? this.struct[i].width : default_width});
			}
			return res;
		},
		select: function (){
			$('.detail').load('/audit/' + grid.getSelected()[0] + '?t=' + Math.random(), function (){
				setDetailHeight();
				$('.detail form a').click(function (){
					$(this).hide();
					$('.detail form .all').show();
					return false;
				});
				$('.detail select').change(function (){
					$(this).parent().find('input').prop('checked', true);
				});
				$('.detail form').submit(function (){
					var checked = $(this).find('input:checked');
					if(!checked.size()){
						alert('请选择操作！');
						return false;
					}
					var params = {status: checked.val()};
					
					var select = checked.parent().find('select');
					if(select.size())
						if(!parseInt(select.val())){
							alert('请选择指派给的人！');
							return false;
						}
						params.editor_id = select.val();
					}

					$.post('<?php echo $this->createUrl('audit/operate'); ?>', params, function (res){
						if(res.success){

						}else{
							alert(res.message);
						}
					});

					return false;
				});
			});
		}
	});

	$(window).resize(setDetailHeight);
});
</script>