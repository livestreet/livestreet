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

    $.widget( "livestreet.lsMedia", $.livestreet.lsComponent, {
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
                nav: '.js-media-nav',
                uploader: '.js-media-uploader',
                block: '.js-media-info-block',
                insert_submit: '.js-media-insert-submit',
                photoset_submit: '.js-media-photoset-submit',
                url: {
                    form: '.js-media-url-form',
                    url: '.js-media-url-form-url',
                    block_container: '.js-media-url-settings-blocks',
                    submit_upload: '.js-media-url-submit-upload',
                    submit_insert: '.js-media-url-submit-insert',
                    image_preview: '.js-media-url-image-preview'
                }
            },

            uploader_options: {},

            params: {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            // Получаем редактор
            ! this.option( 'editor' ).length && this.option( 'editor', $( '#' + this.element.data( 'media-editor') ) );

            $.extend(this.elements, {
                blocks: this.element.find( this.option( 'selectors.uploader' ) + ' ' + this.option( 'selectors.block' ) ),
                url: {
                    form: this.element.find( this.option( 'selectors.url.form' ) ),
                    url: this.element.find( this.option( 'selectors.url.url' ) ),
                    block_container: this.element.find( this.option( 'selectors.url.block_container' ) ),
                    submit_upload: this.element.find( this.option( 'selectors.url.submit_upload' ) ),
                    submit_insert: this.element.find( this.option( 'selectors.url.submit_insert' ) ),
                    image_preview: this.element.find( this.option( 'selectors.url.image_preview' ) )
                }
            });

            this.elements.url.blocks = this.elements.url.block_container.find( this.option( 'selectors.block' ) );

            // Иниц-ия загрузчика
            this.elements.uploader.lsUploader( $.extend( {}, this.option( 'uploader_options' ), {
                autoload: false,
                params: {
                    security_ls_key: LIVESTREET_SECURITY_KEY
                },
                filebeforeactivate: this._onFileBeforeActivate.bind( this )
            }));

            this._list = this.elements.uploader.lsUploader( 'getElement', 'list' );
            this._originalTargetType = this.elements.uploader.lsUploader( 'option', 'params.target_type' );

            // Табы
            this.elements.nav.lsTabs({
                tabactivate: this._onTabActivate.bind( this )
            });

            // Иниц-ия модального окна
            this.element.lsModal({
                aftershow: this.reload.bind( this )
            });

            //
            // INSERT
            //

            this._on( this.elements.insert_submit, { click: '_onInsertSubmit' } );
            this._on( this.elements.photoset_submit, { click: '_onPhotosetSubmit' } );

            //
            // INSERT FROM URL
            //

            this._on( this.elements.url.type, { click: 'onUrlTypeChange' } );
            this._on( this.elements.url.url, { keyup: 'onUrlChange', change: 'onUrlChange' } );
            this._on( this.elements.url.submit_upload, { click: this.urlInsert.bind( this, true ) } );
            this._on( this.elements.url.submit_insert, { click: this.urlInsert.bind( this, false ) } );
        },

        /**
         * 
         */
        _onInsertSubmit: function( event ) {
            this.insertSelectedFiles( 'insert', this.getSettings() );
        },

        /**
         * 
         */
        _onPhotosetSubmit: function( event ) {
            this.insertSelectedFiles( 'photoset', this.getSettings() );
        },

        /**
         * 
         */
        _onFileBeforeActivate: function( event, data ) {
            this.activateInfoBlock( data.element );
        },

        /**
         * 
         */
        _onTabActivate: function( event, data ) {
            var type = data.element.data( 'media-name' );

            this.moveUploader( data );

            if ( type === 'photoset' ) {
                this._list.lsUploaderFileList( 'option', 'multiselect_ctrl', false );
                this.elements.uploader.lsUploader( 'filterFilesByType', [ '1' ] );
            }

            if ( type === 'url' ) {
                var url = this.elements.url.url.val();

                this.elements.url.submit_insert.prop( 'disabled', ! url );
                this.elements.url.submit_upload.prop( 'disabled', ! url );
            }
        },

        /**
         * Перемещение uploader'а из одного таба в другой
         */
        moveUploader: function( tab ) {
            this.resetUploader();

            // Перемещение
            if ( tab.element.hasClass( 'js-tab-show-gallery' ) ) {
                this.elements.uploader
                    .lsUploader( 'resetFilter' )
                    .lsUploader( 'unselectAll' )
                    .appendTo( this.getPaneContent( tab ) );
            }
        },

        /**
         * 
         */
        resetUploader: function() {
            this._list.lsUploaderFileList( 'option', 'params.target_type', this._originalTargetType );
            this._list.lsUploaderFileList( 'option', 'multiselect_ctrl', true );
        },

        /**
         * 
         */
        getPaneContent: function( tab ) {
            return tab.getPane().find( '.js-media-pane-content' );
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
         * 
         */
        insertSelectedFiles: function( url, params ) {
            this.insertFiles( url, params, this.elements.uploader.lsUploader( 'getSelectedFiles' ) );
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

            this._load( url, $.extend( true, {}, { ids: ids }, params || {} ), function( response ) {
                this.option( 'editor' ).lsEditor( 'insert', response.sTextResult );
                this.element.lsModal( 'hide' );
            });
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
        reload: function() {
            this.elements.uploader.lsUploader( 'reload' );
        },

        /**
         * 
         */
        getActiveTab: function() {
            return this.elements.nav.lsTabs( 'getActiveTab' );
        },

        /**
         * 
         */
        getActiveTabName: function() {
            return this.getActiveTab().data( 'media-name' );
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

            this.elements.url.submit_insert.prop( 'disabled', ! url );
            this.elements.url.submit_upload.prop( 'disabled', ! url );

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

            this._load( 'url_upload', params, function ( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( response.sMsgTitle, response.sMsg );
                } else {
                    this.option( 'editor' ).lsEditor( 'insert', response.sText );
                    this.element.lsModal( 'hide' );
                    this.reload();
                }
            }, {
                // TODO: Fix validation
                validate: false,
                submitButton: this.elements.url[ upload ? 'submit_upload' : 'submit_insert' ]
            });
        }
    });
})(jQuery);