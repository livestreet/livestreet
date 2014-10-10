/**
 * comment
 *
 * @module ls/topic
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsTopic", {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
            },
            // Селекторы
            selectors: {
                vote: '.js-vote-topic',
                favourite: '.js-favourite-topic'
            }
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            // Избранное
            this.element.find( this.option( 'selectors.favourite' ) ).lsTopicFavourite();

            // Голосование за топик
            this.element.find( this.option( 'selectors.vote' ) ).vote({
                urls: {
                    vote: aRouter.ajax + 'vote/topic/',
                    info: aRouter.ajax + 'vote/get/info/topic'
                }
            });
        }
    });
})(jQuery);