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
		// Селекторы
		selectors: {
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
		}
	};

	var _countCheckLink = 0;

	var _blockCheckLink = -1;

	/**
	 * Инициализация
	 */
	this.init = function(options) {
		var _this = this;

		this.options = $.extend(true, {}, _defaults, options);

		this.elements = {
			uploader: $('#media-uploader'),
			buttonsInsert:        $(this.options.selectors.buttons_insert),
			buttonInsert:         $(this.options.selectors.button_insert),
			buttonInsertPhotoset: $(this.options.selectors.button_insert_photoset),
			modal:                $(this.options.selectors.modal),
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
			autoload: false,
			params: {
				security_ls_key: LIVESTREET_SECURITY_KEY
			},
			file_options: {
				beforeactivate: function ( event, context ) {
					_this.updateInsertSettings( context.element );
				}
			}
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
			this.activateInfoBlock( $( event.target ).data( 'mediaMode' ) );
		}.bind(this));

		// После добавления комментария необходимо получить новый временный идентификатор и очистить галлерею
		ls.hook.add('ls_comments_add_after',function(){
			this.elements.uploader.lsUploader( 'generateTargetTmp' ).lsUploader( 'markAsEmpty' );
			this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'empty' );
		}.bind(this));

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
					this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'load' );
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


	//
	// Загрузка по ссылки
	//

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



	//
	// Превью
	//

	/**
	 * Создание превью из активного файла
	 */
	this.createPreviewActiveFile = function() {
		var file = this._getActiveFile();

		return file.length ? this.createPreviewFile( file.lsUploaderFile( 'getProperty', 'id' ) ) : false;
	};

	/**
	 * Удаление превью у активного файла
	 */
	this.removePreviewActiveFile = function() {
		var file = this._getActiveFile();

		return file.length ? this.removePreviewFile( file.lsUploaderFile( 'getProperty', 'id' ) ) : false;
	};

	/**
	 * Создание превью
	 *
	 * @param {Number} id
	 */
	this.createPreviewFile = function( id ) {
		ls.ajax.load(
			this.options.routers.create_preview_file,
			{
				id:          id,
				target_type: this.options.target_type,
				target_id:   this.options.target_id,
				target_tmp:  this.options.target_tmp
			},
			function( response ) {
				if ( response.bStateError ) {
					ls.msg.error( null, response.sMsg );
				} else {
					if ( response.bUnsetOther ) {
						this.elements.uploader
							.lsUploader( 'getElement', 'list' )
							.lsUploaderFileList( 'getFiles' )
							.lsUploaderFile( 'setProperty', 'media_relation_is_preview', false );
					}

					var file = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getFileById', id );

					file.lsUploaderFileList( 'setProperty', 'media_relation_is_preview', true );

					// Обновляем отображение информации
					this.showDetail( file );
					this.loadPreviewItems();
				}
			}.bind( this )
		);
	};

	/**
	 * Удаление превью
	 *
	 * @param {Number} id
	 */
	this.removePreviewFile = function( id ) {
		ls.ajax.load(
			this.options.routers.remove_preview_file,
			{
				id: id,
				target_type: this.options.target_type,
				target_id:   this.options.target_id,
				target_tmp:  this.options.target_tmp
			},
			function( response ) {
				if ( response.bStateError ) {
					ls.msg.error( null, response.sMsg );
				} else {
					var file = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getFileById', id );

					file.lsUploaderFileList( 'setProperty', 'media_relation_is_preview', false );

					// Обновляем отображение информации
					this.showDetail( file );
					this.loadPreviewItems();
			}
		}.bind( this ));
	};

	/**
	 * Подгрузка превьюшек
	 */
	this.loadPreviewItems = function() {
		ls.ajax.load(
			this.options.routers.load_preview_items,
			{
				target_type: this.options.target_type,
				target_id:   this.options.target_id,
				target_tmp:  this.options.target_tmp
			},
			function( response ) {
				this.renderPreviewItems( response.sTemplatePreview );
			}.bind( this )
		);
	};

	/**
	 * Отображение превьюшек
	 */
	this.renderPreviewItems = function( html ) {
		$( '#tab-media-preview' ).find( '.modal-content' ).html( html );
	};



	//
	// Вставка
	//

	/**
	 * Вставка файлов в редактор
	 */
	this.insert = function( url, params ) {
		var files = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' );

		if ( ! files.length ) return false;

		/* Формируем список ID элементов */
		var ids = $.map(files, function (value, index) {
			return $(value).data('mediaId');
		});

		ls.ajax.load( url, $.extend( true, {}, { ids: ids }, params ), function( response ) {
			if ( response.bStateError ) {
				ls.msg.error( response.sMsgTitle, response.sMsg );
			} else {
				this.insertTextToEditor( response.sTextResult );
				this.elements.modal.modal( 'hide' );
			}
		}.bind( this ));
	};

	/**
	 * Вставляет текст в редактор
	 * @param  {String} text Текст
	 */
	this.insertTextToEditor = function( text ) {
		$.markItUp({
			replaceWith: text
		});
	};



	//
	// Блоки
	//

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
	this.updateInsertSettings = function( file ) {
		if ( file.lsUploaderFile( 'getProperty', 'type' ) === '1' ) {
			// Выставляем настройки по вставке медиа
			this.elements.info.sizes.find( 'option:not([value=original])' ).remove();
			this.elements.info.sizes.append($.map( file.data('mediaImageSizes'), function ( v, k ) {
				// Расчитываем пропорциональную высоту изображения
				var height = v.h || parseInt( v.w * file.lsUploaderFile( 'getProperty', 'height' ) / file.lsUploaderFile( 'getProperty', 'width' ) );

				return '<option value="' + v.w + 'x' + (v.h ? v.h : '') + (v.crop ? 'crop' : '') + '">' + v.w + ' × ' + height + '</option>';
			}).join(''));
		}
	};

	/**
	 * Получает активный файл
	 */
	this._getActiveFile = function() {
		return this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getActiveFile' );
	};

	return this;
}).call(ls.media || {},jQuery);