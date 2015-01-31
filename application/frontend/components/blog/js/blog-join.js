/**
 * Join blog
 *
 * @module ls/blog/join
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsBlogJoin", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                toggle: null
            },
            // Селекторы
            selectors: {
                count: '.js-blog-users-count'
            },
            // Классы
            classes : {
                active: 'button--primary'
            },
            // Ajax параметры
            params : {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this.option( 'params.blog_id', this.element.data( 'blog-id' ) );

            this._on({ click: 'toggle' });
        },

        /**
         * 
         */
        toggle: function() {
            this.element.addClass( ls.options.classes.states.loading );

            this._load('toggle', function( response ) {
                this.onToggle( response );

                this.element.removeClass( ls.options.classes.states.loading );
            }.bind( this ));
        },

        /**
         * 
         */
        onToggle: function( response ) {
            this.element
                .text( ls.lang.get( response.bState ? 'blog.join.leave' : 'blog.join.join' ) )
                .toggleClass( this.option( 'classes.active' ) );

            $( this.option( 'selectors.count' ) + '[data-blog-id=' + this.option( 'params.blog_id' ) + ']' ).text( response.iCountUser );
        }
    });
})(jQuery);