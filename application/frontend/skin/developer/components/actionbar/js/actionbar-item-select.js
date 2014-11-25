/**
 * Actionbar Select button
 *
 * @module ls/actionbar-item-select
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsActionbarItemSelect", {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                target_item: '.js-actionbar-select-target-item'
            },
            // Классы
            classes : {
                target_selected: 'selected'
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

            this.element.lsDropdown();

            this.elements = {
                target_items: $( this.option( 'selectors.target_item') ),
                select_menu_items: this.element.lsDropdown( 'getMenu' ).find( 'li[data-select-item]' )
            };

            this.elements.select_menu_items.on( 'click' + this.eventNamespace, function ( event ) {
                var items = _this.elements.target_items.filter( $( this ).data( 'select-filter' ) || '*' );

                _this.elements.target_items
                    .removeClass( _this.option( 'classes.target_selected' ) )
                    .find( 'input[type=checkbox]' )
                    .prop( 'checked', false );

                items
                    .addClass( _this.option( 'classes.target_selected' ) )
                    .find( 'input[type=checkbox]' )
                    .prop( 'checked', true );

                event.preventDefault();
            });

            $( this.option( 'selectors.target_item' ) + ' input[type=checkbox]' ).on( 'click' + this.eventNamespace, function () {
                $( this ).closest( _this.option( 'selectors.target_item' ) ).toggleClass( _this.option( 'classes.target_selected' ) );
            });
        },
    });
})(jQuery);