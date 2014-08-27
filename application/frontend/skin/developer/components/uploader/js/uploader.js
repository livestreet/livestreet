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
				upload: aRouter['ajax'] + 'media/upload/',
				generate_target_tmp: aRouter['ajax'] + 'media/generate-target-tmp/',
			},

			// Селекторы
			selectors: {
				list:         '.js-media-upload-gallery-list',
				info:         '.js-media-info',
				upload_zone:  '.js-media-upload-area',
				upload_input: '.js-media-upload-file',
			},

			// Настройки загрузчика
			fileupload : {
				url: null,
				sequentialUploads: false,
				singleFileUploads: true,
				limitConcurrentUploads: 3
			},

			target_type: null,
			target_id: null,
			target_tmp: null,

			// Подгрузка списк сразу после иниц-ии
			autoload: true,
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this.elements = {
				list: this.element.find( this.option( 'selectors.list' ) ),
				info: this.element.find( this.option( 'selectors.info' ) ),
				upload_zone: this.element.find( this.option( 'selectors.upload_zone' ) ),
				upload_input: this.element.find( this.option( 'selectors.upload_input' ) ),
			};

			this.option( 'params', this.element.data( 'params' ) );
			this.option( 'target_type', this.element.data( 'type' ) );
			this.option( 'target_id', this.element.data( 'id' ) );
			this.option( 'target_tmp', this.element.data( 'tmp' ) || $.cookie( 'media_target_tmp_' + this.option( 'target_type' ) ) );

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
		 * Method
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
			file.find( file.lsUploaderFile( 'option', 'selectors.progress.value' ) ).height( percent + '%' );
			file.find( file.lsUploaderFile( 'option', 'selectors.progress.label' ) ).text( percent + '%' );
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
			file.replaceWith(
				$( $.trim( response.sTemplateFile ) )
					.lsUploaderFile({ uploader: this.element })
					.lsUploaderFile( 'activate' )
			);
		},

		/**
		 * 
		 */
		onUploadError: function( file, response ) {
			ls.msg.error( response.sMsgTitle, response.sMsg );

			file.find( file.lsUploaderFile( 'option', 'selectors.progress.value' ) ).height( 0 );
			file.find( file.lsUploaderFile( 'option', 'selectors.progress.label' ) ).text( 'ERROR' );
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
		 * Помечает загрузчик как пустой
		 */
		markAsEmpty: function() {
			this.element.addClass('is-empty');
		},

		/**
		 * Помечает загрузчик как не пустой
		 */
		markAsNotEmpty: function() {
			this.element.removeClass('is-empty');
		},

		/**
		 * 
		 */
		getElement: function( name ) {
			return this.elements[ name ];
		},
	});
})(jQuery);