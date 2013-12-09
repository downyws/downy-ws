$(function() {
	$.fn.goTop();
	$(window).scroll(function(){
		if($(window).scrollTop() > $(".content .aside").data("top")){
			$(".content .aside").children("ul").addClass("fixed");
		}else{
			$(".content .aside").children("ul").removeClass("fixed");
		}
	});
	$(".content .aside").data("top", $(".content .aside").offset().top - 20);
});

$.fn.extend({
	goTop: function(){
		var btn = $(".mod-go-top");

		var objFade = function(){
			if($(window).scrollTop() > 100){
				btn.fadeIn(300);
			}else{
				btn.fadeOut(300);
			}
		};
		var objPosition = function(){
			var width = $(document).width();
			if(width >= 1130){
				btn.css("right", ((width - 980 - 20 * 2) / 2 - 55) + "px");
			}else if(width >= 1090){
				btn.css("right", "0px");
			}else if(width >= 980){
				btn.css("right", ((width - 980) / 2) + "px");
			}else{
				btn.css("right", "0px");
			}
		};

		btn.mouseenter(function(){
			objPosition();
		}).click(function(){
			$("html, body").animate({scrollTop: 0}, 500);
		}).attr("href", "javascript:;");

		$(window).scroll(function(){
			objFade();
		});

		objPosition();
		objFade();
	}
});
