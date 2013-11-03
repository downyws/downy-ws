$(function(){
	// 初始化界面
	$("input.url").val(PARAMS.url);
	$("input.desc").val(PARAMS.desc);
	$("input.img").each(function(index){
		if(index + 1 <= PARAMS.img.length){
			if(index == 0){
				$("input.img_txt").val(PARAMS.img[index]);
			}
			$(this).val(PARAMS.img[index]);
			$(this).parent().removeClass("hide");
			$(this).parent().find("img").attr("src", PARAMS.img[index]);
		}else if(index == PARAMS.img.length){
			$(this).parent().removeClass("hide");
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
			$("a.btn").removeClass("disable");
		}else{
			$("a.btn").addClass("disable");
		}
	}
	btnable();

	// 绑定事件
	$("label.url").click(function(){
		$("input.url").focus().select();
	});
	$("input.url").change(btnable);
	$("label.desc").click(function(){
		$("input.desc").focus().select();
	});
	$("input.img").parent().click(function(){
		$(this).parent().find(".focus").removeClass("focus");
		$(this).addClass("focus");
		var url = $(this).find("input").val();
		$("input.img_txt").val(url).focus().select();
	});
	$("input.img_txt").blur(function(){
		var obj = $("input.img").parent().parent().find(".focus");
		var urlmatch = /^https?:\/\/([0-9a-z-]+\.)+[a-z]{2,4}\//;
		var img_url = $(this).val();
		var index = obj.data("index");
		if(img_url.indexOf("http://") != 0 && img_url.indexOf("https://") != 0){
			img_url = "http://" + img_url;
		}
		if(urlmatch.test(img_url)){
			$(this).val(img_url);
			obj.find("img").attr("src", $("input.img_txt").val());
			obj.find("input").val($("input.img_txt").val());
		}else{
			obj.find("img").attr("src", "images/share.jpg");
			obj.find("input").val("");
		}

		var imgs = new Array("", "", "", "", ""), i = 0, isfrist = true;
		$("input.img").each(function(){
			if($(this).val() != ""){
				imgs[i++] = $(this).val();
			}
		});
		i = 0;
		$("input.img").parent().each(function(){
			img_url = imgs[i++];
			if(img_url != ""){
				$(this).find("input").val(img_url);
				$(this).find("img").attr("src", img_url);
				$(this).removeClass("hide");
			}else{
				$(this).find("input").val("");
				$(this).find("img").attr("src", "images/share.jpg");
				$(this).addClass("hide");
				if(isfrist){
					isfrist = false;
					$(this).removeClass("hide");
				}
			}
			if($(this).hasClass("focus")){
				$("input.img_txt").val(img_url);
			}
		});
	});
	$("label.img").click(function(){
		$("input.img_txt").focus().select();
	});
	$(".site").change(function(){
		$(".below img").attr("src", $(this).find("option:selected").data("src"));
		btnable();
	});
	$("a.btn").click(function(){
		var url = "/index.php?a=share&m=index"
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