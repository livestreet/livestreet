/**
 * Информация о блоге
 *
 * @module ls/blog/info
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsBlogInfo", {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                load: null
            },
            // Селекторы
            selectors: {
                select: null
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

            this.elements = {
                select: $( this.option( 'selectors.select' ) )
            };

            this.elements.select.on( 'change' + this.eventNamespace, function () {
                _this.load( _this.elements.select.val() );
            });

            this.load( this.elements.select.val() );
        },

        /**
         * 
         */
        load: function( blogId ) {
            if ( ! blogId ) return;

            this.element.empty().addClass( ls.options.classes.states.loading );

            ls.ajax.load( this.option( 'urls.load' ), { blog_id: blogId }, function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( null, response.sMsg );
                } else {
                    this.element.removeClass( ls.options.classes.states.loading ).html( response.text );
                }
            }.bind( this ));
        }
    });
})(jQuery);