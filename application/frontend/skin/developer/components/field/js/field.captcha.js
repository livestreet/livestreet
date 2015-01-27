/**
 * Captcha
 *
 * @module ls/captcha
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsCaptcha", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            name: null,
            url: aRouter.ajax + 'captcha/'
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function() {
            this._super();
            this.update();

            this._on({ click: 'update' });
        },

        /**
         * Получает url каптчи
         */
        getUrl: function () {
            return this.options.url + '?security_ls_key=' + LIVESTREET_SECURITY_KEY + '&n=' + Math.random() + '&name=' + this.options.name;
        },

        /**
         * Обновляет каптчу
         */
        update: function () {
            this.element.css('background-image', 'url(' + this.getUrl() + ')');
        }
    });
})(jQuery);