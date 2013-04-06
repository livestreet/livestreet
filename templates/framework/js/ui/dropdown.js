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

                // Change dropdown's text on item click and add 'active' class to clicked item
                if (this.options.activateItems || this.options.changeText) {
                    this.$target.find('li > a').on('click', function (e) {
                        var $link = $(this);

                        if (self.options.activateItems) {
                            self.$target.find('li').removeClass('active');
                            $link.parent('li').addClass('active');
                        }
                        if (self.options.changeText) {
                            self.$toggle.find('[data-type=dropdown-text]').text($link.text());
                        }
                        self.hide();
                    });
                }
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