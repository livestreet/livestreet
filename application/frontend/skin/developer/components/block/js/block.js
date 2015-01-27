/**
 * Block
 *
 * @module ls/block
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsBlock", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                tabs: '.js-tabs-block',
                update: '.js-block-update-tabs',
                pane_container: '[data-type=tab-panes]'
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

            // Сохраняем высоту блока при переключении табов
            this.elements.tabs.lsTabs().lsTabs( 'getTabs' ).lsTab( 'option', {
                beforeactivate: function ( e, data ) {
                    this.elements.pane_container.css( 'height', this.elements.pane_container.height() );
                }.bind( this ),
                activate: function ( e, data ) {
                    this.elements.pane_container.css( 'height', 'auto' );
                }.bind( this )
            });
        }
    });
})(jQuery);