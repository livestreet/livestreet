/**
 * Избранное
 *
 * @module ls/favourite
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */


(function($) {
    "use strict";

    $.widget( "livestreet.lsFavourite", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                // Добавить/удалить из избранного
                toggle: null
            },

            // Селекторы
            selectors: {
                // Кнопка добавить/удалить из избранного
                toggle: '.js-favourite-toggle',
                // Счетчик
                count: '.js-favourite-count'
            },

            // Классы
            classes: {
                // Добавлено в избранное
                added: 'favourite--added',
                // Кол-во добавивших в избранное больше нуля
                has_counter: 'favourite--has-counter'
            },

            // Параметры отправляемые при каждом аякс запросе
            params: {}

            // Коллбэки

            // После успешного изменения состояния
            // aftertogglesuccess: null
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            // Обработка кликов по кнопкам голосования
            this._on({ click: 'toggle' });
        },

        /**
         * Добавить/удалить из избранного
         */
        toggle: function() {
            this.options.params.type = ! this._hasClass( 'added' );

            this._load( 'toggle', this.onToggleSuccess );
        },

        /**
         * 
         */
        onToggleSuccess: function( response ) {
            // Обновляем состояние
            this._removeClass( 'added' );

            if ( response.bState ) {
                this._addClass( 'added' ).attr( 'title', ls.lang.get( 'favourite.remove' ) );
            } else {
                this.element.attr( 'title', ls.lang.get( 'favourite.add' ) );
            }

            // Обновляем счетчик
            if ( this.elements.count ) {
                if ( response.iCount > 0 ) {
                    this._addClass( 'has_counter' );
                    this.elements.count.show().text( response.iCount );
                } else {
                    this._removeClass( 'has_counter' );
                }
            }

            this._trigger( 'aftertogglesuccess', null, { context: this, response: response } );
        }
    });
})(jQuery);