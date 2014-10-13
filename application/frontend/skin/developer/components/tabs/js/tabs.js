/**
 * Tabs
 *
 * @module ls/tabs
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsTabs", {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                tab: '[data-tab-type=tab]',
                pane: '[data-tab-type=tab-pane]'
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
                tabs: this.element.find( this.option( 'selectors.tab' ) ),
                panes: this.element.find( this.option( 'selectors.pane' ) ),
            };

            this.elements.tabs.lsTab({
                tabs: this.element
            });
        },

        /**
         * Get tabs
         */
        getTabs: function() {
            return this.elements.tabs;
        },

        /**
         * Get panes
         */
        getPanes: function() {
            return this.elements.panes;
        },

        /**
         * Get active tab
         */
        getActiveTab: function() {
            return this.elements.tabs.filter( '.active' );
        }
    });
})(jQuery);