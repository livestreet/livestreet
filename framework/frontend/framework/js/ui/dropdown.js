/**
 * Dropdowns
 *
 * @version 1.0
 * @author Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

(function($) {
    "use strict";

    var Dropdown = function (element, options) {
        this.init('dropdown', element, options);
    };

    Dropdown.prototype = $.extend({}, $.fn.popup.Constructor.prototype, {
        constructor: Dropdown,

        hooks : {
            onInitTarget: function () {
                var self = this;

                // Toggle's text
                if (this.options.changeText) {
                    var activeText = this.$target.find('li.active').text();
                    activeText && this.$toggle.find('[data-type=dropdown-text]').text(activeText);
                }

                this.$target.on('click', 'li > a', function (e) {
                    var $link = $(this),
                        duration = self.options.duration;

                    if (self.options.activateItems) {
                        self.$target.find('li').removeClass('active');
                        $link.parent('li').addClass('active');
                    }
                    if (self.options.changeText) {
                        self.$toggle.find('[data-type=dropdown-text]').text($link.text());
                    }
                    
                    self.options.duration = 0;
                    self.hide();
                    self.options.duration = duration;
                });
            }
        }
    });

    $.fn.dropdown = function (options, variable, value) {
        return ls.popup.initPlugin('dropdown', this, options, variable, value);
    };

    $.fn.dropdown.Constructor = Dropdown;

    $.fn.dropdown.defaults = $.extend({} , $.fn.popup.defaults, { 
        effect: 'slide',
        duration: 300
    });

    $.fn.dropdown.settings = $.extend({} , $.fn.popup.settings, { 
        toggleSelector: '[data-type=dropdown-toggle]',
        targetSelector: '[data-type=dropdown-target]'
    });
})(jQuery);