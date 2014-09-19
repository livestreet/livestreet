/**
 * Media
 *
 * @module ls/media
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsMedia", {
		/**
		 * Дефолтные опции
		 */
		options: {
			editor: null,

			// Ссылки
			urls: {
				insert: aRouter.ajax + 'media/submit-insert/',
				photoset: aRouter.ajax + 'media/submit-create-photoset'
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
				}
			},

			// Классы
			classes : {
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

			// FIXME: Временная заглушка
			this.option( 'editor', $( '.js-editor' ).eq( 0 ) );

			this.elements = {
				uploader: this.element.find( this.option( 'selectors.uploader' ) ),
				blocks: this.element.find( this.option( 'selectors.block' ) ),
				insert: {
					submit: this.element.find( this.option( 'selectors.insert.submit' ) )
				},
				photoset: {
					submit: this.element.find( this.option( 'selectors.photoset.submit' ) )
				}
			};

			this.activateInfoBlock( 'insert' );

			// Иниц-ия загрузчика
			this.elements.uploader.lsUploader( $.extend( {}, this.option( 'uploader_options' ), {
				autoload: true,
				params: {
					security_ls_key: LIVESTREET_SECURITY_KEY
				},
				file_options: {
					beforeactivate: function ( event, context ) {
						//_this.updateInsertSettings( context.element );
					}
				}
			}));

			// Перемещение галереи из одного таба в другой
			$( '.js-tab-show-gallery' ).on( 'tabactivate', function( event, tab ) {
				this.elements.uploader.appendTo( _this.element.find( '#' + tab.options.target + ' .js-media-pane-content' ) );
				this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'clearSelected' );
				this.activateInfoBlock( $( event.target ).data( 'mediaMode' ) );
			}.bind(this));

			//
			// INSERT
			//

			this.elements.insert.submit.on( 'click' + this.eventNamespace, function () {
				var files = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' );

				this.insertFiles( this.option( 'urls.insert' ), {}, files );
			}.bind( this ));

			//
			// PHOTOSET
			//

			this.elements.photoset.submit.on( 'click' + this.eventNamespace, function () {
				var files = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' );

				this.insertFiles( this.option( 'urls.photoset' ), {}, files );
			}.bind( this ));
		},

		/**
		 * Вставляет выделенные файлы в редактор
		 */
		insertFiles: function( url, params, files ) {
			if ( ! files.length ) return;

			// Формируем список ID элементов
			var ids = $.map( files, function ( file ) {
				return $( file ).lsUploaderFile( 'getProperty', 'id' );
			});

			ls.ajax.load( url, $.extend( true, {}, { ids: ids }, params || {} ), function( response ) {
				if ( response.bStateError ) {
					ls.msg.error( response.sMsgTitle, response.sMsg );
				} else {
					this.option( 'editor' ).lsEditor( 'insert', response.sTextResult );
					// this.elements.modal.modal( 'hide' );
				}
			}.bind( this ));
		},

		/**
		 * Устанавливает текущий режим вставки медиа файлов
		 */
		activateInfoBlock: function( name ) {
			this.elements.blocks.hide();
			this.elements.blocks.filter( '[data-type=' + name + ']' ).show();
		}
	});
})(jQuery);