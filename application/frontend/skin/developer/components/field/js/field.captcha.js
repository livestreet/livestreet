/**
 * Captcha
 *
 * @module captcha
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.captcha", {
        /**
         * Дефолтные опции
         */
        options: {
            name: '',
			url: aRouter.ajax + 'captcha/'
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function() {
			this.options = $.extend({}, this.options, ls.utils.getDataOptions(this.element, 'captcha'));

            this._on({ click: this.update });

            this.update();
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