/**
 * User photo
 *
 * @module ls/user/photo
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 *
 * TODO: Вынести опции кропа для фото и аватары в общие опции
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsUserPhoto", {
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
            // Селекторы
            selectors: {
                image: '.js-user-photo-image',
                actions: {
                    upload: '.js-user-photo-actions-upload',
                    upload_label: '.js-user-photo-actions-upload-label',
                    upload_input: '.js-user-photo-actions-upload-input',
                    crop_avatar: '.js-user-photo-actions-crop-avatar',
                    remove: '.js-user-photo-actions-remove',
                },
            },
            // Классы
            classes: {
                nophoto: 'user-photo--nophoto'
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

            this.option( 'params.user_id', this.element.data( 'user-id' ) );

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

            this.elements.actions.crop_avatar.on( 'click' + this.eventNamespace, this.cropAvatar.bind( this ) );
            this.elements.actions.remove.on( 'click' + this.eventNamespace, this.remove.bind( this ) );
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
                    this.elements.actions.remove.hide();
                    this.elements.actions.crop_avatar.hide();

                    this._trigger( 'changeavatar', null, [ this, response.avatars ] );
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
            $( '<input type="hidden" name="user_id" value="' + this.option( 'params.user_id' ) + '" >').appendTo( form );

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
            this.showModal( image, false, {
                crop_params : {
                    minSize: [ 370, 370 ]
                },
                save_params : this.option( 'params' ),
                crop_url : this.option( 'urls.crop_photo' ),
                save_url : this.option( 'urls.save_photo' ),
                save_callback : function( response, modal, image ) {
                    this.element.removeClass( this.option( 'classes.nophoto' ) );
                    this.elements.image.attr( 'src', response.photo );
                    this.elements.actions.upload_label.text( response.upload_text );
                    this.elements.actions.remove.show();
                    this.elements.actions.crop_avatar.show();

                    // TODO: Временный хак (модальное не показывается сразу после закрытия предыдущего окна)
                    setTimeout( this.cropAvatar.bind( this ), 300);
                },
                modal_close_callback : function( event, modal ) {
                    ls.ajax.load( this.option( 'urls.cancel_photo' ), this.option( 'params' ) );
                }
            });
        },

        /**
         * Показывает модальное кропа аватара
         */
        cropAvatar: function() {
            var photo = $('.js-user-photo-image');
            var image = {
                path: photo.attr( 'src' ),
                // TODO: IE8 naturalWidth naturalHeight
                original_width: photo[0].naturalWidth,
                original_height: photo[0].naturalHeight,
                width: photo[0].naturalWidth,
                height: photo[0].naturalHeight
            };

            this.showModal( image, true, {
                crop_params : {
                    minSize: [ 100, 100 ],
                    aspectRatio: 1
                },
                save_callback : function( response, modal, image ) {
                    this._trigger( 'changeavatar', null, [ this, response.avatars ] );
                },
                save_params : this.option( 'params' ),
                crop_url : this.option( 'urls.crop_avatar' ),
                save_url : this.option( 'urls.save_avatar' )
            });
        },

        /**
         * Показывает модальное кропа
         *
         * TODO: Перенести в компонент crop
         */
        showModal: function( image, usePreview, params ) {
            var _this = this;

            ls.modal.load( params.crop_url, {
                original_width: image.original_width,
                original_height: image.original_height,
                width: image.width,
                height: image.height,
                image_src: image.path,
                use_preview: usePreview
            }, {
                aftershow: function( e, modal ) {
                    var crop   = modal.element.find('.js-crop').lsCrop( params.crop_params );
                    var submit = modal.element.find('.js-crop-submit');
                    var image  = crop.lsCrop( 'getImage' );

                    submit.on( 'click', function() {
                        var paramsRequest = $.extend({}, {
                            size: crop.lsCrop( 'getSelection' ),
                            canvas_width: image.innerWidth()
                        }, params.save_params || {});

                        ls.ajax.load( params.save_url, paramsRequest, function( response ) {
                            if ( response.bStateError ) {
                                ls.msg.error( null, response.sMsg );
                            } else {
                                modal.hide();

                                if ( $.isFunction( params.save_callback ) ) {
                                    params.save_callback.call( _this, response, modal, image );
                                }
                            }
                        });
                    });
                },
                afterhide: function( event, modal ) {
                    if ( $.isFunction( params.modal_close_callback ) ) {
                        params.modal_close_callback.call( _this, event, modal );
                    }
                },
                center: false
            });
        }
    });
})(jQuery);