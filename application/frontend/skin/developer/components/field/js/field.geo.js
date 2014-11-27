/**
 * Местоположение
 *
 * @module ls/field/geo
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsFieldGeo", {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                regions: null,
                cities: null
            },
            // Селекторы
            selectors: {
                country: '.js-field-geo-country',
                region: '.js-field-geo-region',
                city: '.js-field-geo-city'
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
                country: this.element.find( this.option( 'selectors.country' ) ),
                region: this.element.find( this.option( 'selectors.region' ) ),
                city: this.element.find( this.option( 'selectors.city' ) )
            };

            this.elements.country.on( 'change' + this.eventNamespace, this._loadRegions.bind( this ) );
            this.elements.region.on( 'change' + this.eventNamespace, this._loadCities.bind( this ) );
        },

        /**
         * Подгрузка регионов
         */
        _loadRegions: function() {
            this.elements.city.empty().hide();

            if ( ! this.elements.country.val() ) {
                this.elements.region.empty().hide().change();
                return;
            }

            ls.ajax.load( this.option( 'urls.regions' ), { country: this.elements.country.val() }, function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( null, response.sMsg );
                } else {
                    this.append( this.elements.region, response.aRegions, ls.lang.get( 'field.geo.select_region' ) );
                }
            }.bind( this ));
        },

        /**
         * Подгрзука городов
         */
        _loadCities: function() {
            if ( ! this.elements.region.val() ) {
                this.elements.city.empty().hide().change();
                return;
            }

            ls.ajax.load( this.option( 'urls.cities' ), { region: this.elements.region.val() }, function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( null, response.sMsg );
                } else {
                    this.append( this.elements.city, response.aCities, ls.lang.get( 'field.geo.select_city' ) );
                }
            }.bind( this ));
        },

        /**
         * Добавление пунктов в select
         */
        append: function( element, items, addText ) {
            element.empty().show().append( '<option value="">' + addText + '</option>' );

            $($.map( items, function( value, index ) {
                return '<option value="' + value.id + '">' + value.name + '</option>';
            }).join( '' )).appendTo( element );
        },

        /**
         * Получает выбранную страну
         */
        getCountry: function() {
            return this.elements.country.val();
        },

        /**
         * Получает выбранный регион
         */
        getRegion: function() {
            return this.elements.region.val();
        },

        /**
         * Получает выбранный город
         */
        getCity: function() {
            return this.elements.city.val();
        },

        /**
         * Получает элемент виджета
         */
        getElement: function( name ) {
            return this.elements[ name ];
        }
    });
})(jQuery);