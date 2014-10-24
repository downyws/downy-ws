$(function(){
	// 下拉框
	$(".dy_select").each(function(){
		$(this).dySelect({name: $(this).attr("name"), text: $(this).data("text"), type: $(this).data("type"), width: $(this).data("width"), height: $(this).data("height"), defval: $(this).data("defval")});
	});
});

$.fn.extend({
	// DATA TABLE
	dyDataTab: function(options){
		// 参数格式化
		if(typeof(options.obj) == "undefined") return false;
		if(typeof(options.url) == "undefined") return false;
		if(typeof(options.fields) == "undefined") return false;
		if(typeof(options.defSort) == "undefined") options.defSort = {field: "", type: ""};
		if(typeof(options.onEvent) == "undefined") options.onEvent = {};
		if(typeof(options.onEvent.formatData) == "undefined") options.onEvent.formatData = function(datas){return datas;};
		if(typeof(options.onEvent.clickItem) == "undefined") options.onEvent.clickItem = function(obj){};

		// 创建对象
		var table = "", count = 0, sort = "";
		for(var key in options.fields){
			count++;
			if(options.fields[key][1]){
				if(options.defSort.field == key){
					sort = (options.defSort.type == "ASC" ? "↑" : "↓");
					table += "<td class='sort' data-field='" + key + "'><span>" + options.fields[key][0] + "<font>" + sort + "</font></span></td>";
				}else{
					table += "<td class='sort' data-field='" + key + "'><span>" + options.fields[key][0] + "<font>　</font></span></td>";
				}
			}else{
				table += "<td data-field='" + key + "'>" + options.fields[key][0] + "</td>";
			}
		}
		table = "<table>" + 
					"<thead><tr>" + table + "</tr></thead>" + 
					"<tbody class='items'></tbody>" +
					"<tbody class='loadbar'><tr><td data-count='0' data-amount='-1' colspan='" + count + "'></td></tr></tbody>" +
				"</table>";
		options.obj.find(".list_main").append(table);

		// 定义对象
		var OBJ = {};
		if(options.obj.find(".toolbar").length){
			OBJ.TOOLBAR = options.obj.find(".toolbar");
			if(options.obj.find(".toolbar .search").length){
				OBJ.TOOLBAR.SEARCH = OBJ.TOOLBAR.find(".search");
			}
		}
		OBJ.LIST = options.obj.find(".list_main");
		OBJ.LIST.HEADER = OBJ.LIST.find("thead");
		OBJ.LIST.HEADER.data("sort", options.defSort);
		OBJ.LIST.ITEMS = OBJ.LIST.find(".items");
		OBJ.LIST.LOADBAR = OBJ.LIST.find(".loadbar td");
		OBJ.DETAIL = options.obj.find(".detail_main");

		var _W_DETAIL = OBJ.DETAIL.outerWidth();

		// 事件
		var H_WindowResize = function(){
			var h = $(window).height();
			if(typeof(OBJ.TOOLBAR) != "undefined"){
				h -= OBJ.TOOLBAR.outerHeight();
			}
			OBJ.DETAIL.css({"height": h, "width": _W_DETAIL});
			OBJ.LIST.css("height", h);

			var w = $(window).width();
			var w_detail = OBJ.DETAIL.width();
			OBJ.LIST.css("width", (w - _W_DETAIL > 0 ? (w - _W_DETAIL) : 0));
		};
		var H_Search = function(event){
			OBJ.TOOLBAR.SEARCH.data("search", OBJ.TOOLBAR.SEARCH.find("input[name=search]").val());

			F_RefreshItems();
		};
		var H_Sort = function(event){
			var field = $(this).parent().data("field");
			OBJ.LIST.HEADER.find("td").each(function(){
				$(this).find("span").attr("alt", "").find("font").html("　");
			});

			if(OBJ.LIST.HEADER.data("sort").field != field || OBJ.LIST.HEADER.data("sort").type == ""){
				OBJ.LIST.HEADER.data("sort", {"field": field, "type": "ASC"});
				$(this).attr("alt", "low to up").find("font").html("↑");
			}else if(OBJ.LIST.HEADER.data("sort").type == "ASC"){
				OBJ.LIST.HEADER.data("sort", {"field": field, "type": "DESC"});
				$(this).attr("alt", "up to low").find("font").html("↓");
			}else{
				OBJ.LIST.HEADER.data("sort", {"field": "", "type": ""});
			}

			F_RefreshItems();
		};

		// 加载数据
		var F_LoadBarTips = function(type, message){
			OBJ.LIST.LOADBAR.html("<div class='" + type + "'></div>");
			OBJ.LIST.LOADBAR.find("div").text(message);
		};
		var F_RefreshItems = function(){
			OBJ.LIST.LOADBAR.data("count", 0).data("amount", -1);
			OBJ.LIST.ITEMS.html("");
			F_AjaxLoad();
		};
		var F_AjaxLoad = function(){

			if(OBJ.LIST.LOADBAR.data("amount") >= 0 && OBJ.LIST.LOADBAR.data("count") >= OBJ.LIST.LOADBAR.data("amount")){
				return;
			}

			F_LoadBarTips("tips", "loading...");

			var data = {};
			data.index = OBJ.LIST.LOADBAR.data("count") + 1;
			if(typeof(OBJ.TOOLBAR) != "undefined"){
				if(typeof(OBJ.TOOLBAR.SEARCH) != "undefined"){
					OBJ.TOOLBAR.SEARCH.find("input[name=search]").val(OBJ.TOOLBAR.SEARCH.data("search"));
				}
				OBJ.TOOLBAR.find("input").each(function(){
					data[$(this).attr("name")] = $(this).val();
				});
			}
			if(OBJ.LIST.HEADER.data("sort").type != ""){
				data.sort_field = OBJ.LIST.HEADER.data("sort").field;
				data.sort_type = OBJ.LIST.HEADER.data("sort").type;
			}

			$.ajax({type: "POST", url: options.url,	data: data, dataType: "JSON", async: false, success: function(response){
				if(typeof(response.message) != "undefined"){
					F_LoadBarTips("error", "错误信息：" + response.message);
				}else{
					response.datas = options.onEvent.formatData(response.datas);
					var html_td = "", temp = null;
					for(var key in options.fields){
						html_td += "<td class='_f_" + key + "' data-field='" + key + "'></td>";
					}
					for(key in response.datas){
						OBJ.LIST.ITEMS.append("<tr class='_i_" + response.datas[key].id + "' data-id='" + response.datas[key].id + "'>" + html_td + "</tr>");
						OBJ.LIST.ITEMS.find("._i_" + response.datas[key].id + " td").each(function(){
							$(this).text(response.datas[key][$(this).data("field")]);
						});
					}

					OBJ.LIST.LOADBAR.data("amount", response.amount);
					OBJ.LIST.LOADBAR.data("count", OBJ.LIST.ITEMS.find("tr").length);
					if(OBJ.LIST.LOADBAR.data("count") >= OBJ.LIST.LOADBAR.data("amount")){
						F_LoadBarTips("tips", "no more  data ╮(╯▽╰)╭");
					}else{
						OBJ.LIST.LOADBAR.html("load more");
						F_LoadBarTips("button", "load more");
					}
				}
			}, error: function(jqXHR, textStatus, errorThrown){
				F_LoadBarTips("error", "错误信息：" + JSON.stringify(jqXHR));
			}});
		};

		// 绑定
		$(window).bind("resize", H_WindowResize);
		OBJ.LIST.HEADER.find("span").bind("click", H_Sort);
		if(typeof(OBJ.TOOLBAR) != "undefined"){
			OBJ.TOOLBAR.find("input[type='hidden']").bind("change", F_RefreshItems);
			if(typeof(OBJ.TOOLBAR.SEARCH) != "undefined"){
				OBJ.TOOLBAR.SEARCH.find("button").bind("click", H_Search);
				OBJ.TOOLBAR.SEARCH.find("input[type=text]").bind("keydown", function(event){
					if(event.keyCode == "13"){
						$(this).parent().find("button").click();
					}
				});
			}
		}
		OBJ.LIST.LOADBAR.bind("click", F_AjaxLoad);
		OBJ.LIST.ITEMS.on("click", "tr", function(){
			options.onEvent.clickItem($(this));
		});
		// 展现
		H_WindowResize();
		F_AjaxLoad();
	},
	// 下拉框
	dySelect: function(option){
		// 获取配置
		var id = "dy-select-" + parseInt(Math.random() * 1000);

		// 创建对象
		var html = '<div id="' + id + '" class="dy-select"><input type="hidden" name="' + option.name + '" />';
		html += '	<div class="caption" style="width:' + (option.width - 24) + 'px">';
		html += '		<div class="text">' + option.text + '</div><div class="dropdown">&nbsp;</div>';
		html += '	</div>';
		html += '	<div class="list" tabindex="0" style="height:' + option.height + 'px;width:' + option.width + 'px;">';
		$(this).find("option").each(function(){
			html += '<div class="item" data-value="' + $(this).val() + '">' + $(this).html() + '</div>';
		});
		html += '	</div>';
		html += '</div>';
		$(this).after(html);
		if(typeof(option.defval) != "undefined"){
			$("#" + id + " input[type=hidden]").val(option.defval);
			$(this).find("option").each(function(){
				if(option.defval == $(this).val()){
					$("#" + id + " .caption .text").html($(this).html());
				}
			});
		}
		$(this).remove();

		// 绑定事件
		if(option.type == "jump"){
			$("#" + id + ".dy-select .list .item").click(function(){
				window.location.href = $(this).data("value");
			});
		}else{
			$("#" + id + ".dy-select .list .item").click(function(){
				$(this).parent().parent().find("input").val($(this).data("value")).trigger("change");
				$(this).parent().parent().find(".caption .text").html($(this).html());
				$(this).parent().css("display", "none");
			});
		}
		$("#" + id + ".dy-select .caption").click(function(){
			var obj = $(this).parent().find(".list");
			obj.css("display", "block");
			obj.focus();
			return false;
		});
		$("#" + id + ".dy-select .list").blur(function(){
			$(this).css("display", "none");
		});
	}
});
