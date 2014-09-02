/**
 * Media
 *
 * @module ls/uploader
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsUploader", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Загрузка файла
				upload: aRouter['ajax'] + 'media/upload/',
				// Генерация временного хэша
				generate_target_tmp: aRouter['ajax'] + 'media/generate-target-tmp/',
			},

			// Селекторы
			selectors: {
				// Список файлов
				list: '.js-uploader-list',
				// Информация о файле
				info: '.js-uploader-info',

				// Контейнер с элементами blocks и empty
				aside: '.js-uploader-aside',
				// Контейнер который отображается когда есть активный файл
				// и скрывается когда активного файла нет
				blocks: '.js-uploader-blocks',
				// Сообщение об отсутствии активного файла
				empty: '.js-uploader-aside-empty',

				// Drag & drop зона
				upload_zone:  '.js-uploader-area',
				// Инпут
				upload_input: '.js-uploader-file',
			},

			// Классы
			classes: {
				empty: 'is-empty'
			},

			// Настройки загрузчика
			fileupload : {
				url: null,
				sequentialUploads: false,
				singleFileUploads: true,
				limitConcurrentUploads: 3
			},

			// Параметры
			target_type: null,
			target_id: null,
			target_tmp: null,

			// Подгрузка файлов сразу после иниц-ии
			autoload: true,
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			// Получение элементов
			this.elements = {};

			$.each( this.option( 'selectors' ), function ( key, value ) {
				this.elements[ key ] = this.element.find( value );
			}.bind( this ));

			// Получение параметров
			this.option( 'params',      this.element.data( 'params' ) );
			this.option( 'target_type', this.element.data( 'type' ) );
			this.option( 'target_id',   this.element.data( 'id' ) );
			this.option( 'target_tmp',  this.element.data( 'tmp' ) || $.cookie( 'media_target_tmp_' + this.option( 'target_type' ) ) );

			// Генерация временного хэша для привязки
			if ( ! this.option( 'target_id' ) && ! this.option( 'target_tmp' ) ) {
				this.generateTargetTmp();
			}

			// Иниц-ия саб-компонентов
			this.elements.info.lsUploaderInfo();
			this.elements.list.lsUploaderFileList({
				uploader: this.element,
				info: this.elements.info
			});
			this.initUploader();

			// Подгрузка списка файлов
			this.option( 'autoload' ) && this.elements.list.lsUploaderFileList( 'load' );
		},

		/**
		 * Иниц-ия загрузчика
		 */
		initUploader: function() {
			// Настройки загрузчика
			$.extend( this.option( 'fileupload' ), {
				url: this.option( 'urls.upload' ),
				dropZone: this.elements.upload_zone,
				formData: {
					security_ls_key: LIVESTREET_SECURITY_KEY,
					target_type:     this.options.target_type || '',
					target_id:       this.options.target_id || '',
					target_tmp:      this.options.target_tmp || ''
				}
			});

			// Иниц-ия плагина
			this.elements.upload_input.fileupload( this.option( 'fileupload' ) );

			// Коллбэки
			this.element.on({
				fileuploadadd: this.onUploadAdd.bind( this ),
				fileuploaddone: function( event, data ) {
					this[ data.result.bStateError ? 'onUploadError' : 'onUploadDone' ]( data.context, data.result );
				}.bind( this ),
				fileuploadprogress: function( event, data ) {
					this.onUploadProgress( data.context, parseInt( data.loaded / data.total * 100, 10 ) );
				}.bind( this )
			});
		},

		/**
		 * 
		 */
		onUploadProgress: function( file, percent ) {
			file.lsUploaderFile( 'setProgress', percent );
		},

		/**
		 * 
		 */
		onUploadAdd: function( event, file ) {
			this.elements.list.lsUploaderFileList( 'addFile', file );
		},

		/**
		 * 
		 */
		onUploadDone: function( file, response ) {
			file.lsUploaderFile( 'destroy' );
			file.replaceWith(
				$( $.trim( response.sTemplateFile ) )
					.lsUploaderFile({ uploader: this.element })
					.lsUploaderFile( 'uploaded' )
					.lsUploaderFile( 'activate' )
			);
			file = null;
		},

		/**
		 * 
		 */
		onUploadError: function( file, response ) {
			ls.msg.error( response.sMsgTitle, response.sMsg );

			file.lsUploaderFile( 'error' );
		},

		/**
		 * Генерация хэша для привязки к нему загруженных файлов
		 */
		generateTargetTmp: function() {
			ls.ajax.load( this.option( 'urls.generate_target_tmp' ), {
				type: this.option( 'target_type' )
			}, function( response ) {
				this.options.target_tmp = response.sTmpKey || null;
			}.bind(this));
		},

		/**
		 * Скрывает контейнер с блоками
		 */
		hideBlocks: function() {
			this.getElement( 'blocks' ).hide();
			this.getElement( 'empty' ).show();
		},

		/**
		 * Показывает контейнер с блоками
		 */
		showBlocks: function() {
			this.getElement( 'empty' ).hide();
			this.getElement( 'blocks' ).show();
		},

		/**
		 * Помечает загрузчик как пустой
		 */
		markAsEmpty: function() {
			this.element.addClass( this.option( 'classes.empty' ) );
		},

		/**
		 * Помечает загрузчик как не пустой
		 */
		markAsNotEmpty: function() {
			this.element.removeClass( this.option( 'classes.empty' ) );
		},

		/**
		 * Получает элемент
		 */
		getElement: function( name ) {
			return this.elements[ name ];
		},
	});
})(jQuery);