/**
 * Кнопка добавления топика в избранное
 *
 * @module ls/topic/favourite
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsTopicFavourite", $.livestreet.lsFavourite, {
        /**
         * Дефолтные опции
         */
        options: {
            urls: {
                toggle: aRouter['ajax'] + 'favourite/topic/'
            },
            tags: null
        },

        /**
         * 
         */
        onToggleSuccess: function ( response ) {
            this._super( response );

            this.option( 'tags' ).lsTagsFavourite( 'setEditable', response.bState );
        }
    });
})(jQuery);