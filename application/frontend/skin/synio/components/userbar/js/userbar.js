/**
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsUserbar", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
            },

            // Селекторы
            selectors: {
                userNav: '.js-userbar-user-nav'
            },

            // Классы
            classes : {
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

            this.elements.userNav.lsDropdown({
                selectors: {
                    toggle: '.js-userbar-user-nav-toggle',
                    menu: '.js-userbar-user-nav-menu'
                },
                position: {
                    my: "right top",
                    at: "right bottom",
                    collision: "flipfit flip"
                },
            });
        }
    });
})(jQuery);