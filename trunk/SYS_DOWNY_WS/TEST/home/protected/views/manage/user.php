<h1><?php echo $title; ?></h1>
<div class="section">
	<form class="filter" method="post">
		<div class="fl">
			<label>关键词：<input type="text" name="keywords" value="<?php echo $filters['keywords']; ?>" /></label>
			<input type="submit" value="搜索" />
		</div>
		<div class="fr">
			<input type="button" class="add" value="新增" />
			<input type="button" class="del" value="删除所选" />
		</div>
		<div class="cc"></div>
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
var grid = null;
$(function (){
	var struct = <?php echo json_encode([[
		'title' => '编号',
		'field' => 'id',
		'width' => 30,
	], [
		'title' => '会员名',
		'field' => 'username',
		'width' => 70,
	], [
		'title' => '真实姓名',
		'field' => 'real_name',
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
		'title' => '邮箱',
		'field' => 'email',
		'width' => 180,
	], [
		'title' => '身份证号码',
		'field' => 'identity',
		'width' => 180,
	], [
		'title' => '访问日期',
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
			$('.detail').load('/manage/userView?id=' + grid.getSelected()[0] + '&t=' + Math.random(), function (){
				setDetailHeight();
			});
		}
	});

	grid.reloadRows = function(ids){
		$.post('/manage/userRows', 'ids=' + ids, function (res){
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

	$('.section .filter .add').click(function(){
		$('.detail').load('/manage/userView?id=0&t=' + Math.random(), function (){
			setDetailHeight();
		});
	});
	$('.section .filter .del').click(function(){
		if(!confirm('是否确认要删除选中的数据？')){
			return false;
		}

		var objs = grid.fetchSelected();
		var ids = [];
		for(var i = 0; i < objs.length; i++){
			ids.push(parseInt(objs[i][0]));
		}
		ids = ids.join(',');

		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '/manage/userDelete',
			data: {ids: ids},
			async: false,
			cache: false,
			success: function(res){
				alert(res.message);
				if(res.success){
					alert('coding...grid中删除行');
				}
			}
		});
	});
});
</script>