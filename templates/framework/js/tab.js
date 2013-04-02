/*
 * Tabs
 *
 * Author: Denis Shakhov
 * Version: 1.0
 *
 */

(function($) {
    /**
     * Constructs tab objects
     * @constructor
     * @class Tab
     * @param {Object} options Options
     */
    var Tab = function (element, options) {
        this.element = $(element);
    };

    /**
     * Static methods and vars
     * @type {Object}
     */
    Tab.settings = {
    	tabsSelector:    '[data-toggle=tabs]',
    	tabSelector:     '[data-toggle=tab]',
    	contentSelector: '[data-toggle=tab-content]',
    	paneSelector:    '[data-toggle=tab-pane]'
    };

    Tab.prototype = {
    	/**
    	 * Activate tab
    	 */
    	activate: function () {
			var
				pane     = $('#' + this.element.data('tab-target')),
				dropdown = this.element.closest('ul').parent('li');

			this.element
				.addClass('active')
				.closest(Tab.settings.tabsSelector)
				.find('li') // TODO: Fix selector
				.not(this.element)
				.removeClass('active');
			if (dropdown.length > 0) dropdown.addClass('active');
			pane.show().parent(Tab.settings.contentSelector).find(Tab.settings.paneSelector).not(pane).hide();
    	}
    };

    $(document).on('click.tab', Tab.settings.tabSelector, function () {
        $(this).data('object').activate();
		return false;
	});

	$.fn.tabActivate = function () {
		$(this).data('object').activate();
	};

    // Init
    $(document).ready(function($) {
        $(Tab.settings.tabSelector).each(function () {
            var 
                tab      = $(this),
                tabFirst = tab.closest(Tab.settings.tabsSelector).find('li').eq(0);
                object   = tab.data('object');

            if (!object) tab.data('object', (object = new Tab(this)));
            if (!tabFirst.hasClass('active')) tabFirst.data('object').activate();
        });
    });
})(jQuery);