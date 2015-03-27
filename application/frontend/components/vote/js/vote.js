/**
 * Голосование
 *
 * @module ls/vote
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsVote", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                // Голосование
                vote: null,
                // Информация о голосовании
                info: null
            },

            // Селекторы
            selectors: {
                // Кнопки голосования
                item:   '.js-vote-item',
                // Рейтинг
                rating: '.js-vote-rating'
            },

            // Классы
            classes : {
                // Пользователь проголосовал
                voted:          'vote--voted',
                // Не проголосовал
                not_voted:      'vote--not-voted',
                // Понравилось
                voted_up:       'vote--voted-up',
                // Не понравилось
                voted_down:     'vote--voted-down',
                // Воздержался
                voted_zero:     'vote--voted-zero',

                // Рейтинг больше нуля
                count_positive: 'vote--count-positive',
                // Меньше нуля
                count_negative: 'vote--count-negative',
                // Равен нулю
                count_zero:     'vote--count-zero',

                // Рейтинг скрыт
                rating_hidden:  'vote--rating-hidden'
            },
            // Параметры отправляемые при каждом аякс запросе
            params: {},
            // Опции тултипа с информацией о голосовании
            tooltip_options: {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            // Обработка кликов по кнопкам голосования
            if ( ! this._hasClass( 'voted' ) ) {
                this._on( this.elements.item, {
                    click: function ( event ) {
                        this.vote( $( event.currentTarget ).data( 'vote-value' ) );
                        event.preventDefault();
                    }
                });
            }

            // Иниц-ия тултипа с информацией о голосовании
            // Показываем инфо-ию только если рейтинг отображается
            if ( ! this._hasClass( 'rating_hidden' ) ) {
                this.info();
            }
        },

        /**
         * Голосование
         *
         * @param {Number} value Значение
         */
        vote: function( value ) {
            this.option( 'params.value', value );

            this._load( 'vote', function ( response ) {
                response.iRating = parseFloat( response.iRating );

                // Добавляем/удаляем классы
                this._removeClass( 'count_negative count_positive count_zero rating_hidden not_voted' );
                this._addClass( 'voted' );
                this._addClass( value > 0 ? 'voted_up' : ( value < 0 ? 'voted_down' : 'voted_zero' ) );
                this._addClass( response.iRating > 0 ? 'count_positive' : ( response.iRating < 0 ? 'count_negative' : 'count_zero' ) );

                // Не обрабатываем клики после голосования
                this._off( this.elements.item, 'click' );

                // Удаляем подсказки и устанавливаем рейтинг
                this.elements.item.removeAttr( 'title' );
                this.elements.rating.text( response.iRating );

                // Иниц-ия тултипа
                this.info().lsTooltip( 'show' );
            });
        },

        /**
         * Иниц-ия тултипа с информацией о голосовании
         *
         * @return {jQuery}
         */
        info: function () {
            if ( ! this.options.urls.info ) return $();

            return this.element.lsTooltip($.extend({}, {
                ajax: {
                    url: this.options.urls.info,
                    params: this.options.params
                }
            }, this.options.tooltip_options));
        }
    });
})(jQuery);