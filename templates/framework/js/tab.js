/*
 * Tabs
 *
 * Author: Denis Shakhov
 * Version: 1.0
 *
 */

(function($) {
	$.fn.tab = function (options) {
		var tabsSelector = '[data-toggle=tabs]';

		this.each(function () {
			var 
				$this     = $(this),
				$tabFirst = $this.closest(tabsSelector).find('li').eq(0);
			
			if (!$tabFirst.hasClass('active')) activateTab($tabFirst);

			// Click
			$this.click(function () {
				activateTab($this);
				return false;
			});
		});

		// Activate tab
		function activateTab (tab) {
			var
				$pane = $('#' + tab.data('tab-target')),
				dropdown = tab.closest('ul').parent('li');

			tab.addClass('active').closest(tabsSelector).find('li').not(tab).removeClass('active');
			if (dropdown.length > 0) dropdown.addClass('active');
			$pane.show().parent('.tabs-content').find('.tab-pane').not($pane).hide();
		}
	};
})(jQuery);

// Init
jQuery(document).ready(function($){
	$('[data-toggle=tab]').tab();
});