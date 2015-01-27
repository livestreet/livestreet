/**
 * Crop
 *
 * @module ls/crop
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsCrop", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            minSize: [ 0, 0 ],
            maxSize: [ 0, 0 ],
            aspectRatio: 0,

            // Селекторы
            selectors: {
                image: '.js-crop-image',
                preview: '.js-crop-preview-image'
            },
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            var _this = this;

            this._super();

            // Вычисляем минимальные ширину и высоту
            var ratio = this.elements.image.innerWidth() / this.element.data( 'crop-width' );
            var jcropOptions = $.extend( {}, this.options, {
                minSize: [
                    ratio * this.option( 'minSize' )[0],
                    ratio * this.option( 'minSize' )[1]
                ],
                onChange: this._updatePreviews.bind( this ),
                onSelect: this._updatePreviews.bind( this )
            });

            // Иниц-ия jcrop
            this.elements.image.Jcrop( jcropOptions, function() {
                _this.jcrop = this;

                // Добавляем начальное выделение
                var iw = _this.elements.image.innerWidth();
                var ih = _this.elements.image.innerHeight();

                var sx = 10, sy = 10, ex = iw - 10, ey = ih - 10;

                if ( _this.option( 'aspectRatio' ) ) {
                    sx = iw / 2 - 50;
                    sy = ih / 2 - 50;
                    ex = sx + 100;
                    ey = sy + 100;
                }

                this.setSelect([ sx, sy, ex, ey ]);
            });
        },

        /**
         * 
         */
        getImage: function() {
            return this.elements.image;
        },

        /**
         * 
         */
        getSelection: function() {
            return this.jcrop.tellSelect();
        },

        /**
         * 
         */
        setSelection: function( coords ) {
            return this.jcrop.setSelect( coords );
        },

        /**
         * 
         */
        _updatePreviews: function( coords ) {
            var _this = this;

            this.elements.preview.each(function() {
                var preview = $( this ),
                    size = preview.data('size'),
                    rx = size / coords.w,
                    ry = size / coords.h;

                preview.css({
                    width:      Math.round( rx * _this.elements.image.width() ) + 'px',
                    height:     Math.round( ry * _this.elements.image.height() ) + 'px',
                    marginLeft: '-' + Math.round( rx * coords.x ) + 'px',
                    marginTop:  '-' + Math.round( ry * coords.y ) + 'px'
                });
            })
        },

        /**
         * 
         */
        _destroy: function() {
            this.jcrop.destroy();
        }
    });
})(jQuery);