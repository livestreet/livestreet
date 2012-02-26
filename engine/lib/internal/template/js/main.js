Function.prototype.bind = function(context) {
	var fn = this;
	if(jQuery.type(fn) != 'function'){
		throw new TypeError('Function.prototype.bind: call on non-function');
	};
	if(jQuery.type(context) == 'null'){
		throw new TypeError('Function.prototype.bind: cant be bound to null');
	};
	return function() {
		return fn.apply(context, arguments);
	};
};
String.prototype.tr = function(a,p) {
	var k;
	var p = typeof(p)=='string' ? p : '';
	var s = this;
	jQuery.each(a,function(k){
		var tk = p?p.split('/'):[];
		tk[tk.length] = k;
		var tp = tk.join('/');
		if(typeof(a[k])=='object'){
			s = s.tr(a[k],tp);
		}else{
			s = s.replace((new RegExp('%%'+tp+'%%', 'g')), a[k]);
		};
	});
	return s;
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
* Доступ к языковым текстовкам (предварительно должны быть прогружены в шаблон)
*/
ls.lang = (function ($) {
	/**
	* Набор текстовок
	*/
	this.msgs = {};

	/**
	* Загрузка текстовок
	*/
	this.load = function(msgs){
		$.extend(true,this.msgs,msgs);
	};

	/**
	* Отображение сообщения об ошибке
	*/
	this.get = function(name,replace){
		if (this.msgs[name]) {
			var value=this.msgs[name];
			if (replace) {
				value = value.tr(replace);
			}
			return value;
		}
		return '';
	};

	return this;
}).call(ls.lang || {},jQuery);


/**
* Flash загрузчик
*/
ls.swfupload = (function ($) {

	this.swfu = null;
	this.swfOptions = {};

	this.initOptions = function() {
		
		this.swfOptions = {
			// Backend Settings
			upload_url: aRouter['photoset']+"upload",
			post_params: {'SSID':SESSION_ID, 'security_ls_key': LIVESTREET_SECURITY_KEY},

			// File Upload Settings
			file_types : "*.jpg;*.jpe;*.jpeg;*.png;*.gif;*.JPG;*.JPE;*.JPEG;*.PNG;*.GIF",
			file_types_description : "Images",
			file_upload_limit : "0",

			// Event Handler Settings
			file_queue_error_handler : this.handlerFileQueueError,
			file_dialog_complete_handler : this.handlerFileDialogComplete,
			upload_progress_handler : this.handlerUploadProgress,
			upload_error_handler : this.handlerUploadError,
			upload_success_handler : this.handlerUploadSuccess,
			upload_complete_handler : this.handlerUploadComplete,

			// Button Settings
			button_placeholder_id : "start-upload",
			button_width: 122,
			button_height: 30,
			button_text : '<span class="button">'+ls.lang.get('topic_photoset_upload_choose')+'</span>',
			button_text_style : '.button { color: #1F8AB7; font-size: 14px; }',
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_text_left_padding: 6,
			button_text_top_padding: 3,
			button_cursor: SWFUpload.CURSOR.HAND,

			// Flash Settings
			flash_url : DIR_ROOT_ENGINE_LIB+'/external/swfupload/swfupload.swf',

			custom_settings : {
			},

			// Debug Settings
			debug: false
		};
		
		ls.hook.run('ls_swfupload_init_options_after',arguments,this.swfOptions)
		
	}

	this.loadSwf = function() {
		var f = {};
		
		f.onSwfobject = function(){
			if(window.swfobject && swfobject.swfupload){
				f.onSwfobjectSwfupload();
			}else{
				ls.debug('window.swfobject && swfobject.swfupload is undefined, load swfobject/plugin/swfupload.js');
				$.getScript(
					DIR_ROOT_ENGINE_LIB+'/external/swfobject/plugin/swfupload.js',
					f.onSwfobjectSwfupload
				);
			}
		}.bind(this);
		
		f.onSwfobjectSwfupload = function(){
			if(window.SWFUpload){
				f.onSwfupload();
			}else{
				ls.debug('window.SWFUpload is undefined, load swfupload/swfupload.js');
				$.getScript(
					DIR_ROOT_ENGINE_LIB+'/external/swfupload/swfupload.js',
					f.onSwfupload
				);
			}
		}.bind(this);
		
		f.onSwfupload = function(){
			this.initOptions();
			$(this).trigger('load');
		}.bind(this);
		
		
		(function(){
			if(window.swfobject){
				f.onSwfobject();
			}else{
				ls.debug('window.swfobject is undefined, load swfobject/swfobject.js');
				$.getScript(
					DIR_ROOT_ENGINE_LIB+'/external/swfobject/swfobject.js',
					f.onSwfobject
				);
			}
		}.bind(this))();
	};
	

	this.init = function(opt) {
		if (opt) {
			$.extend(true,this.swfOptions,opt);
		}
		this.swfu = new SWFUpload(this.swfOptions);
		return this.swfu;
	}

	this.handlerFileQueueError = function(file, errorCode, message) {
		$(this).trigger('eFileQueueError',[file, errorCode, message]);
	}

	this.handlerFileDialogComplete = function(numFilesSelected, numFilesQueued) {
		$(this).trigger('eFileDialogComplete',[numFilesSelected, numFilesQueued]);
		if (numFilesQueued>0) {
			this.startUpload();
		}
	}

	this.handlerUploadProgress = function(file, bytesLoaded) {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		$(this).trigger('eUploadProgress',[file, bytesLoaded, percent]);
	}

	this.handlerUploadError = function(file, errorCode, message) {
		$(this).trigger('eUploadError',[file, errorCode, message]);
	}

	this.handlerUploadSuccess = function(file, serverData) {
		$(this).trigger('eUploadSuccess',[file, serverData]);
	}

	this.handlerUploadComplete = function(file) {
		var next = this.getStats().files_queued;
		if (next > 0) {
			this.startUpload();
		}
		$(this).trigger('eUploadComplete',[file, next]);
	}

	return this;
}).call(ls.swfupload || {},jQuery);


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
		var text =(BLOG_USE_TINYMCE) ? tinyMCE.activeEditor.getContent()  : $('#'+textId).val();
		var ajaxUrl = aRouter['ajax']+'preview/text/';
		var ajaxOptions = {text: text, save: save};
		'*textPreviewAjaxBefore*'; '*/textPreviewAjaxBefore*';
		ls.ajax(ajaxUrl, ajaxOptions, function(result){
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle||'Error',result.sMsg||'Please try again later');
			} else {
				if (!divPreview) {
					divPreview = 'text_preview';
				}
				var elementPreview = $('#'+divPreview);
				'*textPreviewDisplayBefore*'; '*/textPreviewDisplayBefore*';
				if (elementPreview.length) {
					elementPreview.html(result.sText);
					'*textPreviewDisplayAfter*'; '*/textPreviewDisplayAfter*';
				}
			}
		});
	}

	/**
	* Возвращает выделенный текст на странице
	*/
	this.getSelectedText = function(){
		var text = '';
		if(window.getSelection){
			text = window.getSelection().toString();
		} else if(window.document.selection){
			var sel = window.document.selection.createRange();
			text = sel.text || sel;
			if(text.toString) {
				text = text.toString();
			} else {
				text = '';
			}
		}
		return text;
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

		var ajaxOptions = {
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
		};
		
		ls.hook.run('ls_ajax_before', [ajaxOptions], this);

		return $.ajax(ajaxOptions);
	};

	/**
	* Выполнение AJAX отправки формы, включая загрузку файлов
	*/
	this.ajaxSubmit = function(url,form,callback,more) {
		more=more || {};
		if (typeof(form)=='string') {
			form=$('#'+form);
		}
		if (url.indexOf('http://')!=0 && url.indexOf('https://')!=0) {
			url=aRouter['ajax']+url+'/';
		}

		var options={
			type: 'POST',
			url: url,
			dataType: more.dataType || 'json',
			data: {security_ls_key: LIVESTREET_SECURITY_KEY},
			success: callback || function(msg){
				ls.debug("base success: ");
				ls.debug(msg);
			}.bind(this),
			error: more.error || function(x,s,e){
				ls.debug("base error: ");
				ls.debug(x);
			}.bind(this)

		}

		ls.hook.run('ls_ajaxsubmit_before', [options], this);
		
		form.ajaxSubmit(options);
	}

	/**
	* Загрузка изображения
	*/
	this.ajaxUploadImg = function(form, sToLoad) {
		'*ajaxUploadImgBefore*'; '*/ajaxUploadImgBefore*';
		ls.ajaxSubmit('upload/image/',form,function(data){
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				$.markItUp({replaceWith: data.sText} );
				$('#form_upload_img').find('input[type="text"], input[type="file"]').val('');
				$('#form_upload_img').jqmHide();
				'*ajaxUploadImgAfter*'; '*/ajaxUploadImgAfter*';
			}
		});
	}

	/**
	* Дебаг сообщений
	*/
	this.debug = function() {
		if (this.options.debug) {
			this.log.apply(this,arguments);
		}
	}

	/**
	* Лог сообщений
	*/
	this.log = function() {
		if (!$.browser.msie && window.console && window.console.log) {
			Function.prototype.bind.call(console.log, console).apply(console, arguments);
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