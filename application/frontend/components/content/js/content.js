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

            this.action = this.element.data( 'content-action' );

            this._on({ submit: 'onSubmit' });
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

            this._trigger( 'beforesubmit', null, this );

            this._submit( this.action, this.element, function( response ) {
                this._trigger( 'aftersubmit', null, this );

                if ( response.sUrlRedirect ) {
                    window.location.href = response.sUrlRedirect;
                }
            });
        }
    });
})(jQuery);