//html5 fixed
/*@cc_on(function(a,b){function r(a){var b=-1;while(++b<f)a.createElement(e[b])}if(!window.attachEvent||!b.createStyleSheet||!function(){var a=document.createElement("div");return a.innerHTML="<elem></elem>",a.childNodes.length!==1}())return;a.iepp=a.iepp||{};var c=a.iepp,d=c.html5elements||"abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|subline|summary|time|video",e=d.split("|"),f=e.length,g=new RegExp("(^|\\s)("+d+")","gi"),h=new RegExp("<(/*)("+d+")","gi"),i=/^\s*[\{\}]\s*$/,j=new RegExp("(^|[^\\n]*?\\s)("+d+")([^\\n]*)({[\\n\\w\\W]*?})","gi"),k=b.createDocumentFragment(),l=b.documentElement,m=b.getElementsByTagName("script")[0].parentNode,n=b.createElement("body"),o=b.createElement("style"),p=/print|all/,q;c.getCSS=function(a,b){try{if(a+""===undefined)return""}catch(d){return""}var e=-1,f=a.length,g,h=[];while(++e<f){g=a[e];if(g.disabled)continue;b=g.media||b,p.test(b)&&h.push(c.getCSS(g.imports,b),g.cssText),b="all"}return h.join("")},c.parseCSS=function(a){var b=[],c;while((c=j.exec(a))!=null)b.push(((i.exec(c[1])?"\n":c[1])+c[2]+c[3]).replace(g,"$1.iepp-$2")+c[4]);return b.join("\n")},c.writeHTML=function(){var a=-1;q=q||b.body;while(++a<f){var c=b.getElementsByTagName(e[a]),d=c.length,g=-1;while(++g<d)c[g].className.indexOf("iepp-")<0&&(c[g].className+=" iepp-"+e[a])}k.appendChild(q),l.appendChild(n),n.className=q.className,n.id=q.id,n.innerHTML=q.innerHTML.replace(h,"<$1font")},c._beforePrint=function(){if(c.disablePP)return;o.styleSheet.cssText=c.parseCSS(c.getCSS(b.styleSheets,"all")),c.writeHTML()},c.restoreHTML=function(){if(c.disablePP)return;n.swapNode(q)},c._afterPrint=function(){c.restoreHTML(),o.styleSheet.cssText=""},r(b),r(k);if(c.disablePP)return;m.insertBefore(o,m.firstChild),o.media="print",o.className="iepp-printshim",a.attachEvent("onbeforeprint",c._beforePrint),a.attachEvent("onafterprint",c._afterPrint)})(this,document)@*/

//General function
$.fn.extend({
	tab: function(tabs, pages, option){
		var i = 0, tabs = $(this).find(tabs).each(function(){
			$(this).data("tabs", i++);
		}), nTab = -1;
		var pages;

		if(option.fade){
			pages = $(this).find(pages).css('position', 'absolute');
		}else{
			pages = $(this).find(pages);
		}
		// 鼠标移入
		var stop = false;
		$(tabs).mouseenter(function(){stop = true});
		$(tabs).mouseleave(function(){stop = false});
		$(pages).mouseenter(function(){stop = true});
		$(pages).mouseleave(function(){stop = false});
		// 切换
		var completed = 1;

		var change = function(step){
			var i = 0;
			if(this != window){
				nTab = $(this).data("tabs");
			}else{
				if((nTab += step) == tabs.size()){
					nTab = 0;
				}
			}
			tabs.each(function(){
				if(i++ == nTab){
					$(this).addClass(option.css);
				}else{
					$(this).removeClass(option.css);
				}
			});

			if(!completed){
				return;
			}
			completed = 0;
			i = 0;
			pages.each(function(){
				if(i++ == nTab){
					if(option.fade){
						var nTabOld = nTab;
						$(this).fadeIn(option.fadeSpeed || 'slow', function (){
							completed = 1;
							if(nTabOld != nTab){
								change(0);
							}
						});
					}else{
						$(this).show();completed = 1;
					}
					if(option.callback){
						option.callback(this, tabs.get(i), i);
					}
				}else{
					if(option.fade){
						$(this).fadeOut(option.fadeSpeed || 'slow');
					}else{
						$(this).hide();
					}
				}
			});
		}
		tabs.mouseover(change);
		change(1);
		// 自动切换
		var auto = function(){
			clearTimeout(timer);
			!stop && change(1);
			timer = setTimeout(auto, option.speed);
		}
		if(option.speed){
			var timer = setTimeout(auto, option.speed);
		}

		return {
			select: function(tab){
				change.apply(tab);
			}
		}
	},

	createUrl: function(url, params){
		var split = (url.indexOf("?") >= 0) ? "&" : "?";
		var v = "";
		for(var k in params)
		{
			v = (typeof(params[k]) == "undefined") ? "" : (params[k] === null) ? '' : params[k];
			url += split + k + "=" + encodeURIComponent(v);
			split = "&";
		}
		return url;
	},

	regionSel: function(id, state, city, district){
		// 获取
		var search = function(id){
			var data = null;
			$.ajax({
				url: '/region/search',
				dataType: 'json',
				cache: false,
				async: false,
				data: {id: id},
				success: function(res){
					data = res;
				}
			});
			return data;
		}

		// 更新sel
		var refresh = function(obj, data, value){
			var html = '<option value="0">请选择</option>';
			var selected = '';
			for(var k in data){
				selected = (data[k]['id'] == value) ? 'selected="selected"' : '';
				html += '<option ' + selected + 'value="' + data[k]['id'] + '">' + data[k]['region_name'] + '</option>';
			}
			$(obj).html(html);
		}

		// 修改
		$(state).change(function(){
			var obj_id = $(this).val();
			if(obj_id > 0){
				var data = search(obj_id);
				refresh(city,  data['children'], 0);
			}
			$(district).html('<option value="0">请选择</option>');
		});
		$(city).change(function(){
			var obj_id = $(this).val();
			if(obj_id > 0){
				var data = search(obj_id);
				refresh(district,  data['children'], 0);
			}
		});

		// 初始化
		var data = search(id);
		switch(data['level']){
			case '4':
				data = search(data['parent_id']);
				refresh(district, data['children'], id);
				id = data['id'];
			case '3':
				data = search(data['parent_id']);
				refresh(city, data['children'], id);
				id = data['id'];
			case '2':
				data = search(data['parent_id']);
				refresh(state, data['children'], id);
		}
	}
});

/*$.fn.extend({
});*/
