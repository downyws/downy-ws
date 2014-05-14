<h1><?php echo $title; ?></h1>
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
var grid = null;
$(function (){
	var struct = <?php echo json_encode([[
		'title' => '编号',
		'field' => 'id',
		'width' => 50,
	], [
		'title' => '栏目',
		'field' => 'column',
		'width' => 70,
	], [
		'title' => '标题',
		'field' => 'title',
		'width' => 200,
	], [
		'title' => '标识',
		'field' => 'code',
		'width' => 200,
	], [
		'title' => '创建日期',
		'field' => 'create_time',
		'width' => 140,
	], [
		'title' => '更新日期',
		'field' => 'update_time',
		'width' => 140,
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
	grid = $(".list").css({height: height}).dataGrid({
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
			$('.detail').load('/manage/documentView?id=' + grid.getSelected()[0] + '&t=' + Math.random(), function (){
				setDetailHeight();
			});
		}
	});

	grid.reloadRows = function(ids){
		$.post('/manage/documentRows', 'ids=' + ids, function (res){
			if(res.success){
				var rows = {};
				for(var i = 0; i < res.data.length; i++){
					rows[res.data[i][0]] = res.data[i][1];
				}
				grid.update('id', rows);
			}else{
				alert(res.message);
			}
		}, 'json');
	}

	$(window).resize(setDetailHeight);
});
</script>