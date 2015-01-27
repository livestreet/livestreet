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

    $.widget( "livestreet.lsFieldGeo", $.livestreet.lsComponent, {
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
            },
            params: {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            this.type = this.element.data( 'type' );
            this.option( 'params.type', this.type );

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

            this._load( 'regions', { country: this.elements.country.val(), target_type: this.type }, function( response ) {
                this.append( this.elements.region, response.aRegions, ls.lang.get( 'field.geo.select_region' ) );
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

            this._load( 'cities', { region: this.elements.region.val(), target_type: this.type }, function( response ) {
                this.append( this.elements.city, response.aCities, ls.lang.get( 'field.geo.select_city' ) );
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