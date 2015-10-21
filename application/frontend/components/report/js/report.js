/**
 * Report
 *
 * @module ls/report
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsReport", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            params: {},

            // Ссылки
            urls: {
                modal: null,
                add: null
            },

            // Селекторы
            selectors: {
                form: 'form'
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

            this.option( 'params', $.extend( {}, this.option( 'params' ), ls.utils.getDataOptions( this.element, 'param' ) ) );

            this._on({ click: this.showModal });
        },

        /**
         * Показывает модальное окно с формой
         */
        showModal: function( event ) {
            var _this = this, form;

            ls.modal.load( this.option( 'urls.modal' ), this.option( 'params' ), {
                aftershow: function ( e, modal ) {
                    form = modal.element.find( _this.option( 'selectors.form' ) );

                    // Отправка формы
                    form.on( 'submit', function ( event ) {
                        _this._submit( 'add', form, function( response ) {
                            modal.hide();
                        });

                        event.preventDefault();
                    });
                },
                afterhide: function () {
                    form.off();
                    form = null;
                },
                center: false
            });

            event.preventDefault();
        }
    });
})(jQuery);