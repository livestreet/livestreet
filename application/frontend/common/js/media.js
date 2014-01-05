/**
 * Работа с медиа файлами
 * 
 * @module ls/media
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.media = (function ($) {
	/**
	 * Дефолтные опции
	 * 
	 * @private
	 */
	var _defaults = {
		target_type: '',
		target_id: '',
		target_tmp: '',
		// Настройки загрузчика
		fileupload: {
			url: null,
			sequentialUploads: false,
			singleFileUploads: true,
			limitConcurrentUploads: 3
		},
		// Селекторы
		selectors: {
			gallery: {
				upload_area:  '.js-media-upload-area',
				upload_input: '.js-media-upload-file',
				file_list:    '.js-media-upload-gallery-list',
				file:         '.js-media-upload-gallery-item'
			},
			button_insert: '.js-media-insert',
			button_insert_photoset: '.js-media-insert-photoset'
		},
		// Классы
		classes: {
			selected: 'is-selected'
		},
		// Роуты
		routers: {
			upload:              aRouter['ajax'] + "media/upload/",
			remove_file:         aRouter['ajax'] + "media/remove-file/",
			load_gallery:        aRouter['ajax'] + "media/load-gallery/",
			generate_target_tmp: aRouter['ajax'] + "media/generate-target-tmp/",
			submit_insert:       aRouter['ajax'] + "media/submit-insert/",
			submit_photoset:     aRouter['ajax'] + "media/submit-create-photoset/",
			save_data_file:      aRouter['ajax'] + "media/save-data-file/"
		},
		// HTML
		html: {
			progress: '<div class="progress loading"></div>'
		}
	};

	this.mode = 'insert';

	/**
	 * Инициализация
	 */
	this.init = function(options) {
		this.options = $.extend(true, {}, _defaults, options);

		this.elements = {
			uploadArea: $(this.options.selectors.gallery.upload_area),
			uploadInput: $(this.options.selectors.gallery.upload_input),
			buttonInsert: $(this.options.selectors.button_insert),
			buttonInsertPhotoset: $(this.options.selectors.button_insert_photoset),
			gallery: {
				fileList: $(this.options.selectors.gallery.file_list)
			}
		};

		this.setMode('insert');

		this.options.target_tmp = this.options.target_tmp ? this.options.target_tmp : $.cookie('media_target_tmp_' + this.options.target_type);
		if ( ! this.options.target_id && ! this.options.target_tmp ) {
			ls.media.generateTargetTmp(this.options.target_type);
		}

		// Настройки загрузчика
		this.options.fileupload.url = this.options.fileupload.url || this.options.routers.upload;
		this.options.fileupload.dropZone = this.options.fileupload.dropZone || this.elements.uploadArea;
		this.options.fileupload.formData = $.extend({}, this.options.fileupload.formData || {}, {
			security_ls_key: LIVESTREET_SECURITY_KEY,
			target_type:     this.options.target_type,
			target_id:       this.options.target_id,
			target_tmp:      this.options.target_tmp
		});

		$.each(this.options.fileupload.formData, function(k, v) {
			if (v === null) this.options.fileupload.formData[k] = '';
		}.bind(this));

		this.elements.uploadInput.fileupload(this.options.fileupload);

		this.elements.uploadInput.bind({
			fileuploadadd: function(e, data) {
				ls.media.addPreload(data);
			},
			fileuploaddone: function(e, data) {
				ls.media[data.result.bStateError ? 'onUploadError' : 'onUploadDone'](data.context, data.result);
			},
			fileuploadprogress: function(e, data) {
				data.context.html(this.options.html.progress);
			}.bind(this)
		});

		// Ивенты вставки в редактор
		this.elements.buttonInsert.on('click', function () {
			var params = {
				align: $('#media-settings-mode-insert').find('select[name=align]').val(),
				size: $('#media-settings-mode-insert').find('select[name=size]').val()
			}

			this.insert(this.options.routers.submit_insert, params);
		}.bind(this));

		this.elements.buttonInsertPhotoset.on('click', function () {
			var params = {
				use_thumbs: $('#media-settings-mode-create-photoset').find('input[name=use_thumbs]').val(),
				show_caption: $('#media-settings-mode-create-photoset').find('input[name=show_caption]').val()
			}

			this.insert(this.options.routers.submit_photoset, params);
		}.bind(this));

		// Перемещение галереи из одного таба в другой
		$('.js-tab-show-gallery').on('tabbeforeactivate',function(event,tab){
			$('#upload-gallery-image').appendTo($('#' + tab.options.target).find('.modal-content'));
			ls.media.setMode($(event.target).data('mediaMode'));
		});

		// Сохранение настроек файла
		$('.js-media-detail-area .js-input-title').on('blur', function(e) {
			this.saveDataFile($(e.currentTarget).attr('name'),$(e.currentTarget).val());
		}.bind(this));

		// инициализация фоторамы при предпросмотре
		ls.hook.add('ls_topic_preview_after',function(){
			$('.fotorama').fotorama();
		});

		this.loadImageList();
		this.bindFileEvents();
	};

	/**
	 * 
	 */
	this.setMode = function(mode) {
		this.mode = mode;
		this.showSettingsMode();
	};

	/**
	 * Показывает блок с информацией об активном файле
	 * 
	 * @param  {Object} $item Выделенный файл
	 */
	this.showSettingsMode = function($item) {
		$('.js-media-settings-mode').hide();

		/* Показываем только если есть выделенные элементы */
		if (this.getSelected().length) {
			$('#media-settings-mode-' + this.mode).show();
			$item = $item || this.getCurrent();

			/* Выставляем настройки по вставке медиа */
			var $select = $('select[name=size]').html('');
			var sizes = $item.data('mediaImageSizes');

			$.each(sizes, function(k, v) {
				/* Расчитываем пропорциональную высоту изображения */
				var height = v.h;

				if ( ! height ) {
					height = parseInt(v.w * $item.data('mediaHeight') / $item.data('mediaWidth'));
				}

				$select.append('<option value="'+ v.w + (v.crop ? 'crop' : '') + '">' + v.w + ' × ' + height + '</option>');
			});
		}
	};

	/**
	 * 
	 */
	this.hideSettingsMode = function() {
		$('.js-media-settings-mode').hide();
	};

	/**
	 * Удаление активного файла
	 */
	this.removeCurrentFile = function() {
		var $item = this.getCurrent();

		if ($item.length) {
			return this.removeFile($item.data('mediaId'));
		}

		return false;
	};

	/**
	 * Удаление файла
	 * 
	 * @param {Number} id
	 */
	this.removeFile = function(id) {
		var _this = this;

		ls.ajax.load(this.options.routers.remove_file, { id: id }, function(result) {
			$(_this.options.selectors.gallery.file + '[data-media-id=' + id + ']').fadeOut(500, function() {
				$(this).remove();

				if ($itemNext = _this.searchNextSelected()) {
					_this.setCurrent($itemNext, true);
				} else {
					_this.hideDetail();
				}

				if ($(_this.options.selectors.gallery.file).length === 0) _this.showEmptyAlert();
			});
		});
	};

	/**
	 * Подгрузка списка файлов
	 */
	this.loadImageList = function() {
		this.elements.gallery.fileList.empty().addClass( ls.options.classes.states.loading );

		ls.ajax.load(this.options.routers.load_gallery, {target_type: this.options.target_type, target_id: this.options.target_id, target_tmp: this.options.target_tmp }, function(result) {
			this.elements.gallery.fileList.removeClass( ls.options.classes.states.loading ).html(result.sTemplate);
			this[result.sTemplate ? 'hideEmptyAlert' : 'showEmptyAlert']();
		}.bind(this));
	};

	/**
	 * 
	 */
	this.generateTargetTmp = function(type) {
		ls.ajax.load(this.options.routers.generate_target_tmp, { type: type }, function(result) {
			if (result.sTmpKey) this.options.target_tmp = result.sTmpKey;
		}.bind(this));
	};

	/**
	 * Индикация загрузки изображения
	 */
	this.addPreload = function(data) {
		this.hideEmptyAlert();
		data.context = $('<li class="modal-upload-image-gallery-list-item">' + this.options.html.progress + '</li>');
		this.elements.gallery.fileList.prepend(data.context);
	};

	/**
	 * Выделение изображений
	 */
	this.bindFileEvents = function() {
		this.elements.gallery.fileList.on('click', this.options.selectors.gallery.file, function(e){
			var $item = $(e.currentTarget);
			var allowMany = (this.mode == 'create-photoset' || e.ctrlKey || e.metaKey);

			if ($item.hasClass(ls.options.classes.states.active)) {
				/* Снимаем выделение с текущего */
				$item.removeClass(ls.options.classes.states.active).removeClass(this.options.classes.selected);

				/* Делаем активным другой выделенный элемент */
				if ($itemNext = this.searchNextSelected($item)) {
					this.setCurrent($itemNext,true);
				} else {
					this.hideDetail();
				}
			} else {
				/* Делаем текущим и показываем детальную информацию */
				this.setCurrent($item,allowMany);
			}
		}.bind(this));
	};

	/**
	 * 
	 */
	this.showEmptyAlert = function(data) {
		$('#media-empty').show();
		$('.js-media-item-info').hide();
	};

	/**
	 * 
	 */
	this.hideEmptyAlert = function(data) {
		$('#media-empty').hide();
		$('.js-media-item-info').show();
	};

	/**
	 * Завершение загрузки файла
	 */
	this.onUploadDone = function(context, response) {
		var $item = $(response.sTemplateFile);
		context.replaceWith($item);
		this.setSelected($item, true);
	};

	/**
	 * Помечаем файл как ошибочный (глюк при загрузке)
	 */
	this.onUploadError = function(context, response) {
		ls.msg.error(response.sMsgTitle,response.sMsg);
		context.html('ERROR');
	};

	/**
	 * Ищет файл для выделения
	 */
	this.searchNextSelected = function() {
		var $item = $(this.options.selectors.gallery.file + '.' + this.options.classes.selected + ':first');
		if ($item.length === 0) return false;
		return $item;
	};

	/**
	 * Помечает файл как активный
	 * 
	 * @param {Object}  $item     Помечаемый файл
	 * @param {Boolean} allowMany Выделение нескольких файлов
	 */
	this.setCurrent = function($item, allowMany) {
		$(this.options.selectors.gallery.file + '.' + ls.options.classes.states.active).removeClass(ls.options.classes.states.active);
		$item.addClass(ls.options.classes.states.active);
		this.setSelected($item, allowMany);
		this.showDetail($item);
	};

	/**
	 * Получает активный файл
	 * 
	 * @return {Object} Активный файл
	 */
	this.getCurrent = function() {
		return $(this.options.selectors.gallery.file + '.' + ls.options.classes.states.active);
	};

	/**
	 * Выделяет файл
	 * 
	 * @param {Object}  $item     Выделяемый файл
	 * @param {Boolean} allowMany Выделение нескольких файлов
	 */
	this.setSelected = function($item, allowMany) {
		if ( ! allowMany ) {
			this.getSelected().removeClass(this.options.classes.selected);
		}

		$item.addClass(this.options.classes.selected);

		/* Если нет еще нет активного элемента, то делаем активным текущий */
		if (this.getCurrent().length === 0) {
			this.setCurrent($item, allowMany);
		}
	};

	/**
	 * Получает выделенные файлы
	 * 
	 * @return {Array} Список выделенных файлов
	 */
	this.getSelected = function() {
		return $(this.options.selectors.gallery.file + '.' + this.options.classes.selected);
	};

	/**
	 * 
	 */
	this.saveDataFile = function(name, value) {
		var $item = this.getCurrent();

		if ($item.length) {
			var id = $item.data('mediaId');

			ls.ajax.load(this.options.routers.save_data_file, { name: name, value: value, id: id }, function(result) {
				$(this.options.selectors.gallery.file + '[data-media-id=' + id + ']').data('mediaData' + name.charAt(0).toUpperCase() + name.slice(1), value);
			}.bind(this));
		}
	};

	/**
	 * 
	 */
	this.showDetail = function($item) {
		$('.js-media-detail-preview').attr('src',$item.data('mediaPreview'));
		$('.js-media-detail-name').html($item.data('mediaFileName'));
		$('.js-media-detail-date').html($item.data('mediaDateAdd'));
		$('.js-media-detail-dimensions').html($item.data('mediaWidth')+' × '+$item.data('mediaHeight'));
		$('.js-media-detail-file-size').html(parseInt($item.data('mediaFileSize')/1024)+'kB');
		$('.js-media-detail-area .js-input-title').val($item.data('mediaDataTitle'));
		$('.js-media-detail-area').show();

		this.showSettingsMode($item);
	};

	/**
	 * 
	 */
	this.hideDetail = function() {
		$('.js-media-detail-area').hide();
		this.hideSettingsMode();
	};

	/**
	 * Вставка файлов в редактор
	 */
	this.insert = function(url, params) {
		var $aItems = this.getSelected();

		if ( ! $aItems.length ) return false;

		var aIds = [];
		var _params = {
			ids: aIds
		};

		/* Формируем список ID элементов */
		$aItems.each(function(k, v) {
			var $item = $(v);
			aIds.push($item.data('mediaId'));
		});

		ls.ajax.load(url, $.extend(true, {}, _params, params), function(result) {
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				$.markItUp({
					replaceWith: result.sTextResult
				});
				$('#modal-image-upload').modal('hide');
			}
		});
	};

	return this;
}).call(ls.media || {},jQuery);