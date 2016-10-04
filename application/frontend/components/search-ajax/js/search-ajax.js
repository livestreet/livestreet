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
                list: '.js-search-ajax-list',
                more: '.js-search-ajax-more',
                title: null
            },

            // Локализация
            i18n: {
                title: null
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
                _this._initFilter( value );
            });

            // Кнопка подгрузки
            this.elements.more.lsMore({
                urls: {
                    load: _this.option( 'urls.search' )
                },
                beforeload: function ( event, context ) {
                    $.extend( context.option( 'params' ), _this.option( 'params' ) );
                }
            });
        },

        /**
         * Добавление фильтра
         */
        addFilter: function( filter ) {
            this.option( 'filters' ).push( filter );
            this._initFilter( filter );
        },

        /**
         * Иниц-ия фильтра
         */
        _initFilter: function( filter ) {
            var _this = this,
                element = $( filter.selector );

            switch ( filter.type ) {
                // Текстовое поле
                case 'text':
                    element.on( 'keyup', function () {
                        ls.timer.run( _this, _this.update, null, null, 300 );
                    });

                    break;
                case 'radio':
                case 'checkbox':
                case 'select':
                    // TODO: multiselect
                    element.on( 'change', function () {
                        _this.update();
                    });

                    break;
                case 'list':
                case 'sort':
                    element.on( 'click', function ( event ) {
                        var el = $( this ),
                            els = el.closest( 'ul' ).find( 'li' ).not( el ),
                            value = el.data( 'value' ),
                            activeClass = filter.activeClass || ls.options.classes.states.active;

                        els.removeClass( activeClass );
                        el.addClass( activeClass );

                        if ( filter.type == 'sort' ) {
                            var order = el.attr( 'data-order' );

                            els.attr( 'data-order', 'asc' );
                            el.attr( 'data-order', el.attr( 'data-order' ) == 'asc' ? 'desc' : 'asc' );
                        }

                        _this.update();
                        event.preventDefault();
                    });

                    break;
                default:
                    break;
            }
        },

        updateFilter: function( filter ) {
            var _this = this,
                element = $( filter.selector ),
                activeClass = filter.activeClass || ls.options.classes.states.active;

            switch ( filter.type ) {
                // Текстовое поле
                case 'text':
                    element.each(function () {
                        _this.setParam( filter.name, $(this).val() );
                        _this.setParam( 'isPrefix', 0 );
                    });

                    break;
                case 'radio':
                case 'checkbox':
                case 'select':
                    element.each(function () {
                        var value, el = $( this );

                        // Пропускаем неотмеченные радио инпуты
                        if ( filter.type == 'radio' && ! el.is( ':checked' ) ) return;

                        if ( filter.type == 'checkbox' ) {
                            value = el.is( ':checked' ) ? 1 : 0;
                        } else {
                            value = el.val();
                        }

                        _this.setParam( filter.name, value );
                    });

                    break;
                case 'list':
                case 'sort':
                    element.each(function () {
                        var el = $( this ).closest( 'ul' ).find( 'li.' + activeClass ),
                            value = el.data( 'value' );

                        if ( filter.type == 'sort' ) {
                            _this.setParam( 'order', el.attr( 'data-order' ) );
                        }

                        _this.setParam( filter.name, value );
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
            for (var i = 0; i < this.option( 'filters' ).length; i++) {
                this.updateFilter( this.option( 'filters' )[i] );
            };

            this._trigger( 'beforeupdate', null, this );

            this._load( 'search', 'onUpdate' );
        },

        /**
         * 
         */
        onUpdate: function ( response ) {
            this.elements.more[ response.hide ? 'hide' : 'show' ]();

            if ( response.searchCount ) {
                this.elements.list.show().html( $.trim( response.html ) );
            } else {
                this.elements.list.hide();
            }

            if ( this.option( 'i18n.title' ) && this.elements.title.length) {
                this.elements.title.show().text( this._i18n( 'title', response.searchCount ) );
            }

            this._trigger( 'afterupdate', null, { context: this, response: response } );
        },

        /**
         * Обновляет ссылку на основе параметров
         */
        updateUrl: function () {
            window.history.pushState( {}, 'Search', window.location.origin + window.location.pathname + '?' + $.param( this.option( 'params' ) ) );
        }
    });
})( jQuery );
