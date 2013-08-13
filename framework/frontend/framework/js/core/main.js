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
 * Методы таймера например, запуск функии через интервал
 */
ls.timer = (function ($) {

	this.aTimers={};

	/**
	 * Запуск метода через определенный период, поддерживает пролонгацию
	 */
	this.run = function(fMethod,sUniqKey,aParams,iTime){
		iTime=iTime || 1500;
		aParams=aParams || [];
		sUniqKey=sUniqKey || Math.random();

		if (this.aTimers[sUniqKey]) {
			clearTimeout(this.aTimers[sUniqKey]);
			this.aTimers[sUniqKey]=null;
		}
		var timeout = setTimeout(function(){
			clearTimeout(this.aTimers[sUniqKey]);
			this.aTimers[sUniqKey]=null;
			fMethod.apply(this,aParams);
		}.bind(this),iTime);
		this.aTimers[sUniqKey]=timeout;
	};

	return this;
}).call(ls.timer || {},jQuery);

/**
 * Функционал хранения js данных
 */
ls.registry = (function ($) {

	this.aData={};

	/**
	 * Сохранение
	 */
	this.set = function(sName,data){
		this.aData[sName]=data;
	};

	/**
	 * Получение
	 */
	this.get = function(sName){
		return this.aData[sName];
	};

	return this;
}).call(ls.registry || {},jQuery);

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
			flash_url : PATH_FRAMEWORK_FRONTEND+'/js/vendor/swfupload/swfupload.swf',

			custom_settings : {
			},

			// Debug Settings
			debug: false
		};

		ls.hook.run('ls_swfupload_init_options_after',arguments,this.swfOptions);

	};

	this.loadSwf = function() {
		var f = {};

		f.onSwfobject = function(){
			if(window.swfobject && swfobject.swfupload){
				f.onSwfobjectSwfupload();
			}else{
				ls.debug('window.swfobject && swfobject.swfupload is undefined, load swfobject/plugin/swfupload.js');
				$.getScript(
					PATH_FRAMEWORK_FRONTEND+'/js/vendor/swfobject/plugin/swfupload.js',
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
					PATH_FRAMEWORK_FRONTEND+'/js/vendor/swfupload/swfupload.js',
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
					PATH_FRAMEWORK_FRONTEND+'/js/vendor/swfobject/swfobject.js',
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
	};

	this.handlerFileQueueError = function(file, errorCode, message) {
		$(this).trigger('eFileQueueError',[file, errorCode, message]);
	};

	this.handlerFileDialogComplete = function(numFilesSelected, numFilesQueued) {
		$(this).trigger('eFileDialogComplete',[numFilesSelected, numFilesQueued]);
		if (numFilesQueued>0) {
			this.startUpload();
		}
	};

	this.handlerUploadProgress = function(file, bytesLoaded) {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		$(this).trigger('eUploadProgress',[file, bytesLoaded, percent]);
	};

	this.handlerUploadError = function(file, errorCode, message) {
		$(this).trigger('eUploadError',[file, errorCode, message]);
	};

	this.handlerUploadSuccess = function(file, serverData) {
		$(this).trigger('eUploadSuccess',[file, serverData]);
	};

	this.handlerUploadComplete = function(file) {
		var next = this.getStats().files_queued;
		if (next > 0) {
			this.startUpload();
		}
		$(this).trigger('eUploadComplete',[file, next]);
	};

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
	};

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
	};

	/**
	* Предпросмотр
	*/
	this.textPreview = function(textId, save, divPreview) {
		var text = WYSIWYG ? tinyMCE.activeEditor.getContent() : $('#' + textId).val();
		var ajaxUrl = aRouter['ajax']+'preview/text/';
		var ajaxOptions = {text: text, save: save};
		ls.hook.marker('textPreviewAjaxBefore');
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
				ls.hook.marker('textPreviewDisplayBefore');
				if (elementPreview.length) {
					elementPreview.html(result.sText);
					ls.hook.marker('textPreviewDisplayAfter');
				}
			}
		});
	};

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
	};

	/**
	 * Получает значение атрибута data
	 */
	this.getOption = function (element, data, defaultValue) {
		var option = element.data(data);

		switch (option) {
			case 'true':
				return true;
			case 'false':
				return false;
			case undefined:
				return defaultValue
			default:
				return option;
		}
	};

	this.getDataOptions = function (element, prefix) {
		var prefix = prefix || 'option',
			resultOptions = {},
			dataOptions = typeof element === 'string' ? $(element).data() : element.data();

		for (option in dataOptions) {
			// Remove 'option' prefix
			if (option.substring(0, prefix.length) == prefix) {
				var str = option.substring(prefix.length);
				resultOptions[str.charAt(0).toLowerCase() + str.substring(1)] = dataOptions[option];
			}
		}

		return resultOptions;
	};

	return this;
}).call(ls.tools || {},jQuery);


