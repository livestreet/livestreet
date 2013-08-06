/*
 * Toolbar
 *
 * @version 1.0
 * @author Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

(function($) {
    "use strict";

    /**
     * Constructs toolbar objects
     * @constructor
     * @class Toolbar
     * @param {Object} options Options
     */
    var Toolbar = function (element, options) {
        this.options = $.extend({}, $.fn.toolbar.defaults, options);

        this.$toolbar = $(element);
        this.$target = $(this.options.alignTo);

        this.position();
    };

    Toolbar.prototype = {
    	/**
    	 * Position
    	 */
    	position: function () {
            typeof this.options.onPosition === 'function' && $.proxy(this.options.onPosition, this)();

            var targetPos = this.$target.offset();

            this.$toolbar.css({
                'top': targetPos.top + this.options.offsetY,
                'left': (this.options.align == 'right' ? targetPos.left + this.$target.outerWidth() + this.options.offsetX : targetPos.left - this.$toolbar.outerWidth() - this.options.offsetX) - $(document).scrollLeft()
            })
    	}
    };


    /**
     * Plugin defention
     */
    $.fn.toolbar = function (options, variable, value) {
        var returnValue = false;

        this.each(function () {
            var toolbar  = $(this),
                object   = toolbar.data('object');

            if ( ! object ) toolbar.data('object', (object = new Toolbar(this, $.extend({}, options, ls.tools.getDataOptions(toolbar)))));
            if (typeof options === 'string') {
                if (options === "option") {
                    if (value) object.options[variable] = value; else returnValue = object.options[variable];
                } else {
                    object[options]();
                }
            }
        });

        return returnValue;
    };


    /**
     * Default options
     * @type {Object}
     */
    $.fn.toolbar.defaults = {
        alignTo:   false,
        offsetX:   0,
        offsetY:   0,
        onPosition: false
    };


    $(window).on('resize scroll', function () {
        $($.fn.toolbar.settings.toolbarSelector).each(function () {
            var obj = $(this).data('object');
            obj && obj.position();
        })
    })


    /**
     * Global settings
     * @type {Object}
     */
    $.fn.toolbar.settings = {
        toolbarSelector:    '[data-type=toolbar]'
    };
})(jQuery);