$(function() {
	$.fn.goTop();
});

$.fn.extend({
	goTop: function(){
		var btn = $('.mod-go-top');

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
				btn.css('right', ((width - 980 - 20 * 2) / 2 - 55) + 'px');
			}else if(width >= 1090){
				btn.css('right', '0px');
			}else if(width >= 980){
				btn.css('right', ((width - 980) / 2) + 'px');
			}else{
				btn.css('right', '0px');
			}
		};

		btn.mouseenter(function(){
			objPosition();
		}).click(function(){
			$('html, body').animate({scrollTop: 0}, 500);
		}).attr('href', 'javascript:;');

		$(window).scroll(function(){
			objFade();
		});

		objPosition();
		objFade();
	},

	fullSel: function(obj, list, defval, callback){
		var html = '<option class="lv0" value="0">请选择</option>';
		var selval = null;
		for(i = 0; i < list.length; i++){
			if(list[i].id == defval || list[i].title == defval){
				selval = list[i].id;
			}
			html += '<option class="lv1" value="' + list[i].id + '">' + list[i].title + '</option>';
			if(typeof(list[i].child) != 'undefined'){
				for(j = 0; j < list[i].child.length; j++)
				{
					if(list[i].child[j].id == defval || list[i].child[j].title == defval){
						selval = list[i].child[j].id;
					}
					html += '<option class="lv2" value="' + list[i].child[j].id + '">' + list[i].title + ' - ' + list[i].child[j].title + '</option>';
				}
			}
		}

		if(callback != null){
			callback(html, selval);
		}else{
			obj.html(html);
			if(selval != null){
				obj.find('option[value="' + selval + '"]').attr('selected', true);
			}
		}
	},

	addDetail: function(detail){
		if(detail == null){
			detail = {id: 0, create_time: 0, remark: '', amount_currency_id: 0, exchange_rate: '', amount: '', file: []};
		}

		// 创建界面
		var html = '';
		html += '<div class="item"><table>';
		html += '	<tr class="deldetail"><td colspan="2"><input type="hidden" name="detail_id" /><span>X</span></td></tr>';
		html += '	<tr>';
		html += '		<td><span>发生时间：</span><input type="text" class="tac" name="detail_create_time" /></td>';
		html += '		<td rowspan="4"><span class="br">说　　明：</span><textarea name="detail_remark"></textarea></td>';
		html += '	</tr>';
		html += '	<tr><td><span>所用货币：</span><select name="detail_amount_currency_id"></select></td></tr>';
		html += '	<tr><td><span>金　　额：</span><input type="text" name="detail_amount" /></td></tr>';
		html += '	<tr><td><span>结余汇率：</span><input type="text" name="detail_exchange_rate" /></td></tr>';
		html += '	<tr><td class="upload"><span>凭证列表：</span><font class="hide"></font><input type="file" name="file" /></td></tr>';
		html += '	<tr><td colspan="2" class="files"></td></tr>';
		html += '</table></div>';
		var obj = $(html).insertBefore($('.rdetail .btn'));
		obj.hide();

		// 数据填充
		obj.find('input[name="detail_id"]').val(detail.id);
		obj.find('input[name="detail_create_time"]').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'hh:mm'
		});
		if(detail.create_time > 0){
			obj.find('input[name="detail_create_time"]').val($.fn.convertTimestamp(detail.create_time, 'yyyy-MM-dd hh:mm'));
		}
		obj.find('textarea[name="detail_remark"]').val(detail.remark);
		$.fn.fullSel(obj.find('select[name="detail_amount_currency_id"]'), CURRENCY, detail.amount_currency_id, null);
		obj.find('input[name="detail_amount"]').val(detail.amount);
		obj.find('input[name="detail_exchange_rate"]').val(detail.exchange_rate);
		for(var i = 0; i < detail.file.length; i++){
			$.fn.addFile(obj.find('.files'), detail.file[i]);
		}

		// 事件绑定
		obj.find('input[type=file]').fileupload({
			url: '/upload.html',
			dataType: 'JSON',
			autoUpload: true,
			done: function(e, data){
				$(this).show().parent().find('font').hide();
				if(typeof(data.result.error) == 'undefined'){
					$.fn.addFile($(this).parent().parent().parent().find('.files'), data.result);
				}else{
					alert(data.result.error);
				}
			},
			progressall: function(e, data){
				var progress = parseInt(data.loaded / data.total * 100);
				$(this).hide().parent().find('font').show().html('<b style="color:#FF0000">上传中 ' + progress + '% <img src="/images/loading.gif" /></b>');
			},
			fail: function(){
				$(this).show().parent().find('font').hide();
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert('jqXHR: ' + $.fn.converToString(jqXHR) + "\n"
					+ 'textStatus: ' + $.fn.converToString(textStatus) + "\n"
					+ 'errorThrown: ' + $.fn.converToString(errorThrown)
				);
			}
		});

		obj.show(500);
	},
	
	addFile: function(obj, file){
		// 创建界面
		var html = '';
		html+= '<div class="file" data-id="' + file.id + '">';
		html+= '	<input type="hidden" name="file_id" value="' + file.id + '" />';
		html+= '	<a class="name" target="_blank" href="index.php?a=index&m=fileread&id=' + file.id + '">' + file.title + '</a>';
		html+= '	<i>' + $.fn.convertTimestamp(file.create_time, 'yyyy-MM-dd hh:mm') + '</i>';
		html+= '	<a class="del" href="javascript:;">删除</a>';
		html+= '</div>';
		obj = $(html).prependTo(obj);
		obj.hide().show(500);
	}
});