/**
* Дополнительные функции
*/
ls = (function ($) {

	/**
	* Глобальные опции
	*/
	this.options = this.options || {};

	/**
	* Выполнение AJAX запроса, автоматически передает security key
	*/
	this.ajax = function(url, params, callback, more) {
		more = more || {};
		params = params || {};
		params.security_ls_key = LIVESTREET_SECURITY_KEY;

		$.each(params, function(k, v){
			if (typeof(v) == "boolean") {
				params[k] = v ? 1 : 0;
			}
		});

		if (url.indexOf('http://') != 0 && url.indexOf('https://') != 0 && url.indexOf('/') != 0) {
			url=aRouter['ajax'] + url + '/';
		}

		var ajaxOptions = $.extend({}, {
			type: "POST",
			url: url,
			data: params,
			dataType: 'json',
			success: callback || function(){
				ls.debug("ajax success: ");
				ls.debug.apply(this,arguments);
			}.bind(this),
			error: function(msg){
				ls.debug("ajax error: ");
				ls.debug.apply(this, arguments);
			}.bind(this),
			complete: function(msg){
				ls.debug("ajax complete: ");
				ls.debug.apply(this, arguments);
			}.bind(this)
		}, more);

		ls.hook.run('ls_ajax_before', [ajaxOptions, callback, more], this);

		return $.ajax(ajaxOptions);
	};

	/**
	* Выполнение AJAX отправки формы, включая загрузку файлов
	*/
	this.ajaxSubmit = function(url, form, callback, more) {
		var more = more || {}
			form = typeof form == 'string' ? $(form) : form;

		if (url.indexOf('http://') != 0 && url.indexOf('https://') != 0 && url.indexOf('/') != 0) {
			url = aRouter['ajax'] + url + '/';
		}

		var options = {
			type: 'POST',
			url: url,
			dataType: more.dataType || 'json',
			data: {
				security_ls_key: LIVESTREET_SECURITY_KEY
			},
			beforeSubmit: function (arr, form, options) {
                form.find('[type=submit]').prop('disabled', true).addClass('loading');
            },
            beforeSerialize: function (form, options) {
                return form.parsley('validate');
            },
			success: typeof callback == 'function' ? function (result, status, xhr, form) {
				if (result.bStateError) {
	            	form.find('[type=submit]').prop('disabled', false).removeClass('loading');
	                ls.msg.error(null, result.sMsg);

	                more.warning(result, status, xhr, form);
	            } else {
	                if (result.sMsg) {
	                    form.find('[type=submit]').prop('disabled', false).removeClass('loading');
	                    ls.msg.notice(null, result.sMsg);
	                }
					callback(result, status, xhr, form);
	            }
			} : function () {
				ls.debug("ajax success: ");
				ls.debug.apply(this, arguments);
			}.bind(this),
			error: more.error || function(){
				ls.debug("ajax error: ");
				ls.debug.apply(this, arguments);
			}.bind(this)
		};

		ls.hook.run('ls_ajaxsubmit_before', [options,form,callback,more], this);

		form.ajaxSubmit(options);
	};

	/**
	 * Создание ajax формы
	 *
	 * @param  {string}          url      Ссылка
	 * @param  {jquery, string}  form     Селектор формы либо объект jquery
	 * @param  {Function}        callback Success коллбэк
	 * @param  {[type]}          more     Дополнительные параметры
	 */
	this.ajaxForm = function(url, form, callback, more) {
		var form = typeof form == 'string' ? $(form) : form;

		form.on('submit', function (e) {
			ls.ajaxSubmit(url, form, callback, more);
			e.preventDefault();
		});
	};

	/**
	* Дебаг сообщений
	*/
	this.debug = function() {
		if (this.options.debug) {
			this.log.apply(this,arguments);
		}
	};

	/**
	* Лог сообщений
	*/
	this.log = function() {
		if (!$.browser.msie && window.console && window.console.log) {
			Function.prototype.bind.call(console.log, console).apply(console, arguments);
		} else {
			//alert(msg);
		}
	};

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
	};

	this.split = function(val) {
		return val.split( /,\s*/ );
	};

	this.extractLast = function(term) {
		return ls.autocomplete.split(term).pop();
	};

	return this;
}).call(ls.autocomplete || {},jQuery);

/**
 * Костыли для ИЕ
 */
ls.ie = (function ($) {

	return this;
}).call(ls.ie || {},jQuery);



(ls.options || {}).debug=1;