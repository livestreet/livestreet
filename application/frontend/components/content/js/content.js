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

    $.widget( "livestreet.lsContent", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                add: null,
                edit: null
            },

            // Ajax параметры
            params: {},

            callbacks: {
                beforeSubmit: null
            }
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

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
            $.extend( this.option( 'params' ), params || {} );

            this.option( 'callbacks' )['beforeSubmit'] && this.option( 'callbacks' )['beforeSubmit'].call(this);

            this._submit( this.action, this.element, function( response ) {
                if ( response.sUrlRedirect ) {
                    window.location.href = response.sUrlRedirect;
                }
            });
        }
    });
})(jQuery);