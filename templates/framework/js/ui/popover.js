/**
 * Popover
 *
 * @version 1.0
 * @author Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

(function($) {
    "use strict";

    var Popover = function (element, options) {
    	this.init('popover', element, options);
  	};

    Popover.prototype = $.extend({}, $.fn.popup.Constructor.prototype, {
        constructor: Popover,

        hooks : {
            onInitTarget: function () {
                if ( ! this.options.target ) { 
                    if ( ! this.options.title ) { 
                        this.options.title = this.$toggle.attr('title'); 
                        this.$toggle.removeAttr('title');
                    }

                    this.setTitle(this.options.title);
                    this.setContent(this.options.content);
                }
            }
        },

        /**
         * Set header
         */
        setTitle: function (title) {
            var $title = this.$target.find('[data-type=' + this.type + '-title]');

            title ? $title.show().html(title) : $title.hide();
        }
    });

    $.fn.popover = function (options, variable, value) {
        return ls.popup.initPlugin('popover', this, options, variable, value);
    };

    $.fn.popover.Constructor = Popover;

    $.fn.popover.defaults = $.extend({} , $.fn.popup.defaults, {
    	template: '<div class="popover" data-type="popover-target">' +
                       '<div class="popover-arrow"></div><div class="popover-arrow-inner"></div>' +
                       '<div class="popover-title" data-type="popover-title"></div>' +
                       '<div class="popover-content" data-type="popover-content"></div>' +
                   '</div>',
        alignX: 'center',
        alignY: 'top',
        offsetY: 10,
        duration: 300,
        event: 'click'
    });

    $.fn.popover.settings = $.extend({} , $.fn.popup.settings, { 
        toggleSelector: '[data-type=popover-toggle]',
        targetSelector: '[data-type=popover-target]'
    });
})(jQuery);