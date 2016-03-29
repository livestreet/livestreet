/**
 * Кнопка "Вступить в блог"
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
                count: '.js-blog-users-count',
                text: null
            },
            // Классы
            classes : {
                active: 'ls-button--primary',
                loading: null
            },
            // Ajax параметры
            params : {},

            i18n: {
                join: '@blog.join.join',
                leave: '@blog.join.leave'
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

            if ( ! this.elements.text.length ) this.elements.text = this.element;

            this.option( 'params.blog_id', this.element.data( 'blog-id' ) );

            this._on({ click: 'onClick' });
        },

        /**
         * 
         */
        onClick: function( event ) {
            this.toggle();
            event.preventDefault();
        },

        /**
         * 
         */
        toggle: function() {
            this.element.addClass( this.option( 'classes.loading' ) );

            this._load('toggle', function( response ) {
                this.onToggle( response );

                this.element.removeClass( this.option( 'classes.loading' ) );
            }.bind( this ));
        },

        /**
         * 
         */
        onToggle: function( response ) {
            this.element.toggleClass( this.option( 'classes.active' ) );
            this.elements.text.text( this._i18n( response.bState ? 'leave' : 'join' ) );

            $( this.option( 'selectors.count' ) + '[data-blog-id=' + this.option( 'params.blog_id' ) + ']' ).text( response.iCountUser );
        }
    });
})(jQuery);