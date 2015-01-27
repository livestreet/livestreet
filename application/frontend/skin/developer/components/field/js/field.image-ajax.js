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

    $.widget( "livestreet.lsFieldImageAjax", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                create: aRouter['ajax'] + 'media/create-preview-file/',
                remove: aRouter['ajax'] + 'media/remove-preview-file/',
                load: aRouter['ajax'] + 'media/load-preview-items/'
            },
            // Селекторы
            selectors: {
                show_modal: '.js-field-image-ajax-show-modal',
                remove: '.js-field-image-ajax-remove',
                image: '.js-field-image-ajax-image',
                modal: '.js-field-image-ajax-modal',
                uploader: '.js-field-image-ajax-modal .js-uploader-modal',
                choose: '.js-field-image-ajax-modal .js-uploader-modal-choose'
            },
            params: {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            var _this = this;

            this._super();

            this.elements.modal.lsModal({
                aftershow: function () {
                    _this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'load' );
                    // т.к. генерация происходит после инициализации
                    _this._setParam( 'target_tmp', _this.elements.uploader.lsUploader( 'option', 'params.target_tmp' ) );
                }
            });

            this.elements.uploader.lsUploader({
                autoload: false,
                params: $.extend( {}, { security_ls_key: LIVESTREET_SECURITY_KEY }, this.options.params )
            });

            this.elements.show_modal.on( 'click' + this.eventNamespace, function () {
                _this.elements.modal.lsModal( 'show' );
            });

            this.elements.remove.on( 'click' + this.eventNamespace, this.remove.bind( this ) );
            this.elements.choose.on( 'click' + this.eventNamespace, this.createPreview.bind( this ) );

        },

        /**
         * Создает превью
         */
        createPreview: function() {
            var id = this.elements.uploader.lsUploader( 'getElement', 'list' ).lsUploaderFileList( 'getSelectedFiles' ).eq( 0 ).lsUploaderFile( 'getProperty', 'id' );

            if ( ! id ) return;

            this.elements.image.show().addClass( 'loading' );
            this.elements.modal.lsModal( 'hide' );

            this._load( 'create', { 'id': id }, function( response ) {
                this.load();
                this.elements.show_modal.hide();
                this.elements.remove.show();
            });
        },

        /**
         * Удаление превью
         */
        remove: function() {
            this._load( 'remove', function( response ) {
                this.elements.image.empty().hide();
                this.elements.remove.hide();
                this.elements.show_modal.show();
            });
        },

        /**
         * Подгружает созданное превью
         */
        load: function() {
            this._load( 'load', function( response ) {
                this.elements.image.removeClass( 'loading' ).show().html( $.trim( response.sTemplatePreview ) );
            });
        }
    });
})(jQuery);