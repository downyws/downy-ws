$.fn.extend({
	placardScroll: function(){
		var items = $(".placard .items");
		var btns = $(".placard .btns");
		var count = btns.find("li").length;
		var width = items.find("li").css("width");
		var height = items.find("li").css("height");

		items.find("li").each(function(index){
			$(this).data("index", index).addClass("index_" + index);
			if(index != 0){
				$(this).find("img").css({"width": "0px", "height": height});
			}else{
				$(this).find("img").css({"height": height});
			}
		});
		btns.find("li").each(function(index){
			$(this).data("index", index).addClass("index_" + index)
				.click(function(){
					if(typeof(timerPoint) !== "undefined"){
						clearInterval(timerPoint);
					}
					trunPage($(this).data("index"));
			});
			if(index == 0){
				$(this).addClass("current");
			}
		});

		var trunPage = function(to){
			var from = btns.find(".current").data("index");
			if(from == to){
				return;
			}
			btns.find(".index_" + from).removeClass("current");
			btns.find(".index_" + to).addClass("current");

			items.find("li img").each(function(index){
				if(index != from && index != to){
					$(this).stop(true, true).css("width", "0px");
				}
			});
			items.find(".index_" + from + " img").animate({
				width: "0px"
			}, 500, function(){
				items.find(".index_" + to + " img").animate({
					width: width
				});
			});
		};
		var timerPoint = setInterval(function(){
			var index = parseInt(btns.find(".current").data("index")) + 1;
			index = (index >= count) ? 0 : index;
			trunPage(index);
		}, 5000);
	},
	photosScroll: function(){
		var items = $(".photos .items");
		var btns = $(".photos .btns");
		var count = btns.find("li").length;
		var height = items.find("li").css("height");
		height = -1 * height.substring(0, height.length - 2);

		items.find("li").each(function(index){
			$(this).data("index", index).addClass("index_" + index);
		});
		btns.find("li").each(function(index){
			$(this).data("index", index).addClass("index_" + index)
				.click(function(){
					trunPage($(this).data("index"));
			});
			if(index == 0){
				$(this).addClass("current");
			}
		});

		var trunPage = function(to){
			var from = btns.find(".current").data("index");
			btns.find(".index_" + from).removeClass("current");
			btns.find(".index_" + to).addClass("current");
			items.stop(true, false).animate({
				marginTop: (height * to) + "px"
			}, 200);
		};
	},

	pageShare: function(){
		$(this).each(function(){
			var href = SHARE_CONFIG["domain"] + "index.php?m=api&a=share&site=" + encodeURIComponent($(this).data("site"));
			href += "&title=" + encodeURIComponent(document.title);
			href += "&url=" + encodeURIComponent(window.location.href);
			href += "&img=" + encodeURIComponent(SHARE_CONFIG["img"]);
			$(this).attr("href", href);
		});
	}
});

$(function() {
	// 上焦点图
	$.fn.placardScroll();

	// 下焦点图
	$.fn.photosScroll();

	// 分享
	$(".content-below-left .share a").pageShare();

	// 提示
	$(".content-below-right .title ul li").tooltip({
		show: null,
		position: {my:"left top", at:"left bottom"},
		open:function(event, ui){
			ui.tooltip.animate({top:ui.tooltip.position().top + 5}, "fast");
		}
	});
});
