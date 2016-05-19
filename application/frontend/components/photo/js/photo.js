/**
 * Photo
 *
 * @module ls/photo
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsPhoto", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                upload: null,
                remove: null,
                crop_photo: null,
                crop_avatar: null,
                save_photo: null,
                save_avatar: null,
                cancel_photo: null
            },
            use_avatar: true,
            crop_photo: {
            },
            crop_avatar: {
                aspectRatio: 1
            },
            // Селекторы
            selectors: {
                image: '.js-photo-image',
                action_upload: '.js-photo-actions-upload',
                action_upload_label: '.js-photo-actions-upload-label',
                action_upload_input: '.js-photo-actions-upload-input',
                action_crop_avatar: '.js-photo-actions-crop-avatar',
                action_remove: '.js-photo-actions-remove'
            },
            // Классы
            classes: {
                nophoto: 'ls-photo--nophoto'
            },
            // Параметры передаваемые в аякс запросах
            params: {}

            // Изменение аватара
            // changeavatar: function() {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            var _this = this;

            this.option( 'params.target_id', this.element.data( 'target-id' ) );

            this.elements.action_upload_input.on( 'change' + this.eventNamespace, function () {
                _this.upload( $( this ) );
            });

            // Удаление
            this._on( this.elements.action_remove, { click: 'remove' } );

            // Изменение аватара
            if ( this.option( 'use_avatar' ) ) {
                this._on( this.elements.action_crop_avatar, { click: 'cropAvatar' } );
            }
        },

        /**
         * Удаление фото
         */
        remove: function() {
            this._load( 'remove', function( response ) {
                this._addClass( 'nophoto' );
                this.elements.image.attr( 'src', response.photo );
                this.elements.action_upload_label.text( response.upload_text );

                if ( this.option( 'use_avatar' ) ) {
                    this._trigger( 'changeavatar', null, [ this, response.avatars ] );
                }
            });
        },

        /**
         * Загрузка фото
         */
        upload: function( input ) {
            var form = $( '<form method="post" enctype="multipart/form-data"></form>' ).hide().appendTo( 'body' );
            input.clone( true ).insertAfter( input );
            input.appendTo( form );
            $( '<input type="hidden" name="target_id" value="' + this.option( 'params.target_id' ) + '" >').appendTo( form );

            this._submit( 'upload', form, function ( response ) {
                this.cropPhoto( response );
                form.remove();
            }, {
                lock: false
            });
        },

        /**
         * Показывает модальное кропа фото
         */
        cropPhoto: function( image ) {
            ls.modal.load(
                this.option('urls.crop_photo'),
                $.extend({}, this.option('params'), image),
                {
                    aftershow: function (event, data) {
                        this._initPhotoModal(data.element, image);
                    }.bind(this),
                    afterhide: function () {
                        this._load('cancel_photo');
                    }.bind(this)
                }
            );
        },

        /**
         * Иниц-ия модального окна с кропом фото
         */
        _initPhotoModal: function (modal, image) {
            modal.lsCropModal($.extend({
                urls: {
                    submit: this.option('urls.save_photo')
                },
                params: $.extend({}, this.option('params'), image),
                cropOptions: this.option('crop_photo')
            }));

            // Сохранение
            modal.on('lscropmodalsubmitted' + this.eventNamespace, function (event, data) {
                this._removeClass( 'nophoto' );
                this.elements.image.attr( 'src', data.response.photo + '?' + Math.random() );
                this.elements.action_upload_label.text( data.response.upload_text );

                if ( this.option( 'use_avatar' ) ) {
                    // TODO: Временный хак (модальное не показывается сразу после закрытия предыдущего окна)
                    setTimeout( this.cropAvatar.bind( this ), 300 );
                }
            }.bind(this));
        },

        /**
         * Показывает модальное кропа аватара
         */
        cropAvatar: function() {
            var image = {
                path: this.elements.image.attr( 'src' ),
                original_width: this.elements.image[0].naturalWidth,
                original_height: this.elements.image[0].naturalHeight,
                width: this.elements.image[0].naturalWidth,
                height: this.elements.image[0].naturalHeight
            };

            ls.modal.load(
                this.option('urls.crop_avatar'),
                $.extend({}, this.option('params'), image),
                {
                    aftershow: function (event, data) {
                        this._initAvatarModal(data.element, image);
                    }.bind(this)
                }
            );
        },

        /**
         * Иниц-ия модального окна с кропом аватары
         */
        _initAvatarModal: function (modal, image) {
            modal.lsCropModal($.extend({
                urls: {
                    submit: this.option('urls.save_avatar')
                },
                params: $.extend({}, this.option('params'), image),
                cropOptions: this.option('crop_avatar')
            }));

            // Сохранение
            modal.on('lscropmodalsubmitted' + this.eventNamespace, function (event, data) {
                this._trigger( 'changeavatar', null, [ this, data.response.avatars ] );
            }.bind(this));
        },
    });
})(jQuery);