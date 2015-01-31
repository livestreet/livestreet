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

    $.widget( "livestreet.lsPhoto", {
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
                cancel_photo: null,
            },
            use_avatar: true,
            crop_photo: {
                minSize: [ 370, 370 ],
                aspectRatio: 0,
                usePreview: false
            },
            crop_avatar: {
                minSize: [ 100, 100 ],
                aspectRatio: 1,
                usePreview: true
            },
            // Селекторы
            selectors: {
                image: '.js-photo-image',
                actions: {
                    upload: '.js-photo-actions-upload',
                    upload_label: '.js-photo-actions-upload-label',
                    upload_input: '.js-photo-actions-upload-input',
                    crop_avatar: '.js-photo-actions-crop-avatar',
                    remove: '.js-photo-actions-remove',
                },
            },
            // Классы
            classes: {
                nophoto: 'photo--nophoto'
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
            var _this = this;

            this.option( 'params.target_id', this.element.data( 'target-id' ) );

            this.elements = {
                image: this.element.find( this.option( 'selectors.image' ) ),
                actions: {
                    upload: this.element.find( this.option( 'selectors.actions.upload' ) ),
                    upload_label: this.element.find( this.option( 'selectors.actions.upload_label' ) ),
                    upload_input: this.element.find( this.option( 'selectors.actions.upload_input' ) ),
                    crop_avatar: this.element.find( this.option( 'selectors.actions.crop_avatar' ) ),
                    remove: this.element.find( this.option( 'selectors.actions.remove' ) ),
                }
            };

            this.elements.actions.upload_input.on( 'change' + this.eventNamespace, function () {
                _this.upload( $( this ) );
            });

            this.elements.actions.remove.on( 'click' + this.eventNamespace, this.remove.bind( this ) );

            if ( this.option( 'use_avatar' ) ) {
                this.elements.actions.crop_avatar.on( 'click' + this.eventNamespace, this.cropAvatar.bind( this ) );
            }
        },

        /**
         * Удаление фото
         */
        remove: function() {
            ls.ajax.load( this.option( 'urls.remove' ), this.option( 'params' ), function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( null, response.sMsg );
                } else {
                    this.element.addClass( this.option( 'classes.nophoto' ) );
                    this.elements.image.attr( 'src', response.photo );
                    this.elements.actions.upload_label.text( response.upload_text );

                    if ( this.option( 'use_avatar' ) ) {
                        this._trigger( 'changeavatar', null, [ this, response.avatars ] );
                    }
                }
            }.bind( this ));
        },

        /**
         * Загрузка фото
         */
        upload: function( input ) {
            var form = $( '<form method="post" enctype="multipart/form-data"></form>' ).hide().appendTo( 'body' );
            input.clone( true ).insertAfter( input );
            input.appendTo( form );
            $( '<input type="hidden" name="target_id" value="' + this.option( 'params.target_id' ) + '" >').appendTo( form );

            ls.ajax.submit( this.option( 'urls.upload' ), form, function ( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( response.sMsgTitle, response.sMsg );
                } else {
                    this.cropPhoto( response );
                }

                form.remove();
            }.bind( this ));
        },

        /**
         * Показывает модальное кропа фото
         */
        cropPhoto: function( image ) {
            ls.cropModal.show({
                urls: {
                    modal: this.option( 'urls.crop_photo' ),
                    save: this.option( 'urls.save_photo' )
                },
                params: $.extend( {}, this.option( 'params' ), image, { usePreview: this.option( 'crop_photo.usePreview' ) } ),
                crop_options: this.option( 'crop_photo' ),
                aftersave: function( response, modal, image ) {
                    this.element.removeClass( this.option( 'classes.nophoto' ) );
                    this.elements.image.attr( 'src', response.photo + '?' + Math.random() );
                    this.elements.actions.upload_label.text( response.upload_text );

                    if ( this.option( 'use_avatar' ) ) {
                        // TODO: Временный хак (модальное не показывается сразу после закрытия предыдущего окна)
                        setTimeout( this.cropAvatar.bind( this ), 300 );
                    }
                }.bind( this ),
                afterhide: function( event, modal ) {
                    ls.ajax.load( this.option( 'urls.cancel_photo' ), this.option( 'params' ) );
                }.bind( this )
            });
        },

        /**
         * Показывает модальное кропа аватара
         */
        cropAvatar: function() {
            var image = {
                path: this.elements.image.attr( 'src' ),
                // TODO: IE8 naturalWidth naturalHeight
                original_width: this.elements.image[0].naturalWidth,
                original_height: this.elements.image[0].naturalHeight,
                width: this.elements.image[0].naturalWidth,
                height: this.elements.image[0].naturalHeight
            };

            ls.cropModal.show({
                urls: {
                    modal: this.option( 'urls.crop_avatar' ),
                    save: this.option( 'urls.save_avatar' )
                },
                params: $.extend( {}, this.option( 'params' ), image, { usePreview: this.option( 'crop_avatar.usePreview' ) } ),
                crop_options: this.option( 'crop_avatar' ),
                aftersave: function( response, modal, image ) {
                    this._trigger( 'changeavatar', null, [ this, response.avatars ] );
                }.bind( this )
            });
        }
    });
})(jQuery);