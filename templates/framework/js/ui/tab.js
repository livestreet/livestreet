/*
 * Tabs
 *
 * @version 1.0
 * @author Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

(function($) {
    "use strict";

    /**
     * Constructs tab objects
     * @constructor
     * @class Tab
     * @param {Object} options Options
     */
    var Tab = function (element, options) {
        this.options = $.extend({}, $.fn.tab.defaults, options);

        this.$tab = $(element);
        this.$pane = $('#' + this.options.target);
    };

    Tab.prototype = {
    	/**
    	 * Activate tab
    	 */
    	activate: function () {
			var self = this,
                dropdown = this.$tab.closest('ul').parent('li');

			this.$tab
				.addClass('active')
				.closest($.fn.tab.settings.tabsSelector)
				.find('li') // TODO: Fix selector
				.not(this.$tab)
				.removeClass('active');

			if (dropdown.length > 0) dropdown.addClass('active');

			this.$pane.show().parent($.fn.tab.settings.contentSelector).find($.fn.tab.settings.paneSelector).not(this.$pane).hide();

            if (this.options.url) {
                this.$pane.empty().addClass('loading');

                ls.ajax(this.options.url, this.options.params, function (result) {
                    if (result.bStateError) {
                        ls.msg.error('Error', result.sMsg);
                    } else {
                        self.$pane.removeClass('loading').html(result[self.options.ajaxVar]);
                    }
                }, {
                    error: function () {
                        ls.msg.error('Error', 'Please try again later');
                    }
                });
            }
    	}
    };

    $.fn.tab = function (options, variable, value) {
        var returnValue = false;

        this.each(function () {
            var tab      = $(this),
                object   = tab.data('object');

            if ( ! object ) {
                tab.data('object', (object = new Tab(this, $.extend({}, options, ls.tools.getDataOptions(tab)))));
                object.options.params = ls.tools.getDataOptions(tab, 'param');
            }
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
    $.fn.tab.defaults = {
        target: false,
        ajaxVar: 'sText',
        url: false,
        params: {}
    };


    /**
     * Global settings
     * @type {Object}
     */
    $.fn.tab.settings = {
        tabsSelector:    '[data-type=tabs]',
        tabSelector:     '[data-type=tab]',
        contentSelector: '[data-type=tab-content]',
        paneSelector:    '[data-type=tab-pane]'
    };

    $(document).on('click.tab', $.fn.tab.settings.tabSelector, function (e) {
        $(this).tab('activate');
        e.preventDefault();
    });

    // Init
    $(document).ready(function($) {
        $($.fn.tab.settings.tabSelector).tab();
    });
})(jQuery);