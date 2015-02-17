/**
 * Кнопка подгрузки и навигации по новым комментариям
 *
 * @module ls/toolbar/comments
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */


(function($) {
    "use strict";

    $.widget( "livestreet.lsCommentsToolbar", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Блок с комментариями
            comments: '.js-comments',

            // Селекторы
            selectors: {
                // Кнопка обновления
                update: '.js-toolbar-comments-update',

                // Счетчик новых комментариев
                counter: '.js-toolbar-comments-count'
            },

            classes: {
                active: 'active'
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

            this.options.comments = typeof target === 'string' ? $( this.options.comments ) : this.options.comments;

            // Обновляем счетчик новых комментариев
            this.updateCounter();

            //
            // События
            //

            // Обновление
            this._on( this.elements.update, { click: 'update' } );

            // Прокрутка к следующему новому комментарию
            this._on( this.elements.counter, { click: 'scroll' } );
        },

        /**
         * Обновление счетчика
         *
         * @param {Number} count (optional) Кол-во новых комментариев
         */
        updateCounter: function(count) {
            count = typeof count === 'undefined' ? this.options.comments.lsComments( 'getCommentsNew' ).length : count;

            if ( count ) {
                this.showCounter();
                this.elements.counter.text( count );
            } else {
                this.hideCounter();
            }
        },

        /**
         * Обновление
         */
        update: function() {
            this._addClass( this.elements.update, 'active' );

            this.options.comments.lsComments( 'load', false, false, function () {
                this.updateCounter();
                this._removeClass( this.elements.update, 'active' );
            }.bind( this ));
        },

        /**
         * Показывает счетчик
         */
        showCounter: function() {
            if ( this.elements.counter.is( ':visible' ) ) return;

            this.element.append( this.elements.counter.show() );
        },

        /**
         * Скрывает счетчик
         */
        hideCounter: function() {
            this.elements.counter.hide().detach();
        },

        /**
         * Прокрутка к следующему новому комментарию
         */
        scroll: function() {
            var commentsNew = this.options.comments.lsComments('getCommentsNew'),
                comment = commentsNew.eq(0);

            if ( ! commentsNew.length ) return;

            // Если новый комментарий находится в свернутой ветке разворачиваем все ветки
            if ( ! comment.is(':visible') ) this.options.comments.lsComments('unfoldAll');

            // Обновляем счетчик новых комментариев
            this.updateCounter( commentsNew.length - 1 );

            comment.lsComment( 'notNew' );
            this.options.comments.lsComments('scrollToComment', comment);
        }
    });
})(jQuery);