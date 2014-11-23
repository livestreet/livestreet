/**
 * Модальное окно с кропом изображения
 *
 * @module ls/crop/modal
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.cropModal = (function ($) {
    "use strict";

    var _defaults = {
        urls: {
            modal: null,
            save: null
        },
        params: {
            usePreview: false
        },
        selectors: {
            crop: '.js-crop',
            submit: '.js-crop-submit'
        },
        crop_options: {},
        aftersave: null,
        afterhide: null
    };

    /**
     * Показывает модальное окно
     */
    this.show = function( options ) {
        var options = $.extend( {}, _defaults, options );

        ls.modal.load( options.urls.modal, options.params, {
            aftershow: function( event, modal ) {
                var crop   = modal.element.find( options.selectors.crop ).lsCrop( options.crop_options );
                var submit = modal.element.find( options.selectors.submit );
                var image  = crop.lsCrop( 'getImage' );

                submit.on( 'click', function() {
                    var paramsRequest = $.extend({}, {
                        size: crop.lsCrop( 'getSelection' ),
                        canvas_width: image.innerWidth()
                    }, options.params || {});

                    ls.ajax.load( options.urls.save, paramsRequest, function( response ) {
                        if ( response.bStateError ) {
                            ls.msg.error( null, response.sMsg );
                        } else {
                            modal.hide();

                            if ( $.isFunction( options.aftersave ) ) {
                                options.aftersave( response, modal, image );
                            }
                        }
                    });
                });
            },
            afterhide: function( event, modal ) {
                if ( $.isFunction( options.afterhide ) ) {
                    options.afterhide( event, modal );
                }
            },
            center: false
        });
    };

    return this;
}).call( ls.cropModal || {}, jQuery );