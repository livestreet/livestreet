/**
 * Подгрузка контента
 *
 * @module ls/more
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsMore", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            urls: {
                load: null
            },
            selectors: {
                counter: '.js-more-count'
            },
            classes: {
                loading: 'loading',
                locked: 'more--locked'
            },
            // Селектор блока с содержимым
            target: null,
            // Добавление контента в конец/начало контейнера
            // true - в конец
            // false - в начало
            append: true,
            // Параметры запроса
            params: {},
            // Проксирующие параметры
            proxy: []
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            this.target = $( this.options.target );

            this._on({ click: 'onClick' });
            this.element.bind( 'keydown' + this.eventNamespace, 'return', this.onClick.bind( this ) );
        },

        /**
         * Коллбэк вызываемый при клике по кнопке
         */
        onClick: function ( event ) {
            if ( ! this.isLocked() ) this.load();
            event.preventDefault();
        },

        /**
         * Блокирует блок подгрузки
         */
        lock: function () {
            this._isLocked = true;
            this._addClass( 'loading locked' );
        },

        /**
         * Разблокировывает блок подгрузки
         */
        unlock: function () {
            this._isLocked = false;
            this._removeClass( 'loading locked' );
        },

        /**
         * Проверяет заблокирован виджет или нет
         */
        isLocked: function () {
            return this._isLocked;
        },

        /**
         * Получает значение счетчика
         */
        getCount: function () {
            return this.elements.counter.length && parseInt( this.elements.counter.text(), 10 );
        },

        /**
         * Устанавливает значение счетчика
         */
        setCount: function ( number ) {
            this.elements.counter.length && this.elements.counter.text( number );
        },

        /**
         * Подгрузка
         */
        load: function () {
            this._trigger("beforeload", null, this);

            this.lock();

            this._load( 'load', function ( response ) {
                if ( response.count_loaded > 0 ) {
                    var html = $('<div></div>').html( $.trim( response.html ) );

                    if ( html.find( this.options.target ).length ) {
                        html = html.find( this.options.target ).first();
                    }

                    this.target[ this.options.append ? 'append' : 'prepend' ]( html.html() );

                    // Обновляем счетчик
                    if ( this.elements.counter.length ) {
                        var countLeft = this.getCount() - response.count_loaded;

                        if ( countLeft <= 0 ) {
                            this.destroy();
                            this.element.remove();
                        } else {
                            this.setCount( countLeft );
                        }
                    }

                    // Обновляем параметры
                    $.each( this.options.proxy, function( k, v ) {
                        if ( response[ v ] ) this._setParam( v, response[ v ] );
                    }.bind( this ));

                    if ( response.hide ) {
                        this.destroy();
                        this.element.remove();
                    }
                } else {
                    // Для блоков без счетчиков
                    ls.msg.notice( null, ls.lang.get( 'more.empty' ) );

                    this.destroy();
                    this.element.remove();
                }

                this.unlock();

                this._trigger("afterload", null, { context: this, response: response });
            });
        }
    });
})(jQuery);