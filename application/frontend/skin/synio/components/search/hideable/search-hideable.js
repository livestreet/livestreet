/**
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsSearchHideable", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                toggle: '.js-search-hideable-toggle',
                input: 'input[type=text]'
            },

            // Классы
            classes : {
                open: 'open'
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

            this._on(this.elements.toggle, { click: '_onToggleClick' });

            this.document.on('click' + this.eventNamespace, function (event) {
                if ( this.isOpen()
                    && ! this.element.is(event.target)
                    && ! this.element.has(event.target).length ) this.hide();
            }.bind(this));
        },

        /**
         * 
         */
        _onToggleClick: function (event) {
            event.preventDefault();
            this.toggle();
        },

        /**
         * 
         */
        toggle: function () {
            this[ this.isOpen() ? 'hide' : 'show' ]();
        },

        /**
         * 
         */
        show: function () {
            this._addClass('open');
            this.elements.input.focus();
        },

        /**
         * 
         */
        hide: function () {
            this._removeClass('open');
            this.elements.input.val('');
        },

        /**
         * 
         */
        isOpen: function () {
            return this._hasClass('open');
        }
    });
})(jQuery);