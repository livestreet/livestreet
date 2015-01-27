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
				generate_target_tmp: aRouter['ajax'] + 'media/generate-target-tmp/'
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
				upload_input: '.js-uploader-file'
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

			// Доп-ые параметры передаваемые в аякс запросах
			params: {},

			// Подгрузка файлов сразу после иниц-ии
			autoload: true,

			info_options: {},
			list_options: {},
			file_options: {}
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
			this.option( 'params', $.extend( {}, this.option( 'params' ), ls.utils.getDataOptions( this.element, 'param' ) ) );

			/**
			 * Генерация временного хэша для привязки
			 * TODO: Перенести в media
			 *
			 * Может быть ситуация, когда на странице несколько аплоадеров для одного типа таргета медиа
			 * В итоге они все делают запрос на получение временного ключа и перезаписывают его
			 * Нужно использовать внешний триггер для фиксации первого запроса
			 */
			if ( ! this.option( 'params.target_id' ) ) {
				this.option( 'params.target_tmp', this.element.data( 'tmp' ) || $.cookie( 'media_target_tmp_' + this.option( 'params.target_type' ) ) );

				if ( !this.option( 'params.target_tmp' ) ) {
					this.generateTargetTmp();
				}
			}

			// Иниц-ия саб-компонентов
			this.elements.info.lsUploaderInfo( $.extend( {}, this.option( 'info_options' ), { uploader: this.element } ) );
			this.elements.list.lsUploaderFileList( $.extend( {}, this.option( 'list_options' ), { uploader: this.element, file_options: this.option( 'file_options' ) } ) );

			this.initFileUploader();

			// Подгрузка списка файлов
			this.option( 'autoload' ) && this.elements.list.lsUploaderFileList( 'load' );
		},

		/**
		 * Иниц-ия загрузчика
		 */
		initFileUploader: function() {
			// Настройки загрузчика
			$.extend( this.option( 'fileupload' ), {
				url:      this.option( 'urls.upload' ),
				dropZone: this.elements.upload_zone
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
			// TODO: Перенести в иниц-ию fileuploader'а

			// В параметрах заменяем null на пустую строку
			$.each( this.option( 'params' ), function ( key, value ) {
				value === null && this.option( 'params.' + key, '' );
			}.bind( this ));

			// Устанавливаем актуальные параметры для загрузчика,
			// т.к. они могли измениться с момента иниц-ии (например target_tmp)
			$( event.target ).fileupload( 'option', 'formData', this.option( 'params' ) );

			this.elements.list.lsUploaderFileList( 'addFile', file );
		},

		/**
		 * 
		 */
		onUploadDone: function( file, response ) {
			file.lsUploaderFile( 'destroy' );
			file.replaceWith(
				$( $.trim( response.sTemplateFile ) )
					.lsUploaderFile( $.extend( {}, this.option( 'file_options' ), { uploader: this.element } ) )
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

			setTimeout(function () {
				file.lsUploaderFile( 'removeDom' );
				file = null;
			}.bind( this ), 500 );
		},

		/**
		 * Генерация хэша для привязки к нему загруженных файлов
		 * Суть в том, чтобы не делать несколько запросов на генерацию для одного типа (когда на одной странице несколько аплоадеров)
		 */
		generateTargetTmp: function() {
			var key='ls.media.target_tmp_create_request_' + this.option( 'params.target_type' );
			if (ls.registry.get(key)) {
				$(window).bind(key, function(e, sTmpKey){
					this.option( 'params.target_tmp', sTmpKey || null );
				}.bind( this ));
			} else {
				ls.registry.set(key, true);
				ls.ajax.load( this.option( 'urls.generate_target_tmp' ), {
					type: this.option( 'params.target_type' )
				}, function( response ) {
					$(window).trigger(key,[response.sTmpKey]);
					this.option( 'params.target_tmp', response.sTmpKey || null );
				}.bind( this ));
			}
		},

		/**
		 * Скрывает контейнер с блоками
		 */
		hideBlocks: function() {
			this.getElement( 'aside' ).addClass( this.option( 'classes.empty' ) );
		},

		/**
		 * Показывает контейнер с блоками
		 */
		showBlocks: function() {
			this.getElement( 'aside' ).removeClass( this.option( 'classes.empty' ) );
		},

		/**
		 * Помечает загрузчик как пустой
		 */
		checkEmpty: function() {
			if ( this.getElement( 'list' ).lsUploaderFileList( 'isEmpty' ) ) {
				this.markAsEmpty();
			} else {
				this.markAsNotEmpty();
			}
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
		}
	});
})(jQuery);