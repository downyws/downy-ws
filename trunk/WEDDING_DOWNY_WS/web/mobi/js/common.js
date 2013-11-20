$(function(){
	// ÏÂ½¹µãÍ¼
	$.fn.photosScroll();
});

$.fn.extend({
	photosScroll: function(){
		var items = $(".photos .items ul");
		var count = items.find("li").length;
		var height = items.parent().css("height");
		height = -1 * height.substring(0, height.length - 2);

		items.find("li").each(function(index){
			$(this).data("index", index).addClass("index_" + index);
		});

		var trunPage = function(to){
			var from = items.find(".current").data("index");
			items.find(".index_" + from).removeClass("current");
			items.find(".index_" + to).addClass("current");
			items.stop(true, false).animate({
				marginTop: (height * to) + "px"
			}, 200);
		};
		var timerPoint = setInterval(function(){
			var index = parseInt(items.find(".current").data("index")) + 1;
			index = (index >= count) ? 0 : index;
			trunPage(index);
		}, 5000);
	}
});