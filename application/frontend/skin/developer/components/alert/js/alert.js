/**
 * Alert
 *
 * @module ls/alert
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.lsAlert", $.livestreet.lsComponent, {
    /**
     * Дефолтные опции
     */
    options: {
        // Селекторы
        selectors: {
            // Кнопка закрывающая уведомление
            close: '.js-alert-close'
        },
        // Анимация при скрытии
        hide: {
            effect: 'fade',
            duration: 200
        },
        // Анимация при показывании
        show: {
            effect: 'fade',
            duration: 200
        }
    },

    /**
     * Конструктор
     *
     * @constructor
     * @private
     */
    _create: function() {
        this._super();

        this._on( this.elements.close, { 'click': this.hide } );
    },

    /**
     * Скрывает уведомление
     */
    hide: function () {
        this.element.hide( this.option( 'hide' ) );
    },

    /**
     * Показывает уведомление
     */
    show: function () {
        this.element.show( this.option( 'show' ) );
    }
});