(function($) {

	var types = ['DOMMouseScroll', 'mousewheel'];

	if ($.event.fixHooks) {
		for ( var i=types.length; i; ) {
			$.event.fixHooks[ types[--i] ] = $.event.mouseHooks;
		}
	}

	$.event.special.mousewheel = {
		setup: function() {
			if ( this.addEventListener ) {
				for ( var i=types.length; i; ) {
					this.addEventListener( types[--i], handler, false );
				}
			} else {
				this.onmousewheel = handler;
			}
		},
		
		teardown: function() {
			if ( this.removeEventListener ) {
				for ( var i=types.length; i; ) {
					this.removeEventListener( types[--i], handler, false );
				}
			} else {
				this.onmousewheel = null;
			}
		}
	};

	$.fn.extend({
		mousewheel: function(fn) {
			return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
		},
		
		unmousewheel: function(fn) {
			return this.unbind("mousewheel", fn);
		}
	});


	function handler(event) {
		var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true, deltaX = 0, deltaY = 0;
		event = $.event.fix(orgEvent);
		event.type = "mousewheel";
		
		// Old school scrollwheel delta
		if ( orgEvent.wheelDelta ) { delta = orgEvent.wheelDelta/120; }
		if ( orgEvent.detail ) { delta = -orgEvent.detail/3; }
		
		// New school multidimensional scroll (touchpads) deltas
		deltaY = delta;
		
		// Gecko
		if ( orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
			deltaY = 0;
			deltaX = -1*delta;
		}
		
		// Webkit
		if ( orgEvent.wheelDeltaY !== undefined ) { deltaY = orgEvent.wheelDeltaY/120; }
		if ( orgEvent.wheelDeltaX !== undefined ) { deltaX = -1*orgEvent.wheelDeltaX/120; }
		
		// Add event and delta to the front of the arguments
		args.unshift(event, delta, deltaX, deltaY);
		
		return ($.event.dispatch || $.event.handle).apply(this, args);
	}

})(jQuery);

