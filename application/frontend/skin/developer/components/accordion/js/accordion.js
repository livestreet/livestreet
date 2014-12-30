/**
 * Accordion
 *
 * @module ls/accordion
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsAccordion", {
        /**
         * Дефолтные опции
         */
        options: {},

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this.element.accordion( this.options );
        }
    });
})(jQuery);