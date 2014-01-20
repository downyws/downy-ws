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
		$.ajax({type: "POST", url: "/index.php?a=index&m=logs&t=ajax", data: {all: 1}, async: false, dataType: "JSON", success: function(response){
			if(typeof(response.error) != "undefined"){
				$(".content .timeline").html("<div class='error'>" + response.error.msg + "</div>");
			}else if(typeof(response.years) != "undefined"){
				// 生成界面
				var logs = response.logs, html = {timeline: "", aside: ""};
				html.timeline += "<div class='blockfuture'>We tied the knot in 2014!</div>";// coding
				html.aside += "<ul>";

				var stamp = {now: {y: 0, m: 0}}, log = null;
				for(var i = 0; i < logs.length; i++)
				{
					log = logs[i];

					if(stamp.y != log.y)
					{
						stamp.y = log.y;
						html.timeline += "<div class='blockyear'><div class='clearfix' id='timeline-" + log.y + "'><span class='year'>" + log.y + "年</span><span class='arr'></span></div></div>";
						html.aside += "<li class='year'><a class='timeline-jump' href='javascript:;' id='timeline-btn-" + log.y + "'>" + log.y + "年</a><ul class='month'>";
					}
					if(stamp.m != log.m)
					{
						stamp.m = log.m;
						html.timeline += "<div id='timeline-" + log.y + "-" + log.m + "'>";
						html.aside += "<li><a class='timeline-jump' href='javascript:;' id='timeline-btn-" + log.y + "-" + log.m + "'>" + log.m + "月</a></li>";
					}

					html.timeline += "<div class='blockmonth clearfix'><div class='day'>" + log.m + "月" + log.d + "日</div>";
					html.timeline += "<div class='notes " + log.type + "'><div class='arrow-left " + log.type + "'></div><div class='cnt'>";
					html.timeline += log['content'];
					html.timeline += "</div><div class='arrow-right'></div></div></div>";

					if(typeof(logs[i+1]) == "undefined" || logs[i+1].m != stamp.m)
					{
						html.timeline += "</div>";
					}
					if(typeof(logs[i+1]) == "undefined" || logs[i+1].y != stamp.y)
					{
						html.aside += "</ul></li>";
					}
				}
				
				html.timeline += "<div class='blockstart'>We start punished in 2010!</div>";// coding
				html.aside += "</ul>";
				$(".content .timeline").html(html.timeline);
				$(".content .aside").html(html.aside);

				// 标记位置
				var ANCHORS = [];
				var top = $(".content .aside").offset().top - 20;
				$(".content .aside").data("top", top);
				var stamp = {now: {y: 0, m: 0}}, log = null;
				for(var i = 0; i < logs.length; i++)
				{
					log = logs[i];

					if(stamp.y != log.y)
					{
						stamp.y = log.y;
						top = $("#timeline-" + log.y).offset().top;
						$("#timeline-btn-" + log.y).data("top", top);
						ANCHORS.push(["#timeline-btn-" + log.y, 'y', top]);
					}
					if(stamp.m != log.m)
					{
						stamp.m = log.m;
						top = $("#timeline-" + log.y + "-" + log.m).offset().top;
						$("#timeline-btn-" + log.y + "-" + log.m).data("top", top);
						ANCHORS.push(["#timeline-btn-" + log.y + "-" + log.m, 'm', top]);
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
