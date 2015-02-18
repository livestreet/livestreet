/**
 * Аякс поиск
 *
 * @module ls/search-ajax
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsSearchAjax", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                search: null
            },

            // Селекторы
            selectors: {
                more: '.js-more-search'
            },

            // Фильтры
            filters : [],

            // Парметры передаваемый при аякс запросе
            params : {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            var _this = this;

            // Иниц-ия фильтров
            $.each( this.option( 'filters' ), function ( index, value ) {
                _this.addFilter( value );
            });

            // Кнопка подгрузки
            this.elements.more.livequery(function () {
                $( this ).lsMore({
                    urls: {
                        load: _this.option( 'urls.search' )
                    },
                    beforeload: function ( event, context ) {
                        $.extend( context.option( 'params' ), _this.option( 'params' ) );
                    }
                });
            });
        },

        /**
         * Добавление фильра
         */
        addFilter: function( filter ) {
            var _this = this,
                element = $( filter.selector ),
                activeClass = filter.activeClass || ls.options.classes.states.active;

            switch ( filter.type ) {
                // Текстовое поле
                case 'text':
                    var alphanumericFilter = $( filter.alphanumericFilterSelector );

                    element.on( 'keyup', function () {
                        _this.setParam( filter.name, $( this ).val() );
                        _this.setParam( 'isPrefix', 0 );

                        if ( alphanumericFilter.length ) {
                            alphanumericFilter
                                .eq(0)
                                .find( 'li' )
                                .removeClass( ls.options.classes.states.active )
                                .first()
                                .addClass( ls.options.classes.states.active );
                        }

                        ls.timer.run( _this, _this.update, null, null, 300 );
                    });

                    break;
                case 'radio':
                case 'checkbox':
                case 'select':
                    // TODO: multiselect
                    element.on( 'change', function () {
                        var value, el = $( this );

                        if ( filter.type == 'checkbox' ) {
                            value = el.is( ':checked' ) ? 1 : 0;
                        } else {
                            value = el.val();
                        }

                        _this.setParam( filter.name, value );
                        _this.update();
                    });

                    break;
                case 'list':
                case 'sort':
                case 'alphanumeric':
                    element.on( 'click', function ( event ) {
                        var el = $( this ),
                            els = el.closest( 'ul' ).find( 'li' ).not( el ),
                            value = el.data( 'value' );

                        els.removeClass( activeClass );
                        el.addClass( activeClass );

                        if ( filter.type == 'sort' ) {
                            var order = el.attr( 'data-order' );

                            els.attr( 'data-order', 'asc' );
                            el.attr( 'data-order', el.attr( 'data-order' ) == 'asc' ? 'desc' : 'asc' );

                            _this.setParam( 'order', order );
                        }

                        if ( filter.type == 'alphanumeric' ) {
                            var letter = el.data( 'letter' );

                            _this.setParam( 'isPrefix', letter ? 1 : 0 );
                            value = letter;

                            // Сбрасываем текстовый фильтр
                            $( filter.textFilterSelector ).val( '' );
                        }

                        _this.setParam( filter.name, value );
                        _this.update();

                        event.preventDefault();
                    });

                    break;
                default:
                    break;
            }
        },

        /**
         * Установка параметра
         */
        setParam: function( name, value ) {
            this.option( 'params.' + name, value );
            // this.updateUrl();
        },

        /**
         * Получение параметра
         */
        getParam: function( name ) {
            return this.option( 'params.' + name );
        },

        /**
         * Обновление поиска
         */
        update: function() {
            this._load( 'search', function ( response ) {
                this.element.html( $.trim( response.html ) );
            });
        },

        /**
         * Обновляет ссылку на основе параметров
         */
        updateUrl: function () {
            window.history.pushState( {}, 'Search', window.location.origin + window.location.pathname + '?' + $.param( this.option( 'params' ) ) );
        }
    });
})( jQuery );