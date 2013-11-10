$(function(){
	// 初始化界面
	$("input.url").val(PARAMS.url);
	$("input.desc").val(PARAMS.desc);
	$("input.img").each(function(index){
		if(index + 1 <= PARAMS.img.length){
			$(this).val(PARAMS.img[index]);
			if(index == 4){
				$("a.plus").addClass("ui-disabled");
			}
		}else if(index != 0){
			$(this).parent().hide();
		}else{
			$("a.minus").addClass("ui-disabled");
		}
	});
	$(".site option").each(function(){
		if($(this).val() == PARAMS.tosite){
			$(".below img").attr("src", $(this).data("src"));
			$(this).attr("selected", "selected");
		}
	});
	function btnable(){
		var urlmatch = /^https?:\/\/([0-9a-z-]+\.)+[a-z]{2,4}\//;
		if($(".site option:selected").val() != "" && urlmatch.test($("input.url").val())){
			$("a.btn").removeClass("ui-disabled");
		}else{
			$("a.btn").addClass("ui-disabled");
		}
	}

	// 绑定事件
	$("input.url").change(btnable);
	$(".site").change(btnable);
	$("a.minus").click(function(){
		var prev = null;
		$("input.img").each(function(index){
			if($(this).parent().is(":hidden")){
				if(index > 1){
					if(index == 2){
						$("a.minus").addClass("ui-disabled");
					}
					prev.val("");
					prev.parent().hide();
					$("a.plus").removeClass("ui-disabled");
					return false;
				}
			}
			if(index == 4){
				$(this).val("");
				$(this).parent().hide();
				$("a.plus").removeClass("ui-disabled");
			}else{
				prev = $(this);
			}
		});

	});
	$("a.plus").click(function(){
		$("input.img").each(function(index){
			if($(this).parent().is(":hidden")){
				$(this).parent().show();
				if(index == 4){
					$("a.plus").addClass("ui-disabled");
				}
				$("a.minus").removeClass("ui-disabled");
				return false;
			}
		});
	});
	$("a.btn").click(function(){
		var url = "/mobi/index.php?a=share&m=index"
					+ "&tosite=" + $(".site option:selected").val() 
					+ "&url=" + encodeURIComponent($("input.url").val())
					+ "&desc=" + encodeURIComponent($("input.desc").val());
		$("input.img").each(function(){
			if($(this).val() != ""){
				url += "&img[]=" + encodeURIComponent($(this).val());
			}
		});
		$(this).attr("href", url);
		window.location.href = url;
	});
});