/**
 * Media
 *
 * @module ls/media
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 *
 * TODO: Фильтрация файлов по типу при переключении табов
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsMedia", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Редактор к которому привязано текущее окно
			editor: $(),

			// Ссылки
			urls: {
				// Вставка файла
				insert: aRouter.ajax + 'media/submit-insert/',
				// Вставка фотосета
				photoset: aRouter.ajax + 'media/submit-create-photoset',
				// Загрузка файла по ссылке
				url_upload: aRouter.ajax + 'media/upload-link/',
				// Вставка файла по ссылке
				url_insert: aRouter.ajax + 'media/upload-insert/'
			},

			// Селекторы
			selectors: {
				uploader: '.js-media-uploader',
				block: '.js-media-info-block',
				insert: {
					submit: '.js-media-insert-submit'
				},
				photoset: {
					submit: '.js-media-photoset-submit'
				},
				url: {
					form: '.js-media-url-form',
					url: '.js-media-url-form-url',
					type: '.js-media-url-type',
					block_container: '.js-media-url-settings-blocks',
					submit_upload: '.js-media-url-submit-upload',
					submit_insert: '.js-media-url-submit-insert',
					image_preview: '.js-media-url-image-preview'
				}
			},

			uploader_options: {}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			! this.option( 'editor' ).length && this.option( 'editor', $( '#' + this.element.data( 'media-editor') ) );

			this.elements = {
				tabs: this.element.find( '[data-tab-type=tab-list] > [data-tab-type=tab]' ),
				uploader: this.element.find( this.option( 'selectors.uploader' ) ),
				blocks: this.element.find( this.option( 'selectors.uploader' ) + ' ' + this.option( 'selectors.block' ) ),
				insert: {
					submit: this.element.find( this.option( 'selectors.insert.submit' ) )
				},
				photoset: {
					submit: this.element.find( this.option( 'selectors.photoset.submit' ) )
				},
				url: {
					form: this.element.find( this.option( 'selectors.url.form' ) ),
					url: this.element.find( this.option( 'selectors.url.url' ) ),
					type: this.element.find( this.option( 'selectors.url.type' ) ),
					block_container: this.element.find( this.option( 'selectors.url.block_container' ) ),
					submit_upload: this.element.find( this.option( 'selectors.url.submit_upload' ) ),
					submit_insert: this.element.find( this.option( 'selectors.url.submit_insert' ) ),
					image_preview: this.element.find( this.option( 'selectors.url.image_preview' ) )
				}
			};

			this.elements.url.blocks = this.elements.url.block_container.find( this.option( 'selectors.block' ) );

			// Иниц-ия модального окна
			this.element.lsModal({
				aftershow: function () {
					_this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'load' );
				}
			});

			// Иниц-ия загрузчика
			this.elements.uploader.lsUploader( $.extend( {}, this.option( 'uploader_options' ), {
				autoload: false,
				params: {
					security_ls_key: LIVESTREET_SECURITY_KEY
				},
				file_options: {
					beforeactivate: function ( event, context ) {
						_this.activateInfoBlock( context.element );
					}
				}
			}));

			// Табы
			this.elements.tabs.on( 'lstabactivate', function( event, tab ) {
				this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'option', 'multiselect_ctrl', true );

				// Перемещение галереи из одного таба в другой
				if ( tab.element.hasClass( 'js-tab-show-gallery' ) ) {
					this.elements.uploader.appendTo( this.element.find( '#' + tab.options.target + ' .js-media-pane-content' ) );
					this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'resetFilter' );
					this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'clearSelected' );
				}
			}.bind( this ));

			this.elements.tabs.filter( '[data-media-name=photoset]' ).on( 'lstabactivate', function( event, tab ) {
				this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'option', 'multiselect_ctrl', false );
				this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'filterFilesByType', [ '1' ] );
			}.bind( this ));

			//
			// INSERT
			//

			this.elements.insert.submit.on( 'click' + this.eventNamespace, function () {
				var files = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' );

				this.insertFiles( this.option( 'urls.insert' ), this.getSettings(), files );
			}.bind( this ));

			//
			// PHOTOSET
			//

			this.elements.photoset.submit.on( 'click' + this.eventNamespace, function () {
				var files = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' );

				this.insertFiles( this.option( 'urls.photoset' ), this.getSettings(), files );
			}.bind( this ));

			//
			// INSERT FROM URL
			//

			this.elements.url.type.on( 'change' + this.eventNamespace, this.onUrlTypeChange.bind( this ) );
			this.elements.url.url.on( 'keyup change' + this.eventNamespace, this.onUrlChange.bind( this ) );
			this.elements.url.submit_upload.on( 'click' + this.eventNamespace, this.urlInsert.bind( this, true ) );
			this.elements.url.submit_insert.on( 'click' + this.eventNamespace, this.urlInsert.bind( this, false ) );
		},

		/**
		 * 
		 */
		show: function() {
			this.element.lsModal( 'show' );
		},

		/**
		 * 
		 */
		hide: function() {
			this.element.lsModal( 'hide' );
		},

		/**
		 * 
		 */
		getSettings: function() {
			return this.elements.blocks
				.filter( ':visible' )
				.find( 'form' )
				.serializeJSON();
		},

		/**
		 * Вставляет выделенные файлы в редактор
		 */
		insertFiles: function( url, params, files ) {
			if ( ! files.length ) return;

			// Формируем список ID файлов
			var ids = $.map( files, function ( file ) {
				return $( file ).lsUploaderFile( 'getProperty', 'id' );
			});

			ls.ajax.load( url, $.extend( true, {}, { ids: ids }, params || {} ), function( response ) {
				if ( response.bStateError ) {
					ls.msg.error( response.sMsgTitle, response.sMsg );
				} else {
					this.option( 'editor' ).lsEditor( 'insert', response.sTextResult );
					this.element.lsModal( 'hide' );
				}
			}.bind( this ));
		},

		/**
		 * 
		 */
		activateInfoBlock: function( file ) {
			this.elements.blocks.hide();

			var block = this.elements.blocks.filter( '[data-type=' + this.getActiveTabName() + ']' ).show();

			// Показываем блок настроек только для активного типа файла
			this.elements.blocks
				.filter( '[data-filetype]' )
				.filter( ':not([data-filetype=' + file.lsUploaderFile( 'getProperty', 'type' ) + '])' )
				.hide();

			// Обновляем настройки
			if ( file.lsUploaderFile( 'getProperty', 'type' ) == '1' ) {
				var sizes = block.find( 'select[name=size]' );

				sizes.find( 'option:not([value=original])' ).remove();
				sizes.append($.map( file.data('mediaImageSizes'), function ( v, k ) {
					// Расчитываем пропорциональную высоту изображения
					var height = v.h || parseInt( v.w * file.lsUploaderFile( 'getProperty', 'height' ) / file.lsUploaderFile( 'getProperty', 'width' ) );

					return '<option value="' + v.w + 'x' + ( v.h ? v.h : '' ) + ( v.crop ? 'crop' : '' ) + '">' + v.w + ' × ' + height + '</option>';
				}).join( '' ));
			}

			// TODO: Add hook
		},

		/**
		 * 
		 */
		getActiveTabName: function() {
			return this.elements.tabs.filter( '.active' ).eq( 0 ).data( 'media-name' );
		},

		//
		// INSERT FROM URL
		//

		/**
		 * 
		 */
		onUrlTypeChange: function ( event ) {
			this.elements.url.blocks.hide();
			this.elements.url.blocks.filter( '[data-filetype=' + this.elements.url.type.val() + ']' ).show();
			this.elements.url.url.val( '' );
			this.elements.url.image_preview.hide().empty();
		},

		/**
		 * 
		 */
		onUrlChange: function ( event ) {
			var _this = this,
				url = this.elements.url.url.val();

			if ( this.elements.url.type.val() == 1 ) {
				$('<img />', {
					src: url,
					style: 'max-width: 50%',
					error: function () {
						_this.elements.url.image_preview.hide().empty();
					},
					load: function () {
						_this.elements.url.image_preview.show().html( $( this ) );
					}
				});
			}
		},

		/**
		 * 
		 */
		urlInsert: function ( upload ) {
			var upload = upload || false,
				params = $.extend(
					{},
					{ upload: upload },
					this.elements.url.form.serializeJSON(),
					this.elements.url.blocks.filter( ':visible' ).find('form').serializeJSON(),
					this.elements.uploader.lsUploader( 'option', 'params' )
				);

			ls.ajax.load( this.option( 'urls.url_upload' ), params, function ( response ) {
				if ( response.bStateError ) {
					ls.msg.error( response.sMsgTitle, response.sMsg );
				} else {
					this.option( 'editor' ).lsEditor( 'insert', response.sText );
					this.element.lsModal( 'hide' );
					this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'load' );
				}
			}.bind( this ), {
				// TODO: Fix validation
				validate: false,
				submitButton: this.elements.url[ upload ? 'submit_upload' : 'submit_insert' ]
			});
		}
	});
})(jQuery);