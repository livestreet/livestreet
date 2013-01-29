/*
 * Tabs
 *
 * Author: Denis Shakhov
 * Version: 1.0
 *
 */

(function($) {
	$.fn.tab = function (options) {
		this.each(function () {
			var
				$this     = $(this),
				$tabFirst = $this.closest($.tab.tabsSelector).find('li').eq(0);

			if (!$tabFirst.hasClass('active')) $.tab.activate($tabFirst);

			// Click
			$this.click(function () {
				$.tab.activate($this);
				return false;
			});
		});
	};

	$.fn.tabActivate = function () {
		$.tab.activate($(this));
	};

	$.tab = {
		tabsSelector: '[data-toggle=tabs]',
		activate: function (tab) {
			var
				$pane = $('#' + tab.data('tab-target')),
				dropdown = tab.closest('ul').parent('li');

			tab.addClass('active').closest(this.tabsSelector).find('li').not(tab).removeClass('active');
			if (dropdown.length > 0) dropdown.addClass('active');
			$pane.show().parent('[data-toggle=tab-content]').find('[data-toggle=tab-pane]').not($pane).hide();
		}
	};
})(jQuery);

// Init
jQuery(document).ready(function($){
	$('[data-toggle=tab]').tab();
});