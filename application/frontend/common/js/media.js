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
		target_params: {},
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
				self:         '.js-media-gallery',
				upload_area:  '.js-media-upload-area',
				upload_input: '.js-media-upload-file',
				file_list:    '.js-media-upload-gallery-list',
				file:         '.js-media-upload-gallery-item'
			},
			link: {
				form: '.js-media-link-form',
				url: 'input[name=url]',
				title: 'input[name=title]',
				align: 'select[name=align]',
				insertButton: '.js-media-link-insert-button',
				uploadButton: '.js-media-link-upload-button'
			},
			info: {
				self:   '.js-media-info',
				remove: '.js-media-item-info-remove',
				create_preview: '.js-media-item-info-create-preview',
				remove_preview: '.js-media-item-info-remove-preview',
				sizes:  'select[name=size]',
				empty:  '.js-media-info-empty'
			},
			buttons_insert:         '.js-media-insert-button',
			button_insert:          '.js-media-insert',
			button_insert_photoset: '.js-media-insert-photoset',
			modal: '#modal-image-upload'
		},
		// Классы
		classes: {
			selected: 'is-selected',
			progress: {
				value: 'js-progress-value',
				info: 'js-progress-info'
			}
		},
		// Роуты
		routers: {
			upload:              aRouter['ajax'] + "media/upload/",
			remove_file:         aRouter['ajax'] + "media/remove-file/",
			create_preview_file: aRouter['ajax'] + "media/create-preview-file/",
			remove_preview_file: aRouter['ajax'] + "media/remove-preview-file/",
			load_preview_items:  aRouter['ajax'] + "media/load-preview-items/",
			load_gallery:        aRouter['ajax'] + "media/load-gallery/",
			generate_target_tmp: aRouter['ajax'] + "media/generate-target-tmp/",
			submit_insert:       aRouter['ajax'] + "media/submit-insert/",
			submit_photoset:     aRouter['ajax'] + "media/submit-create-photoset/",
			save_data_file:      aRouter['ajax'] + "media/save-data-file/",
			upload_link:         aRouter['ajax'] + "media/upload-link/"
		},
		// Дефолтная высота списка файлов
		file_list_max_height: 354
	};

	var _countCheckLink = 0;

	var _blockCheckLink = -1;

	this.mode = 'insert';

	/**
	 * Инициализация
	 */
	this.init = function(options) {
		this.options = $.extend(true, {}, _defaults, options);

		this.elements = {
			uploader: $('#media-uploader'),
			buttonsInsert:        $(this.options.selectors.buttons_insert),
			buttonInsert:         $(this.options.selectors.button_insert),
			buttonInsertPhotoset: $(this.options.selectors.button_insert_photoset),
			modal:                $(this.options.selectors.modal),
			gallery: {
				self: $(this.options.selectors.gallery.self),
				fileList: $(this.options.selectors.gallery.file_list)
			},
			link: {
				url: $(this.options.selectors.link.form).find(this.options.selectors.link.url),
				align: $(this.options.selectors.link.form).find(this.options.selectors.link.align),
				title: $(this.options.selectors.link.form).find(this.options.selectors.link.title),
				insertButton: $(this.options.selectors.link.insertButton),
				uploadButton: $(this.options.selectors.link.uploadButton)
			},
			info: {
				self:   $(this.options.selectors.info.self),
				remove: $(this.options.selectors.info.remove),
				create_preview: $(this.options.selectors.info.create_preview),
				remove_preview: $(this.options.selectors.info.remove_preview),
				sizes:  $(this.options.selectors.info.sizes),
				empty:  $(this.options.selectors.info.empty)
			}
		};

		this.elements.uploader.lsUploader({
			autoload: false
		});

		this.activateInfoBlock( 'insert' );

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
				use_thumbs: $('#media-settings-mode-create-photoset').find('input[name=use_thumbs]').is(':checked'),
				show_caption: $('#media-settings-mode-create-photoset').find('input[name=show_caption]').is(':checked')
			}
			this.insert(this.options.routers.submit_photoset, params);
		}.bind(this));

		// Перемещение галереи из одного таба в другой
		$('.js-tab-show-gallery').on('tabactivate', function(event, tab) {
			this.elements.uploader.appendTo( $( '#' + tab.options.target ).find( '.modal-content' ) );
			this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'clearSelected' );
			this.activateInfoBlock( $(event.target).data('mediaMode') );
		}.bind(this));

		// Сохранение настроек файла
		$('.js-media-detail-area .js-input-title').on('blur', function(e) {
			this.saveDataFile($(e.currentTarget).attr('name'),$(e.currentTarget).val());
		}.bind(this));

		// Инициализация фоторамы при предпросмотре топика
		ls.hook.add('ls_topic_preview_after',function(){
			$('.fotorama').fotorama();
		});

		// Инициализация фоторамы после обновления комментов
		ls.hook.add('ls_comments_load_after',function(){
			$('.fotorama').fotorama();
		}.bind(this));

		// Инициализация фоторамы после hедактирования коммента
		ls.hook.add('ls_comments_submit_comment_update_after',function(){
			$('.fotorama').fotorama();
		}.bind(this));

		// После добавления комментария необходимо получить новый временный идентификатор и очистить галлерею
		ls.hook.add('ls_comments_add_after',function(){
			this.options.target_id='';
			this.options.target_tmp='';
			ls.media.generateTargetTmp(this.options.target_type);
			this.elements.gallery.fileList.lsUploaderFileList( 'clearSelected' );
			this.elements.gallery.fileList.empty();
			this.markAsEmpty();
		}.bind(this));

		// Инициализация фоторамы при предпросмотре
		ls.hook.inject([ls.utils,'textPreview'], function() {
			$('.fotorama').fotorama();
		},'textPreviewDisplayAfter');

		// Проверка корректности урла при вставке ссылки на медиа-объект
		this.elements.link.url.on('input', function() {
			this.checkLinkUrl(this.elements.link.url.val());
		}.bind(this));

		// Вставка медиа ссылки в текст
		this.elements.link.insertButton.on('click', function() {
			var sTitle = ls.utils.escapeHtml( this.elements.link.title.val() ),
			    sTextInsert;

			if ($('.js-media-link-settings-image').is(':visible')) {
				var sAlign = this.elements.link.align.val();
				sAlign = sAlign == 'center' ? 'class="image-center"' : 'align="' + sAlign + '"';
				sTextInsert = '<img src="' + this.elements.link.url.val() + '" title="' + sTitle + '" ' + sAlign + ' />';
			} else {
				sTextInsert = '<a href="'+this.elements.link.url.val() + '">' + sTitle + '</a>';
			}

			this.insertTextToEditor(sTextInsert);
			this.elements.modal.modal('hide');
		}.bind(this));

		// Загрузка медиа файлы по ссылке
		this.elements.link.uploadButton.on('click', function() {
			ls.ajax.submit(this.options.routers.upload_link, $('.js-media-link-form'), function (data){
				if (data.bStateError) {
					ls.msg.error(data.sMsgTitle,data.sMsg);
				} else {
					this.insertTextToEditor(data.sText);
					this.elements.modal.modal('hide');
					this.elements.gallery.fileList.lsUploaderFileList( 'load' );
				}
			}.bind(this), {
				// TODO: Fix validation
				validate: false,
				submitButton: this.elements.link.uploadButton,
				params: { target_type: this.options.target_type, target_id: this.options.target_id, target_tmp: this.options.target_tmp }
			});
		}.bind(this));

		// Создание превью из файла
		this.elements.info.create_preview.on('click', function(e) {
			ls.media.createPreviewActiveFile();
			e.preventDefault();
		}.bind(this));

		// Удаление превью
		this.elements.info.remove_preview.on('click', function(e) {
			ls.media.removePreviewActiveFile();
			e.preventDefault();
		}.bind(this));

		// После показа модального подгружаем контент
		this.elements.modal.on('modalaftershow', function() {
			this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'load' );
		}.bind(this));
	};

	/**
	 * Проверка корректности ссылки на медиа
	 *
	 * @param url
	 */
	this.checkLinkUrl = function(url) {
		// TODO: здесь нужно показывать спинер загрузки в инпуте

		_countCheckLink++;
		this.checkLinkUrlImage(url, function(src, result) {
			if ( ! result) {
				// если не изображение, то проверяем на другой тип, например, видео

				// разрешаем скрытие настроек только след итерациям после успешной проверки
				if (_blockCheckLink < _countCheckLink) {
					this.hideSettingsLinkImage();
				}
			} else {
				_blockCheckLink = _countCheckLink;
				this.showSettingsLinkImage(src);
			}
		}.bind(this));
	};

	/**
	 * Проверка на корректность ссылки на изображение
	 *
	 * @param url
	 * @param callback
	 */
	this.checkLinkUrlImage = function(url, callback) {
		var _this = this;

		$('<img>', {
			src: url,
			error: function() {
				callback.call(_this, this.src, false);
			},
			load: function() {
				callback.call(_this, this.src, true);
			}
		});
	};

	/**
	 * Вставка по ссылке - Показывает настройки изображения
	 *
	 * @param src Ссылка на изображения
	 */
	this.showSettingsLinkImage = function(src) {
		this.elements.link.uploadButton.prop('disabled', false);
		$('.js-media-link-settings-image-preview').attr('src', src);
		$('.js-media-link-settings-image').show();
	};

	/**
	 * Вставка по ссылке - Скрывает настройки изображения
	 */
	this.hideSettingsLinkImage = function() {
		this.elements.link.uploadButton.prop('disabled', true);
		$('.js-media-link-settings-image').hide();
	};

	/**
	 * Устанавливает текущий режим вставки медиа файлов
	 */
	this.activateInfoBlock = function( name ) {
		var blocks = $( '.js-media-info-block' ).hide();

		blocks.filter( '[data-type=' + name + ']' ).show();
	};

	/**
	 * Показывает блок с информацией об активном файле
	 *
	 * @param  {Object} item Выделенный файл
	 */
	this.showSettingsMode = function(item) {
		this.hideSettingsMode();

		/* Показываем только если есть выделенные элементы */
		if (this.elements.gallery.fileList.lsUploaderFileList( 'getSelectedFiles' ).length) {
			$('#media-settings-mode-' + this.mode).show();
			item = item || this.elements.gallery.fileList.lsUploaderFileList( 'getActiveFile' );

			/* Выставляем настройки по вставке медиа */
			this.elements.info.sizes.find('option:not([value=original])').remove();
			this.elements.info.sizes.append($.map(item.data('mediaImageSizes'), function (v, k) {
				/* Расчитываем пропорциональную высоту изображения */
				var height = v.h || parseInt(v.w * item.data('mediaHeight') / item.data('mediaWidth'));

				return '<option value="'+ v.w + 'x' + (v.h ? v.h : '') + (v.crop ? 'crop' : '') + '">' + v.w + ' × ' + height + '</option>';
			}).join(''));
		}
	};

	/**
	 * Скрывает форму с настройками
	 */
	this.hideSettingsMode = function() {
		$('.js-media-settings-mode').hide();
	};

	/**
	 * Создание превью из активного файла
	 */
	this.createPreviewActiveFile = function() {
		var item = this.elements.gallery.fileList.lsUploaderFileList( 'getActiveFile' );

		return item.length ? this.createPreviewFile(item.data('mediaId')) : false;
	};

	/**
	 * Создание превью
	 *
	 * @param {Number} id
	 */
	this.createPreviewFile = function(id) {
		var _this = this;

		ls.ajax.load(this.options.routers.create_preview_file, { id: id, target_type: this.options.target_type, target_id: this.options.target_id, target_tmp: this.options.target_tmp }, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.bUnsetOther) {
					$(_this.options.selectors.gallery.file).data('mediaRelationIsPreview',0);
				}
				var item=$(_this.options.selectors.gallery.file + '[data-media-id=' + id + ']');
				item.data('mediaRelationIsPreview',1);
				/**
				 * Обновляем отображение информации
				 */
				this.showDetail(item);
				this.loadPreviewItems();
			}
		}.bind(this));
	};

	/**
	 * Удаление превью у активного файла
	 */
	this.removePreviewActiveFile = function() {
		var item = this.elements.gallery.fileList.lsUploaderFileList( 'getActiveFile' );

		return item.length ? this.removePreviewFile(item.data('mediaId')) : false;
	};

	/**
	 * Удаление превью
	 *
	 * @param {Number} id
	 */
	this.removePreviewFile = function(id) {
		var _this = this;

		ls.ajax.load(this.options.routers.remove_preview_file, { id: id, target_type: this.options.target_type, target_id: this.options.target_id, target_tmp: this.options.target_tmp }, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var item=$(_this.options.selectors.gallery.file + '[data-media-id=' + id + ']');
				item.data('mediaRelationIsPreview',0);
				/**
				 * Обновляем отображение информации
				 */
				this.showDetail(item);
				this.loadPreviewItems();
			}
		}.bind(this));
	};

	this.loadPreviewItems = function() {
		ls.ajax.load(this.options.routers.load_preview_items, { target_type: this.options.target_type, target_id: this.options.target_id, target_tmp: this.options.target_tmp }, function(result) {
			this.renderPreviewItems(result.sTemplatePreview);
		}.bind(this));
	};

	this.renderPreviewItems = function(sTemplatePreview) {
		$('#tab-media-preview').find('.modal-content').html(sTemplatePreview);
	};

	/**
	 * Сохраняет настройки файла
	 *
	 * @param {String} name  Имя переменной
	 * @param {String} value Значение переменной
	 */
	this.saveDataFile = function(name, value) {
		var item = this.elements.gallery.fileList.lsUploaderFileList( 'getActiveFile' );

		if (item.length) {
			var id = item.data('mediaId');

			ls.ajax.load(this.options.routers.save_data_file, { name: name, value: value, id: id }, function(result) {
				if (result.bStateError) {
					ls.msg.error(result.sMsgTitle,result.sMsg);
				} else {
					$(this.options.selectors.gallery.file + '[data-media-id=' + id + ']').data('mediaData' + name.charAt(0).toUpperCase() + name.slice(1), value);
				}
			}.bind(this));
		}
	};

	/**
	 *
	 */
	this.hideDetail = function() {
		$('.js-media-detail-area').hide();
		this.elements.buttonsInsert.prop('disabled', true);
		this.elements.info.self.lsUploaderInfo( 'empty' );
		this.hideSettingsMode();
		this.resizeFileList();
	};

	/**
	 * Вставка файлов в редактор
	 */
	this.insert = function(url, params) {
		var items = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' );

		if ( ! items.length ) return false;

		/* Формируем список ID элементов */
		var aIds = $.map(items, function (value, index) {
			return $(value).data('mediaId');
		});

		ls.ajax.load(url, $.extend(true, {}, { ids: aIds }, params), function(result) {
			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				this.insertTextToEditor(result.sTextResult);
				this.elements.modal.modal('hide');
			}
		}.bind(this));
	};

	/**
	 * Вставляет текст в редактор
	 * @param  {String} text Текст
	 */
	this.insertTextToEditor = function(text) {
		$.markItUp({
			replaceWith: text
		});
	};

	/**
	 * Ресайз списка файлов, чтобы его высота была не меньше блока с инфой
	 */
	this.resizeFileList = function() {
		var fileListHeight = this.elements.gallery.fileList.outerHeight(),
			infoHeight = this.elements.info.self.outerHeight();

		this.elements.gallery.fileList.css({
			// 'min-height' : fileListHeight <= infoHeight || ( fileListHeight > this.options.file_list_max_height && fileListHeight > infoHeight ) ? infoHeight : this.options.file_list_max_height
			'min-height' : 30
		});
	};

	return this;
}).call(ls.media || {},jQuery);