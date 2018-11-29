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

    $.widget( "livestreet.lsTopic", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                
            },

            // Селекторы
            selectors: {
                favourite: '.js-favourite-topic',
                tags: '.js-tags-favourite'
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

            // Избранное
            this.elements.favourite.lsTopicFavourite({
                tags: this.elements.tags
            });

            

            // Теги
            this.elements.tags.lsTagsFavourite({
                urls: {
                    save: aRouter['ajax'] + 'favourite/save-tags/'
                },
                params: {
                    target_type: 'topic'
                }
            });
        }
    });
})(jQuery);