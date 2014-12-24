/**
 * Image Ajax
 *
 * @module ls/field/image-ajax
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsFieldImageAjax", {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                create_preview: aRouter['ajax'] + 'media/create-preview-file/',
                remove_preview: aRouter['ajax'] + 'media/remove-preview-file/',
                load_preview: aRouter['ajax'] + 'media/load-preview-items/',
            },
            // Селекторы
            selectors: {
                show_modal: '.js-field-image-ajax-show-modal',
                remove: '.js-field-image-ajax-remove',
                image: '.js-field-image-ajax-image',
                modal: '.js-field-image-ajax-modal',
                uploader: '.js-uploader-modal',
                choose: '.js-uploader-modal-choose',
            }
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            var _this = this;

            this.options.params = ls.utils.getDataOptions( this.element, 'param' );

            this.elements = {
                show_modal: this.element.find( this.option( 'selectors.show_modal' ) ),
                remove: this.element.find( this.option( 'selectors.remove' ) ),
                image: this.element.find( this.option( 'selectors.image' ) ),
                modal: this.element.find( this.option( 'selectors.modal' ) ),
                uploader: this.element.find( this.option( 'selectors.modal' ) + ' ' + this.option( 'selectors.uploader' ) ),
                choose: this.element.find( this.option( 'selectors.modal' ) + ' ' + this.option( 'selectors.choose' ) )
            };

            this.elements.modal.lsModal({
                aftershow: function () {
                    _this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'load' );
                }
            });

            this.elements.uploader.lsUploader({
                autoload: false,
                params: $.extend( {}, { security_ls_key: LIVESTREET_SECURITY_KEY }, this.options.params )
            });

            this.elements.show_modal.on( 'click' + this.eventNamespace, function () {
                _this.elements.modal.lsModal( 'show' );
            });

            this.elements.remove.on( 'click' + this.eventNamespace, function () {
                _this.remove();
            });

            this.elements.choose.on( 'click' + this.eventNamespace, function () {
                _this.insertFiles();
            });

            this.options.params.target_tmp = this.elements.uploader.lsUploader( 'option', 'params.target_tmp' );
        },

        /**
         * 
         */
        insertFiles: function() {
            var id = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' ).eq( 0 ).lsUploaderFile( 'getProperty', 'id' );

            if ( ! id ) return;

            this.elements.image.show().addClass('loading');
            this.elements.modal.lsModal( 'hide' );

            ls.ajax.load( this.option( 'urls.create_preview' ), $.extend( {}, this.options.params, { id: id } ), function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( response.sMsgTitle, response.sMsg );
                } else {
                    this.options.params.id = id;
                    this.loadPreview();
                    this.elements.show_modal.hide();
                    this.elements.remove.show();
                }
            }.bind( this ));
        },

        /**
         * 
         */
        remove: function() {
            ls.ajax.load( this.option( 'urls.remove_preview' ), this.options.params, function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( response.sMsgTitle, response.sMsg );
                } else {
                    this.elements.image.empty().hide();
                    this.elements.remove.hide();
                    this.elements.show_modal.show();
                }
            }.bind( this ));
        },

        /**
         * 
         */
        loadPreview: function() {
            ls.ajax.load( this.option( 'urls.load_preview' ), this.options.params, function( response ) {
                this.elements.image.removeClass('loading');

                if ( response.bStateError ) {
                    ls.msg.error( response.sMsgTitle, response.sMsg );
                    this.elements.image.hide();
                } else {
                    this.elements.image.show();
                    this.elements.image.html( $.trim( response.sTemplatePreview ) );
                }
            }.bind( this ));
        },
    });
})(jQuery);