/**
 * Форма добавления блога
 *
 * @module ls/blog/add
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsBlogAdd", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                type: '.js-blog-add-type',
                type_note: '.js-blog-add-field-type .js-field-note'
            },
            i18n: {
                type_open: '@blog.add.fields.type.note_open',
                type_closed: '@blog.add.fields.type.note_closed'
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

            this._super();

            // Подгрузка информации о выбранном типе блога при создании блога
            this.elements.type.on( 'change' + this.eventNamespace, function () {
                _this.setTypeNote( $( this ).val() );
            });
        },

        /**
         * 
         */
        setTypeNote: function( type ) {
            this.elements.type_note.text( this._i18n( 'type_' + type ) );
        }
    });
})(jQuery);