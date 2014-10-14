/**
 * Block
 *
 * @module ls/block
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsBlock", {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                tabs: '.js-tabs-block',
                update: '.js-block-update-tabs',
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
                tabs: this.element.find( this.option( 'selectors.tabs' ) ),
                update: this.element.find( this.option( 'selectors.update' ) ),
                pane_container: this.element.find( '[data-type=tab-panes]' )
            };

            this.elements.tabs.lsTabs();

            // Сохраняем высоту блока при переключении табов
            this.elements.tabs.lsTabs( 'getTabs' ).lsTab( 'option', {
                beforeactivate: function ( e, data ) {
                    _this.elements.pane_container.css( 'height', _this.elements.pane_container.height() );
                },
                activate: function ( e, data ) {
                    _this.elements.pane_container.css( 'height', 'auto' );
                }
            });

            // Кнопка обновления активного таба
            this.elements.update.on( 'click' + this.eventNamespace, function () {
                this.elements.tabs.lsTabs( 'getActiveTab' ).lsTab( 'activate' );
                this.elements.update.addClass( 'active' );

                // Даем кнопке немного покрутиться
                setTimeout(function() {
                    this.elements.update.removeClass( 'active' );
                }.bind( this ), 600 );
            }.bind( this ));
        }
    });
})(jQuery);