$.fn.extend({
	// 滚动条
	scrollBar: function (config){
		var divWrap = document.createElement('div'), divUp = document.createElement('div'), divDown = document.createElement('div'), divBlock = document.createElement('div');
		if(!config.direction){
			config.direction = 0;
		}
		var topLeft = config.direction ? 'left' : 'top';
		divWrap.appendChild(divUp);
		divWrap.appendChild(divDown);
		divWrap.appendChild(divBlock);
		$(divWrap).appendTo(this);
		$(divWrap).css({position: 'absolute'}).addClass('scrollbar' + (config.direction ? 'h' : 'v')).css(config.position);
		var width = config.direction ? $(divWrap).height() : $(divWrap).width(), range = (config.direction ? $(divWrap).width() : $(divWrap).height()) - width * 3;
		$(divUp).css({position: 'absolute', top: 0, left: 0, width: width, height: width}).addClass('scrollbar' + (config.direction ? 'h-left' : 'v-up'));
		$(divDown).css({position: 'absolute', width: width, height: width}).css(config.direction ? 'right' : 'bottom', 0).css(config.direction ? 'top' : 'left', 0).addClass('scrollbar' + (config.direction ? 'h-right' : 'v-down'));
		$(divBlock).css({position: 'absolute', width: width, height: width}).css(topLeft, width).css(config.direction ? 'top' : 'left', 0).addClass('scrollbar' + (config.direction ? 'h' : 'v') + '-block');

		if(typeof(config.step) != 'function'){
			config.step = function (direction){
				return 1 / range * direction;
			}
		}

		if(typeof(config.stepPage) != 'function'){
			config.stepPage = function (direction){
				return 10 / range * direction;
			}
		}

		var pos = 0;

		var getPos = function (){
			return pos;
			//return ($(divBlock).position()[topLeft] - width) / range;
		};

		var setPos = function (posNew, disableCallback){
			if(posNew < 0){
				posNew = 0;
			}

			if(posNew > 1){
				posNew = 1;
			}

			var posOld = pos;

			$(divBlock).css(topLeft, width + (pos = posNew) * range);

			if(typeof config.change == 'function' && !disableCallback){
				config.change(pos, posOld);
			}
		}
		
		var t1 = 0, t2 = 0;

		var clearT = function (){
			clearTimeout(t1);
			clearInterval(t2);
			$(divWrap).unbind('mousemove');
		};

		var setPosRepeat = function (diff){
			clearT();
			setPos(pos + diff());
			t1 = setTimeout(function (){
				t2 = setInterval(function (event){
					var offset = diff(event);
					if(offset === false){
						clearT();
						return;
					}
					setPos(pos + offset);
				}, 50);
			}, 200);
		};

		$(divUp).mousedown(function (){
			setPosRepeat(function (){
				return config.step(-1);
			});
			return false;
		}).mouseleave(clearT).mouseup(clearT);

		$(divDown).mousedown(function (){
			setPosRepeat(function (){
				return config.step(1);
			});
			return false;
		}).mouseleave(clearT).mouseup(clearT);

		$(divWrap).mousedown(function (event){
			var x = (config.direction ? event.pageX : event.pageY);
			var direction = x > $(divBlock).offset()[topLeft] ? 1 : -1;
			setPosRepeat(function (){
				if((x - (direction == 1 ? $(divBlock).offset()[topLeft] + width : $(divBlock).offset()[topLeft])) * direction <= 0){
					return false;
				}
				return config.stepPage(direction);
			});
			$(divWrap).mousemove(function (event){
				x = (config.direction ? event.pageX : event.pageY);
			});
			return false;
		}).mouseout(clearT).mouseup(clearT);

		$(divBlock).mousedown(function (event){
			var x = (config.direction ? event.pageX : event.pageY);
			var pos = getPos();
			var scrolling = function (event){
				setPos(pos + ((config.direction ? event.pageX : event.pageY) - x) / range);
			};
			var release = function (){
				$(document).unbind('mousemove', scrolling).unbind('mouseup', release);
			};

			$(document).mousemove(scrolling).mouseup(release);

			return false;
		});

		var move = function (posNew){
			$(divWrap).css(posNew);
			range = (config.direction ? $(divWrap).width() : $(divWrap).height()) - width * 3;
			setPos(pos, true);
		}

		var getWidth = function (){
			return width;
		}

		var show = function (){
			$(divWrap).show();
		}

		var hide = function (){
			$(divWrap).hide();
		}

		return {move: move, getPos: getPos, setPos: setPos, getWidth: getWidth, show: show, hide: hide};
	},
	/////////////////////////////////////////////////
	/*
		config = {
			struct: [],
			data: [],
			setCustom: '',
			getCustom: '',
			defaultCustom: '',
			dblclick: function (){},
			select: function (){},
			width: 50,
			
		}
	*/
	/////////////////////////////////////////////////
	dataGrid: function (config){
		var struct = config.struct, data = [], selected = -1, rowCount = 0, pos = 0, i;
		for(i = 0; i < config.data.length; i++){
			data[i] = {selected: false, values: config.data[i]};
		}
		var wrap = this, divHead = document.createElement('div'), tblHead = document.createElement('table'), divBody = document.createElement('div'), tblBody = document.createElement('table');
		if(!config.width){
			config.width = 50;
		}

		if($(wrap).css('position') != 'absolute'){
			$(wrap).css('position', 'relative');
		}

		$(wrap).bind('selectstart', function (){return false;});

		var custom = (function (){
			if(config.getCustom && (res = config.getCustom())){
				return config.getCustom();
			}

			if(config.defaultCustom && (res = config.defaultCustom())){
				return config.defaultCustom();
			}
			
			var res = {width: config.width, cols: []};
			for(i = 0; i < struct.length; i++){
				res.cols.push({field: struct[i].field, width: config.width});
			}
			return res;
		})();

		tmpStruct = {};
		for(i = 0; i < struct.length; i++){
			struct[i].order = i;
			tmpStruct[struct[i].field] = struct[i];
		}
		struct = tmpStruct;

		$(divHead).appendTo(wrap).css({width: '100%', overflow: 'hidden'});
		$(tblHead).appendTo(divHead);
		$(divBody).appendTo(wrap).css({width: '100%', overflow: 'hidden'});
		$(tblBody).appendTo(divBody);

		//表头同步滚动
		var scroll = function (){
			divHead.scrollLeft = divBody.scrollLeft;
		};
		$(divBody).scroll(scroll);

		var scrollBarV, scrollBarH;

		var change = function (posNew, posOld){
			var trs = tblBody.getElementsByTagName('tr');

			if(data.length - rowCount > 0){
				posOld = Math.floor(posOld * (data.length - rowCount + 1));
				for(i = posOld; i < posOld + rowCount && i < data.length; i++){
					trs[i].style.display = 'none';
				}

				posNew = Math.floor(posNew * (data.length - rowCount + 1));
				if(posNew > data.length - rowCount){
					posNew = data.length - rowCount;
				}
				for(i = posNew; i < posNew + rowCount && i < data.length; i++){
					trs[i].style.display = '';
					$(trs[i]).find('td div').each(function (){
						if(this.scrollWidth - this.offsetWidth > 0){
							this.title = this.innerHTML;
						}
					});
				}

				pos = posNew;
			}
		};

		scrollBarV = $(wrap).scrollBar({position: {top: $(tblBody).position().top, right: 0, height: $(tblBody).height()}, change: change, step: function (direction){
			return direction / (data.length - rowCount);
		},stepPage: function(direction){
			return direction * rowCount / (data.length - rowCount);
		}});
		scrollBarV.hide();

		scrollBarH = $(wrap).scrollBar({direction: 1, position: {bottom:0, left: 0, width: $(divBody).width()}, change: function (pos){
			divBody.scrollLeft = pos * (divBody.scrollWidth - divBody.clientWidth)
		}});
		var scrollBarHHeight = scrollBarH.getWidth();
		scrollBarH.hide();

		var refreshInit = function (){
			var html = '<tbody><tr><th style="border-bottom:0"><div>&nbsp;</div></th></tr></tbody>';
			$(tblHead).html(html);
			html = '<tbody><tr><td><div>&nbsp;</div></td></tr></tbody>';
			$(tblBody).html(html);
			rowCount = Math.floor(($(wrap).height() - $(tblHead).outerHeight() - $(tblBody).outerHeight() + $(tblBody).find('tr').outerHeight() - scrollBarHHeight) / $(tblBody).find('tr').outerHeight());
			$(wrap).height($(tblHead).outerHeight() + $(tblBody).outerHeight() - $(tblBody).find('tr').outerHeight() + scrollBarHHeight + $(tblBody).find('tr').outerHeight() * rowCount);
		};

		//刷新表头
		var refreshStruct = function (){
			var html = '';
			for(i = 0; i < custom.cols.length; i++){
				html += '<th data-field="' + custom.cols[i].field + '" style="border-bottom:0" onselectstart="return false;" unselectable="on"><div style="width:' + custom.cols[i].width + 'px">' + struct[custom.cols[i].field].title + '</div></th>';
			}
			$(tblHead).html('<thead><tr>' + html + '</tr></thead>');
			$(tblHead).find('th').click(function (){
				var asc = $(this).hasClass('asc');
				sort($(this).data("field"), asc);
				$(tblHead).find('th').removeClass('sort').removeClass('asc');
				$(this).addClass('sort').toggleClass('asc', !asc);
			}).mousedown(function (){
				return false;
			});
		};

		var getRowHtml = function (order, show){
			var html = '<tr' + (data[order].selected ? ' class="current"' : '') +  (show ? '' : ' style="display:none"') + '>', i;
			for(i = 0; i < custom.cols.length; i++){
				html += '<td><div style="width:' + custom.cols[i].width + 'px">' + data[order].values[struct[custom.cols[i].field].order] + '</div></td>';
			}
			html += '</tr>';

			if(typeof(config.each) == 'function'){
				html = config.each(html, data[order]);
			}

			return html;
		};

		//刷新内容
		var refresh = function (preserve){
			var html = '', scrollLeft, scrollTop;

			if(preserve){
				scrollLeft = divBody.scrollLeft;
			}

			for(j = 0; j < data.length; j++){
				html += getRowHtml(j);
			}
			$(tblBody).html('<tbody>' + html + '</tbody>').find('tr').mousedown(select).dblclick(dblclick);
			selected = -1;

			showHide();

			if(preserve){
				divBody.scrollLeft = scrollLeft;
			}

			if(data.length > rowCount){
				scrollBarV.move({top: $(tblBody).position().top, height: $(tblBody).outerHeight()});
				scrollBarV.show();

				$(divHead).css('width', $(wrap).width() - scrollBarV.getWidth());
				$(divBody).css('width', $(wrap).width() - scrollBarV.getWidth());
			}

			if(divBody.scrollWidth > $(wrap).width()){
				scrollBarH.move({bottom: 0, width: $(divBody).outerWidth()});
				scrollBarH.show();
			}

		};

		$(wrap).mousewheel(function (event,delta, deltaX, deltaY){
				scrollBarV.setPos((scrollBarV.getPos() * data.length - rowCount * deltaY) / data.length);
				return false;
		});

		$(window).resize(function (){
			$(divHead).css('width', $(wrap).width() - scrollBarV.getWidth());
			$(divBody).css('width', $(wrap).width() - scrollBarV.getWidth());
			scrollBarV.move({height: $(tblBody).outerHeight()});
			scrollBarH.move({width: $(divBody).outerWidth()});
		});

		var setPos = function (pos, absolute){
			if(data.length > rowCount){
				if(absolute){
					pos = pos / (data.length - rowCount + 1);
				}else{
					pos = (scrollBarV.getPos() * (data.length - rowCount + 1) + pos) / (data.length - rowCount + 1);
				}

				scrollBarV.setPos(pos);
			}
		}

		var clearSelected = function (){
			var trs = tblBody.getElementsByTagName('tr');
			for(i = 0; i < data.length; i++){
				data[i].selected = false;
				$(trs[i]).removeClass('current');
			}
		}

		var selectRow = function (order){
			var trs = tblBody.getElementsByTagName('tr');

			data[order].selected = true;
			$(trs[order]).addClass('current');
		}

		var focus = true;

		var enableKeys = function (){
			focus = true;
		}

		var disableKeys = function (){
			focus = false;
		}

		$(document).keypress(function (event){
			var handled = false;

			if(!focus){
				return;
			}

			if(event.target && (event.target.tagName.toLowerCase() == 'input' || event.target.tagName.toLowerCase() == 'select' || event.target.tagName.toLowerCase() == 'textarea')){
				return;
			}

			switch(event.keyCode){
			case 33:
				setPos(-rowCount);
				handled = true;
				break;
			case 34:
				setPos(rowCount);
				handled = true;
				break;
			case 38:
				if(selected >= 1){
					clearSelected();

					selected--;
					selectRow(selected);
					if(selected < pos || selected > pos + rowCount - 1){
						setPos(selected, true);
					}

					if(typeof config.select == 'function'){
						config.select();
					}
				}
				handled = true;
				break;
			case 40:
				if(selected >= 0 && selected < data.length - 1){
					clearSelected();

					selected++;
					selectRow(selected);
					if(selected < pos || selected > pos + rowCount - 1){
						setPos(selected, true);
					}

					if(typeof config.select == 'function'){
						config.select();
					}
				}
				handled = true;
				break;
			case 37:
				scrollBarH.setPos(scrollBarH.getPos() - 0.02);
				break;
			case 39:
				scrollBarH.setPos(scrollBarH.getPos() + 0.02);
				break;
			}

			if(handled){
				return false;
			}
		});

		var getSelected = function (){
			if(selected >= 0){
				return data[selected].values.slice(0);
			}
			
			return null;
		}

		var fetchSelected = function (){
			var i, res = [];
			for(i = 0; i < data.length; i++){
				if(data[i].selected){
					res.push(data[i].values.slice(0));
				}
			}

			return res;
		};

		var select = function (event){
			if(!config.select){
				return;
			}

			document.documentElement && document.documentElement.focus();
			document.body && document.body.focus();

			var res = true, i;

			var trs = tblBody.getElementsByTagName('tr');

			for(i = 0; i < trs.length; i++){
				if(trs[i] == this){
					break;
				}
			}

			if(i == trs.length){
				return false;
			}

			var order = i, lastOrder = selected;

			if(event.ctrlKey){
				data[order].selected = !(data[order].selected);
				res = false;
			}else{
				if(selected >= 0){
					if(selected == order){
						return false;
					}
				}

				for(i = 0; i < data.length; i++){
					data[i].selected = false;
				}

				data[order].selected = true;
				res = false;
			}

			if(event.shiftKey && lastOrder >= 0){
				if(order < lastOrder){
					for(i = order; i <= lastOrder; i++){
						data[i].selected = true;
					}
				}else{
					for(i = lastOrder; i < order; i++){
						data[i].selected = true;
					}
				}

				res = false;
			}else if(data[order].selected){
				selected = order;
			}else{
				selected = -1;
			}

			if(typeof config.select == 'function'){
				config.select();
			}

			showHide();

			return res;			
		};

		var dblclick = function (){
			if(typeof config.dblclick == 'function'){
				config.dblclick(this);
			}
			return false;
		};

		/////////
		/*
			update(field, rows), rows = {
				value: {},
				...
			}
		*/
		/////////
		var update = function (field, rows){
			var trs = tblBody.getElementsByTagName('tr'), i;

			for(i = 0; i < data.length; i++){
				if(typeof rows[data[i].values[struct[field].order]] != 'undefined'){
					data[i] = {selected: data[i].selected, values: rows[data[i].values[struct[field].order]]};
					$(trs[i]).replaceWith(getRowHtml(i));
				}
			}

			showHide();
		};

		/////////
		/*
			insert(field, rows), rows = {
				value: [{}...]
				...
			}
		*/
		/////////
		var insert = function (field, rows){
			var trs = tblBody.getElementsByTagName('tr'), i, j;

			for(i = 0; i < data.length; i++){
				if(typeof rows[data[i].values[struct[field].order]] != 'undefined'){
					var html = '';
					var insertRows = rows[data[i].values[struct[field].order]];
					for(j = 0; j < insertRows.length; j++){
						data.splice(i + j, 0, {selected: false, values: insertRows[j]});
						html += getRowHtml(i + j);
					}
					$(trs[i]).before(html);
					i += j;
				}
			}

			showHide();
		};

		var showHide = function (){
			var trs = tblBody.getElementsByTagName('tr');

			for(i = 0; i < trs.length; i++){
				if(i >= pos && i < pos + rowCount){
					trs[i].style.display = '';
					$(trs[i]).find('td div').each(function (){
						if(this.scrollWidth - this.offsetWidth > 0){
							this.title = this.innerHTML;
						}
					});
				}else{
					trs[i].style.display = 'none';
				}
				$(trs[i]).toggleClass('current', data[i].selected);
			}

			$(tblBody).find('tr').unbind('mousedown').unbind('dblclick').mousedown(select).dblclick(dblclick);
		};

		/////////
		/*
			remove(field)
		*/
		/////////
		var remove = function (field, rows){
			var trs = tblBody.getElementsByTagName('tr'), i;

			for(i = data.length - 1; i >= 0; i--){
				if(typeof rows[data[i].values[struct[field].order]] != 'undefined'){
					data.splice(i, 1);
					$(trs[i]).remove();
				}
			}

			showHide();
		};

		/////////
		/*
			replace(field, rows), rows = [{}...]
		*/
		/////////
		var replace = function (field, rows){
			var trs = tblBody.getElementsByTagName('tr'), i, j;

			for(i = 0; i < data.length; i++){
				if(typeof rows[data[i].values[struct[field].order]] != 'undefined'){
					var html = '';
					var insertRows = rows[data[i].values[struct[field].order]];
					data.splice(i, 1);
					for(j = 0; j < insertRows.length; j++){
						data.splice(i + j, 0, {selected: false, values: insertRows[j]});
						html += getRowHtml(i + j);
					}
					$(trs[i]).replaceWith(html);
					i += j;
				}
			}

			showHide();
		}

		var sort = function (field, desc){
			data.sort(function (a, b){
				if(a.values[struct[field].order] > b.values[struct[field].order]){
					return desc ? -1 : 1;
				}

				if(a.values[struct[field].order] < b.values[struct[field].order]){
					return desc ? 1 : -1;
				}

				return 0;
			});
			refresh();
		}

		refreshInit();
		refreshStruct();
		refresh();

		return {update: update, insert: insert, remove: remove, replace: replace, getSelected: getSelected, fetchSelected: fetchSelected, enableKeys: enableKeys, disableKeys: disableKeys};
	}
});
