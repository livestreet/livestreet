/**
 * Родительский jquery-виджет
 * Предоставляет вспомогательные методы для дочерних виджетов
 *
 * @module ls/component
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsComponent", {
        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            // Получаем опции из data атрибутов
            $.extend( this.options, ls.utils.getDataOptions( this.element, this.widgetName.toLowerCase() ) );

            // Получаем параметры отправляемые при каждом аякс запросе
            this._getParamsFromData();

            // Список локальных элементов
            this.elements = {};

            // Получаем локальные элементы компонента из селекторов
            $.each( this.options.selectors || {}, function ( key, value ) {
                this.elements[ key ] = this.element.find( value );
            }.bind( this ));

            // Генерируем методы для работы с классами
            $.each( [ 'hasClass', 'addClass', 'removeClass' ], function ( key, value ) {
                this[ '_' + value ] = function( element, classes ) {
                    if ( typeof element === "string" ) {
                        classes = element;
                        element = this.element;
                    }

                    classes = $.map( classes.split( ' ' ), function ( value ) {
                        return this.option( 'classes.' + value );
                    }.bind( this )).join( ' ' );

                    return element[ value ]( classes );
                }.bind( this )
            }.bind( this ));
        },

        /**
         * Получает локальный элемент по его имени
         */
        getElement: function( name ) {
            return this.elements[ name ];
        },

        /**
         * Получает параметры отправляемые при каждом аякс запросе
         */
        _getParamsFromData: function( url, params, callback ) {
            $.extend( this.options.params, ls.utils.getDataOptions( this.element, 'param' ) );
        },

        /**
         * 
         */
        _load: function( url, params, callback ) {
            if ( $.isFunction( params ) ) {
                callback = params;
                params = false;
            }

            if ( params ) $.extend( this.option( 'params' ), params );

            if ( typeof callback === "string" ) callback = this[ callback ];

            ls.ajax.load( this.options.urls[ url ], this.option( 'params' ), callback.bind( this ) );
        },

        /**
         * 
         */
        _submit: function( url, form, callback ) {
            ls.ajax.submit( this.options.urls[ url ], form, callback.bind( this ), {
                params: this.option( 'params' ) || {}
            });
        },

        /**
         * 
         */
        _setParam: function( param, value ) {
            return this.option( 'params.' + param, value );
        },

        /**
         * 
         */
        _getParam: function( param ) {
            return this.option( 'params.' + param );
        }

        /**
         * 
         */
        // _hasClass: function( element, classes ) {},

        /**
         * 
         */
        // _addClass: function( element, classes ) {},

        /**
         * 
         */
        // _removeClass: function( element, classes ) {},
    });
})(jQuery);