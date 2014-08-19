$.fn.extend({
	// COOKIE获取
	cookieGet: function(key){
		var b = key + "=";
		if(document.cookie.length > 0){
			offset = document.cookie.indexOf(b);
			if(offset != -1){
				offset += b.length;
				end = document.cookie.indexOf(";", offset);
				if(end == -1){
					end = document.cookie.length;
				}
				return decodeURIComponent(document.cookie.substring(offset, end))
			}else{
				return "";
			}
		}
	},

	// COOKIE写入
	cookieSet: function(params){
		params.key = (typeof(params.key) == "undefined") ? "" : params.key;
		params.value = (typeof(params.value) == "undefined") ? "" : encodeURIComponent(params.value);
		params.time = (typeof(params.time) == "undefined") ? -1 : params.time;
		params.path = (typeof(params.path) == "undefined") ? "" : (";path=" + params.path);
		params.domain = (typeof(params.domain) == "undefined") ? "" : (";domain=" + params.domain);

		var e = new Date();
		e.setTime(e.getTime() + params.time);
		document.cookie = params.key + "=" + params.value + ";expires=" + e.toGMTString() + params.path + params.domain;
	},

	// 浏览器
	broswerName: function(){
		if(/firefox/gi.test(navigator.userAgent)){
			return "firefox";
		}else if(/AppleWebKit/gi.test(navigator.userAgent) && /theworld/gi.test(navigator.userAgent)){
			return "worldjs";
		}else if(/theworld/gi.test(navigator.userAgent)){
			return "world";
		}else if((/qqbrowser/gi.test(navigator.userAgent)) || (/chrome/gi.test(navigator.userAgent) && /qqbrowser/gi.test(navigator.userAgent))){
			return "qq";
		}else if(/360chrome/gi.test(navigator.userAgent) || (/360/gi.test(navigator.userAgent) && /qihu/gi.test(navigator.userAgent))){
			return "s360js";
		}else if(/se /gi.test(navigator.userAgent)){
			return "sogou";
		}else if(/chrome/gi.test(navigator.userAgent)){
			return "chrome";
		}else if(/opera/gi.test(navigator.userAgent)){
			return "opera";
		}else if(/360se/gi.test(navigator.userAgent)){
			return "s360";
		}else if(/greenbrowser/gi.test(navigator.userAgent)){
			return "green";
		}else if(/tencenttraveler/gi.test(navigator.userAgent)){
			return "tt";
		}else if(/maxthon/gi.test(navigator.userAgent)){
			return "maxthon";
		}else if(/safari/gi.test(navigator.userAgent)){
			return "safari";
		}else if(/krbrowser/gi.test(navigator.userAgent)){
			return "kr";
		}else{
			return "ie";
		}
	},

	// 密码强度检测
	assessPassword: function(val){
		var score = 0;
		if(val.length > 0){
			// 长度检测
			if(val.length >= 8){ score += 20; }
			else if(val.length >= 4){ score += 10; }
			// 大小写字母检测
			if(/[a-z]/.test(val) && /[A-Z]/.test(val)){ score += 20; }
			else if(/[a-z]/i.test(val)){ score += 10; }
			// 数字检测
			if(val.split(/[0-9]/g).length > 2){ score += 20; }
			else if(/[0-9]/.test(val)){ score += 10; }
			// 特殊字符检测
			if(val.split(/\W/).length > 2){ score += 20; }
			else if(/\W/.test(val)){ score += 10; }
			// 混合加分
			if(!/[0-9]/.test(val) || !/[a-z]/i.test(val)){ score += 0; }
			else if(!/\W/.test(val)){ score += 5; }
			else if(!/[a-z]/.test(val) || !/[A-Z]/.test(val)){ score += 10; }
			else{ score += 20; }
		}
		return score;
	},

	// 最大z-index
	dyMaxZindex: function(tag){
		var m = 0, z = 0;
		$("body").find(tag).each(function(){
			z = isNaN(z) ? 0 : $(this).css("z-index") * 1;
			if(m < z) m = z;
		});
		return m;
	},

	// 对话框
	dyDialog: function(option){
		// 参数格式化
		if(typeof(option.content) == "undefined") option.content = "";
		if(typeof(option.bgStyle) == "undefined") option.bgStyle = {};
		if(typeof(option.locate.type) == "undefined") option.locate.type = "window";
		if(typeof(option.locate.target) == "undefined") option.locate.target = {};
		if(typeof(option.locate.target.object) == "undefined") option.locate.target.object = false;
		if(typeof(option.locate.target.origin) == "undefined") option.locate.target.origin = "lt";
		if(typeof(option.locate.x) == "undefined") option.locate.x = 0;
		if(typeof(option.locate.y) == "undefined") option.locate.y = 0;
		if(typeof(option.locate.origin) == "undefined") option.locate.origin = "lt";
		if(typeof(option.onEvent) == "undefined") option.onEvent = {};
		if(typeof(option.onEvent.open) == "undefined") option.onEvent.open = function(){};
		if(typeof(option.onEvent.close) == "undefined") option.onEvent.close = function(){};
		if(typeof(option.onEvent.resize) == "undefined") option.onEvent.resize = function(){};
		if(typeof(option.close.bgBtn) == "undefined") option.close.bgBtn = false;
		if(typeof(option.close.className) == "undefined") option.close.className = false;

		// 事件
		var H_Close = function(){
			// 解绑
			$(window).unbind("resize", H_WindowResize);
			option.close.bgBtn && $("#" + id + "-bg").unbind("click", H_Close);
			option.close.className && $("#" + id + "-ft ." + option.close.className).unbind("click", H_Close);
			// 销毁对象
			$("#" + id + "-bg, #" + id + "-ft").fadeOut(300, function(){
				$("#" + id).remove();
			});
			// 关闭回调
			option.onEvent.close();
		}
		var H_WindowResize = function(){
			$("#" + id + "-bg").css({"width": "0px", "height": "0px"});
			$("#" + id + "-ft").css({"top": 0, "left": 0});

			if(option.locate.type == "window"){
				F_LocateWindow();
			}else if(option.locate.type == "page"){
				F_LocatePage();
			}else if(option.locate.type == "tag"){
				F_LocateTag();
			}
			var w = $(document).width(), h = $(document).height();
			$("#" + id + "-bg").css({"width": w + "px", "height": h + "px"});

			option.onEvent.resize();
		};
		// 窗口类型定位
		var F_LocateWindow = function(){
			var ft_w = $("#" + id + "-ft").innerWidth(), ft_h = $("#" + id + "-ft").innerHeight();
			var w_w = $(window).width(), w_h = $(window).height();
			var x = 0, y = 0;

			if(c_x == "left") x = 0;
			else if(c_x == "middle") x = w_w / 2;
			else if(c_x == "right") x = w_w;
			else x = c_x;

			if(c_y == "top") y = 0;
			else if(c_y == "middle") y = w_h / 2;
			else if(c_y == "bottom") y = w_h;
			else y = c_y;

			var xy = F_Offset(x, y, c_o, ft_w, ft_h);
			$("#" + id + "-ft").css("left", xy.x);
			$("#" + id + "-ft").css("top", xy.y);
		};
		// 页面类型定位
		var F_LocatePage = function(){
			var ft_w = $("#" + id + "-ft").innerWidth(), ft_h = $("#" + id + "-ft").innerHeight();
			var d_w = $(document).width(), d_h = $(document).height();
			var x = 0, y = 0;

			if(c_x == "left") x = 0;
			else if(c_x == "middle") x = d_w / 2;
			else if(c_x == "right") x = d_w;
			else x = c_x;

			if(c_y == "top") y = 0;
			else if(c_y == "middle") y = d_h / 2;
			else if(c_y == "bottom") y = d_h;
			else y = c_y;

			var xy = F_Offset(x, y, c_o, ft_w, ft_h);
			$("#" + id + "-ft").css("left", xy.x);
			$("#" + id + "-ft").css("top", xy.y);
		};
		// 元素类型定位
		var F_LocateTag = function(){
			var ft_w = $("#" + id + "-ft").innerWidth(), ft_h = $("#" + id + "-ft").innerHeight();
			var t_w = option.locate.target.object.innerWidth(), t_h = option.locate.target.object.innerHeight();
			var t_x = option.locate.target.object.offset().left, t_y = option.locate.target.object.offset().top;
			var x = 0, y = 0;

			if(c_x == "left") x = t_x;
			else if(c_x == "middle") x = t_x + t_w / 2;
			else if(c_x == "right") x = t_x + t_w;
			else switch(option.locate.target.origin){
				case "mt": case "mm": case "mb": x = t_x + t_w / 2 + c_x; break;
				case "rt": case "rm": case "rb": x = t_x + t_w + c_x; break;
				case "lt": case "lm": case "lb": default: x = t_x + c_x; break;
			}

			if(c_y == "top") y = t_y;
			else if(c_y == "middle") y = t_y + t_h / 2;
			else if(c_y == "bottom") y = t_y + t_h;
			else switch(option.locate.target.origin){
				case "lm": case "mm": case "rm": y = t_y + t_h / 2 + c_y; break;
				case "lb": case "mb": case "rb": y = t_y + t_h + c_y; break;
				case "lt": case "mt": case "rt": default: y = t_y + c_y; break;
			}

			var xy = F_Offset(x, y, c_o, ft_w, ft_h);
			$("#" + id + "-ft").css("left", xy.x);
			$("#" + id + "-ft").css("top", xy.y);
		};
		// 对齐点偏移
		var F_Offset = function(left, top, origin, width, height){
			switch(origin){
				case "lm": return {x: left, y: top - height / 2}; break;
				case "lb": return {x: left, y: top - height}; break;
				case "mt": return {x: left - width / 2, y: top}; break;
				case "mm": return {x: left - width / 2, y: top - height / 2}; break;
				case "mb": return {x: left - width / 2, y: top - height}; break;
				case "rt": return {x: left - width, y: top}; break;
				case "rm": return {x: left - width, y: top - height / 2}; break;
				case "rb": return {x: left - width, y: top - height}; break;
				case "lt": default: return {x: left, y: top}; break;
			}
		};

		// 创建对象
		var z_index = $.fn.dyMaxZindex("*");
		var id = "dy-dialog-" + parseInt(Math.random() * 1000);
		$("body").append("<div id='" + id + "'><div id='" + id + "-ft'>" + option.content + "</div><div id='" + id + "-bg'></div></div>");
		$("#" + id + "-bg").css({"top":"0", "left":"0", "position":"absolute", "z-index":(z_index + 1), "background-color":"#DDDDDD", "opacity":"0.6"});
		$("#" + id + "-bg").css(option.bgStyle);

		// 定位
		var c_x = option.locate.x, c_y = option.locate.y, c_o = option.locate.origin;
		c_x = isNaN(c_x) ? c_x : (c_x * 1);
		c_y = isNaN(c_y) ? c_y : (c_y * 1);
		$("body").append();
		if(option.locate.type == "window"){
			$("#" + id + "-ft").css({"position":"fixed", "z-index":(z_index + 2)});
		}else if(option.locate.type == "page"){
			$("#" + id + "-ft").css({"position":"absolute", "z-index":(z_index + 2)});
		}else if(option.locate.type == "tag"){
			$("#" + id + "-ft").css({"position":"absolute", "z-index":(z_index + 2)});
		}

		// 绑定
		option.close.bgBtn && $("#" + id + "-bg").bind("click", H_Close);
		option.close.className && $("#" + id + "-ft ." + option.close.className).bind("click", H_Close);
		$(window).bind("resize", H_WindowResize);

		// 展现
		H_WindowResize();
		$("#" + id + "-bg, #" + id + "-ft").css("display", "none").fadeIn(300);
		// 回调
		option.onEvent.open();
	},

	// 文件上传
	dyUploadFile: function(file, option){
		var xhr = new XMLHttpRequest();
		xhr.open("POST", option.url);

		xhr.upload.onprogress = option.onEvent.progress;
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				if(option.dataType.toUpperCase() == "JSON"){
					try{
						var r = eval("(" + xhr.responseText + ")");
						option.onEvent.success(r);
					}catch(e){
						option.onEvent.error(e);
					}
				}
			}
		};

		var formData = new FormData();
		formData.append(option.name, file);
		xhr.send(formData);
	},

	// 创建URL
	createUrl: function (url, params){
		var split = (url.indexOf("?") >= 0) ? "&" : "?";
		var v = "";
		for(var k in params)
		{
			v = (typeof(params[k]) == "undefined") ? "" : (params[k] === null) ? "" : params[k];
			url += split + k + "=" + encodeURIComponent(v);
			split = "&";
		}
		return Ext.organic.Config.baseUrl + url;
	},

	// HTML标签过滤
	filterHTMLTag: function (html){
		// style 和 script 全部删除
		html = html.replace(/<style.*?>.*?<\/style>/ig, "");
		html = html.replace(/<script.*?>.*?<\/script>/ig, "");
		// <xx>标签删除
		html = html.replace(/<\/?[^>]*>/ig, "");
		// 替换 < >
		html = html.replace(/</ig, "&lt;");
		html = html.replace(/>/ig, "&gt;");
		//去除行尾空白
		html = html.replace(/[ | ]*\n/ig, "\n");
		//去除多余空行
		html = html.replace(/\n[\s| | ]*\r/ig, "\n");
		return html;
	},

	// 内容缩略
	getSortContent: function (title, brief){
		var content = "";
		if(title.length > 20){
			content = title.substr(0, 10) + "..." + title.substr(-5);
		}else if(title.length > 0){
			content = title;
		}else if(brief.length > 20){
			content = brief.substr(0, 10) + "..." + brief.substr(-5);
		}else if(brief.length > 0){
			brief = title;
		}
		return content;
	},

	// 时间戳转换
	convertTimestamp: function(timestamp, format){
		var timestamp = new Date(timestamp * 1000);
		timestamp.toLocaleString();
		// 格式化
		var o = {
			"M+": timestamp.getMonth() + 1,
			"d+": timestamp.getDate(),
			"h+": timestamp.getHours(),
			"m+": timestamp.getMinutes(),
			"s+": timestamp.getSeconds(),
			"q+": Math.floor((timestamp.getMonth() + 3) / 3),
			"S": timestamp.getMilliseconds()
		}
		if(/(y+)/.test(format)) {
			format = format.replace(RegExp.$1, (timestamp.getFullYear() + "").substr(4 - RegExp.$1.length));
		}
		for(var k in o) {
			if(new RegExp("(" + k + ")").test(format)) {
				format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
			}
		}
		// 返回
		return format;
	},

	// 强制转字符串
	converToString: function(value){
		var result = '';

		switch(typeof(value)){
			case 'string':
			case 'number':
				result = value;
				break;
			case 'object':
				for(key in value){
					if(typeof(value[key]) == 'string'){
						result += '[' + key + ': ' + value[key] + ']';
					}else{
						result += '[' + key + ': ' + $.fn.converToString(value[key]) + ']';
					}
				}
				break;
			case 'function':
			default:
				result = 'converToString未实现[' + typeof(value) + ']类型。';
				break;
		}

		return result;
	}
});
