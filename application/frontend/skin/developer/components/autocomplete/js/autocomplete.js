/**
 * Autocomplete
 *
 * @module ls/autocomplete
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsAutocomplete", {
        /**
         * Дефолтные опции
         */
        options: {
            multiple: false,
            // Ссылки
            urls: {
                load: null
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
            var _this = this;

            if ( this.option( 'multiple' ) ) {
                this.element
                    .bind( 'keydown', function( event ) {
                        if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "ui-autocomplete" ).menu.activeMenu.is(':visible') ) {
                            event.preventDefault();
                        }
                    })
                    .autocomplete({
                        source: function( request, response ) {
                            _this.option( 'params' ).value = _this._extractLast( request.term );

                            ls.ajax.load( _this.option( 'urls.load' ), _this.option( 'params' ), function( data ) {
                                response( data.aItems );
                            });
                        },
                        search: function() {
                            var term = _this._extractLast( this.value );

                            if ( term.length < 2 ) {
                                return false;
                            }
                        },
                        focus: function() {
                            return false;
                        },
                        select: function( event, ui ) {
                            var terms = _this._split( this.value );

                            terms.pop();
                            terms.push( ui.item.value );
                            terms.push( "" );
                            this.value = terms.join( ", " );

                            return false;
                        }
                    });
            } else {
                this.element.autocomplete({
                    source: function( request, response ) {
                        _this.option( 'params' ).value = _this._extractLast( request.term );

                        ls.ajax.load( _this.option( 'urls.load' ), _this.option( 'params' ), function( data ) {
                            response( data.aItems );
                        });
                    }
                });
            }
        },

        /**
         * 
         */
        _split: function ( str ) {
            return str.split( /,\s*/ );
        },

        /**
         * 
         */
        _extractLast: function ( term ) {
            return this._split( term ).pop();
        },
    });
})(jQuery);