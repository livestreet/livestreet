/**
 * Tabs
 *
 * @module ls/tab
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsTab", {
        /**
         * Дефолтные опции
         */
        options: {
            // Блок с содержимым таба
            target: null,
            // Контейнер с табами
            tabs: $(),

            // Настройки аякса

            // Ссылка
            url: null,
            // Название переменной с результатом
            result: 'sText',
            // Параметры запроса
            params: {},

            // Callbacks

            // Вызывается при активации таба
            beforeactivate: null,
            // Вызывается в конце активации таба
            activate: null
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function() {
            this.options.params = ls.utils.getDataOptions( this.element, 'param' );
            this.options = $.extend( {}, this.options, ls.utils.getDataOptions( this.element, 'tab' ) );

            this.pane = $( '#' + this.option( 'target' ) );

            this._on({
                click: function (e) {
                    this.activate();
                    e.preventDefault();
                }
            });

            // Поддержка активации табов с помощью хэшей
            // Активируем таб с классом active
            if ( this.options.target == location.hash.substring(1)
                || (
                    this.options.url
                    && this.element.hasClass( ls.options.classes.states.active )
                    && ! this.pane.text()
                )
            ) this.activate();
        },


        /**
         * Активация таба
         */
        activate: function () {
            this._trigger("beforeactivate", null, this);

            // Активируем таб
            this.option( 'tabs' ).lsTabs( 'getTabs' ).removeClass( 'active' );
            this.element.addClass( 'active' );

            // Показываем блок с контентом таба
            this.option( 'tabs' ).lsTabs( 'getPanes' ).hide();
            this.pane.show();

            // Поддержка дропдаунов
            // var dropdown = this.element.closest('ul').parent('li');
            // if (dropdown.length > 0) dropdown.addClass('active');

            // Загрузка содержимого таба через аякс
            if ( this.options.url ) {
                this._load();
            } else {
                this._trigger( 'activate', null, this );
            }
        },

        /**
         * Загрузка содержимого таба через аякс
         *
         * @private
         */
        _load: function () {
            this.pane.empty().addClass( 'loading' );

            ls.ajax.load(this.options.url, this.options.params, function (response) {
                this.pane.removeClass( 'loading' );

                if ( response.bStateError ) {
                    //this.pane.html('Error');
                } else {
                    this.pane.html( response[ this.options.result ] );
                }

                this._trigger( 'activate', null, this );
            }.bind(this), {
                error: function ( response ) {
                    this.pane.removeClass( 'loading' );
                    //this.pane.html('Error');
                }.bind( this )
            });
        }
    });
})(jQuery);