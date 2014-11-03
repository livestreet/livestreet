/**
 * Content
 *
 * @module ls/content
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsContent", {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                add: null,
                edit: null
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

            this.action = this.element.data( 'content-action' );

            this.element.on( 'submit' + this.eventNamespace, this.onSubmit.bind( this ) );
        },

        /**
         * Коллбэк вызываемый при отправке формы
         */
        onSubmit: function( event ) {
            this.submit();
            event.preventDefault();
        },

        /**
         * Отправка формы
         */
        submit: function( params ) {
            ls.ajax.submit( this.option( 'urls.' + this.action ), this.element, function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( null, response.sMsg );
                } else {
                    if ( response.sUrlRedirect ) {
                        window.location.href = response.sUrlRedirect;
                    }
                }
            }, {
                params: params || {}
            });
        }
    });
})(jQuery);