$(function() {
	$.fn.goTop();
	$.fn.drawTimeLine();
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
	},
	drawTimeLine: function(){
		$.ajax({type: "POST", url: "/index.php?a=index&m=dates&t=ajax", data: {all: 1}, async: false, dataType: "JSON", success: function(response){
			if(typeof(response.error) != "undefined"){
				$(".content .timeline").html("<div class='error'>" + response.error.msg + "</div>");
			}else if(typeof(response.years) != "undefined"){
				// 生成界面
				var dates = response.dates, html = {timeline: "", aside: ""};
				html.timeline += "<div class='blockwedding'>We tied the knot in 2014!</div>";
				html.aside += "<ul>";

				var stamp = {now: {y: 0, m: 0}}, date = null;
				for(var i = 0; i < dates.length; i++)
				{
					date = dates[i];

					if(stamp.y != date.y)
					{
						stamp.y = date.y;
						html.timeline += "<div class='blockyear'><div class='clearfix' id='timeline-" + date.y + "'><span class='year'>" + date.y + "年</span><span class='arr'></span></div></div>";
						html.aside += "<li class='year'><a class='timeline-jump' href='javascript:;' id='timeline-btn-" + date.y + "'>" + date.y + "年</a><ul class='month'>";
					}
					if(stamp.m != date.m)
					{
						stamp.m = date.m;
						html.timeline += "<div id='timeline-" + date.y + "-" + date.m + "'>";
						html.aside += "<li><a class='timeline-jump' href='javascript:;' id='timeline-btn-" + date.y + "-" + date.m + "'>" + date.m + "月</a></li>";
					}

					html.timeline += "<div class='blockmonth clearfix'><div class='day'>" + date.m + "月" + date.d + "日</div>";
					html.timeline += "<div class='notes'><div class='arrow-left'></div><div class='cnt'>";
					html.timeline += date['location'];
					html.timeline += "</div><div class='arrow-right'></div></div></div>";

					if(typeof(dates[i+1]) == "undefined" || dates[i+1].m != stamp.m)
					{
						html.timeline += "</div>";
					}
					if(typeof(dates[i+1]) == "undefined" || dates[i+1].y != stamp.y)
					{
						html.aside += "</ul></li>";
					}
				}
				
				html.timeline += "<div class='blockmeet'>We met in 2010!</div>";
				html.aside += "</ul>";
				$(".content .timeline").html(html.timeline);
				$(".content .aside").html(html.aside);

				// 标记位置
				var ANCHORS = [];
				var top = $(".content .aside").offset().top - 20;
				$(".content .aside").data("top", top);
				var stamp = {now: {y: 0, m: 0}}, date = null;
				for(var i = 0; i < dates.length; i++)
				{
					date = dates[i];

					if(stamp.y != date.y)
					{
						stamp.y = date.y;
						top = $("#timeline-" + date.y).offset().top;
						$("#timeline-btn-" + date.y).data("top", top);
						ANCHORS.push(["#timeline-btn-" + date.y, 'y', top]);
					}
					if(stamp.m != date.m)
					{
						stamp.m = date.m;
						top = $("#timeline-" + date.y + "-" + date.m).offset().top;
						$("#timeline-btn-" + date.y + "-" + date.m).data("top", top);
						ANCHORS.push(["#timeline-btn-" + date.y + "-" + date.m, 'm', top]);
					}
				}

				// 绑定事件
				$(".content .aside .timeline-jump").click(function(){
					$("html, body").animate({scrollTop: $(this).data("top")}, 500);
				});
				$(window).bind("resize scroll", function(){
					var scrollTop = $(window).scrollTop();
					if(scrollTop > $(".content .aside").data("top")){
						$(".content .aside").children("ul").addClass("fixed");
					}else{
						$(".content .aside").children("ul").removeClass("fixed");
					}

					var prev = null;
					$(".aside .current").removeClass("current");
					for(var a in ANCHORS){
						if(ANCHORS[a][2] > scrollTop){
							if(prev == null){
								prev = ANCHORS[a];
							}
							if(prev[1] == 'm'){
								$(prev[0]).parent().parent().parent().addClass("current");
							}
							$(prev[0]).parent().addClass("current");
							break;
						}
						prev = ANCHORS[a];
					}
				});
			}else{
				$(".content .timeline").html("<div class='error'>Ajax response data error.</div>");
			}
		}, error: function(){
			$(".content .timeline").html("<div class='error'>Ajax response error.</div>");
		}});
	}
});
