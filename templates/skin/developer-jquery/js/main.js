Function.prototype.bind = function(context) {
	var fn = this;
	return function() {
		return fn.apply(context, arguments);
	};
};


var ls = ls || {};

/**
* Управление всплывающими сообщениями
*/
ls.msg = (function ($) {
	/**
	* Опции
	*/
	this.options = {
		class_notice: 'n-notice',
		class_error: 'n-error'
	};

	/**
	* Отображение информационного сообщения
	*/
	this.notice = function(title,msg){
		$.notifier.broadcast(title, msg, this.options.class_notice);
	};
	
	/**
	* Отображение сообщения об ошибке 
	*/
	this.error = function(title,msg){
		$.notifier.broadcast(title, msg, this.options.class_error);
	};
	
	return this;
}).call(ls.msg || {},jQuery);



/**
* Вспомогательные функции
*/
ls.tools = (function ($) {

	/**
	* Переводит первый символ в верхний регистр
	*/
	this.ucfirst = function(str) {
		var f = str.charAt(0).toUpperCase();
		return f + str.substr(1, str.length-1);
	}
	
	/**
	* Выделяет все chekbox с определенным css классом
	*/
	this.checkAll = function(cssclass, checkbox, invert) {
		$('.'+cssclass).each(function(index, item){
			if (invert) {
				$(item).attr('checked', !$(item).attr("checked"));
			} else {
				$(item).attr('checked', $(checkbox).attr("checked"));
			}
		});
	}

	/**
	* Предпросмотр
	*/
	this.textPreview = function(textId, save, divPreview) {
		var text = $('#'+textId).val();		
		ls.ajax(aRouter['ajax']+'preview/text/', {text: text, save: save}, function(result){
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error('Error','Please try again later');
			} else {
				if (!divPreview) {
					divPreview = 'text_preview';
				}
				if ($('#'+divPreview).length) {
					$('#'+divPreview).html(result.sText);
				}
			}
		});
	}
	
	return this;
}).call(ls.tools || {},jQuery);


/**
* Дополнительные функции
*/
ls = (function ($) {
	
	/**
	* Глобальные опции
	*/
	this.options = this.options || {}
	
	/**
	* Выполнение AJAX запроса, автоматически передает security key
	*/
	this.ajax = function(url,params,callback,more){
		more=more || {};
		params=params || {};
		params.security_ls_key=LIVESTREET_SECURITY_KEY;
		
		$.each(params,function(k,v){
			if (typeof(v) == "boolean") {
				params[k]=v ? 1 : 0;
			}
		})
		
		if (url.indexOf('http://')!=0 && url.indexOf('https://')!=0) {
			url=aRouter['ajax']+url+'/';
		}
		
		$.ajax({
			type: more.type || "POST",
			url: url,
			data: params,
			dataType: more.dataType || 'json',
			success: callback || function(msg){
				ls.debug("base success: ");
				ls.debug(msg);
			}.bind(this),
			error: more.error || function(msg){
				ls.debug("base error: ");
				ls.debug(msg);
			}.bind(this),
			complete: more.complete || function(msg){
				ls.debug("base complete: ");
				ls.debug(msg);
			}.bind(this)
		});
		
	};
	
	/**
	* Дебаг сообщений
	*/
	this.debug = function(msg) {
		if (this.options.debug) {
			this.log(msg);
		}
	}

	/**
	* Лог сообщений
	*/
	this.log = function(msg) {
		if (window.console && window.console.log) {
			console.log(msg);
		} else {
			//alert(msg);
		}
	}
		
	return this;
}).call(ls || {},jQuery);



/**
* Автокомплитер
*/
ls.autocomplete = (function ($) {
	/**
	* Добавляет автокомплитер к полю ввода
	*/
	this.add = function(obj, sPath, multiple) {
		if (multiple) {
			obj.bind("keydown", function(event) {
				if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				source: function(request, response) {
					ls.ajax(sPath,{value: ls.autocomplete.extractLast(request.term)},function(data){
						response(data.aItems);
					});
				},
				search: function() {
					var term = ls.autocomplete.extractLast(this.value);
					if (term.length < 2) {
						return false;
					}
				},
				focus: function() {
					return false;
				},
				select: function(event, ui) {
					var terms = ls.autocomplete.split(this.value);
					terms.pop();
					terms.push(ui.item.value);
					terms.push("");
					this.value = terms.join(", ");
					return false;
				}
			});
		} else {
			obj.autocomplete({
				source: function(request, response) {
					ls.ajax(sPath,{value: ls.autocomplete.extractLast(request.term)},function(data){
						response(data.aItems);
					});
				}
			});
		}
	}

	this.split = function(val) {
		return val.split( /,\s*/ );
	}
	
	this.extractLast = function(term) {
		return ls.autocomplete.split(term).pop();
	}
	
	return this;
}).call(ls.autocomplete || {},jQuery);



(ls.options || {}).debug=1;




$(document).ready(function(){
	// Всплывающие окна
	$('#login_form').jqm({trigger: '#login_form_show'});
	$('#blog_delete_form').jqm({trigger: '#blog_delete_show'});
	$('#add_friend_form').jqm({trigger: '#add_friend_show'});
	$('#form_upload_img').jqm();
	
	
	// Подключаем редактор
    $('#topic_text').markItUp(mySettings);
	
	
	// Datepicker
	$('.date-picker').datepicker({ 
		dateFormat: 'dd.mm.yy',
		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		firstDay: 1
	});
	
	
	// Поиск по тегам
	$('#tag_search_form').submit(function(){
		window.location = aRouter['tag']+$('#tag_search').val()+'/';
		return false;
	});
	
	
	// Автокомплит
	ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
	ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', true);
	ls.autocomplete.add($(".autocomplete-city"), aRouter['ajax']+'autocompleter/city/', false);
	ls.autocomplete.add($(".autocomplete-country"), aRouter['ajax']+'autocompleter/country/', false);

	
	// Скролл
	$(window)._scrollable();
});



// ===================
// Разное
// ===================


// Загрузка изображения
function ajaxUploadImg(value, sToLoad) {	
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.responseJS.bStateError) {
				$.notifier.error(req.responseJS.sMsgTitle,req.responseJS.sMsg);				
			} else {
				$.markItUp({ replaceWith: req.responseJS.sText} );
				$('#form_upload_img').jqmHide();
			}
		}
	}
	req.open(null, aRouter['ajax']+'upload/image/', true);
	req.send( { value: value, security_ls_key: LIVESTREET_SECURITY_KEY } );
}
