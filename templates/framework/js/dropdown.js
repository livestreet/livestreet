/*
 * Dropdowns
 *
 * Author: Denis Shakhov
 * Version: 1.0
 *
 * TODO: Add fixed menu option
 */

(function($) {
	$.fn.dropdown = function (options) {
        var defaults = {
            menuTopOffset: 2,
            defaultActiveText: '...'
        };
        var objects = this;
        var options = $.extend(defaults, options);

        // Hide menu when click anywhere but menu
        $('body').click(function () {
			hideDropdowns();
		});

        $('body').on("click", this, function(e) {
			e.stopPropagation();
		});

		this.each(function () {
			// TODO: Fix options
			var 
				$this          = $(this),
				$menu          = $('#' + $this.data('dropdown-menu')),
				isPullRight    = $this.data('dropdown-align') != undefined,
				isAjax         = $this.data('dropdown-ajax') != undefined,
				isChangeText   = $this.data('dropdown-change-text') == undefined,
				isAppendToBody = $this.data('dropdown-append-to-body') == undefined;

			if (isAppendToBody) $menu.appendTo('body');

			// Set text
			options.defaultActiveText = $this.data('dropdown-default-text') || options.defaultActiveText;

			if (isChangeText)  {
				var activeText = $menu.find('li.active').text();
				$this.text(activeText.length > 0 ? activeText : options.defaultActiveText);
			}

			// Resize
			$(window).resize(function () {
				positionMenu($this, $menu, isPullRight , isAppendToBody);
			});

			// Click
			$this.click(function () {
				positionMenu($this, $menu, isPullRight , isAppendToBody);
				menuToggle($this, $menu);
				hideDropdowns($this, $menu);
				return false;
			});

			if (isAjax) {
				$menu.find('li > a').click(function () {
					if (isChangeText) $this.text($(this).text());
					$menu.find('li').removeClass('open');
					$(this).parent('li').addClass('open');
					menuToggle($this, $menu);
				});
			}
		});

		// Hide dropdowns
		function hideDropdowns (currentDropdown, currentMenu) {
			objects.not(currentDropdown).removeClass('open');
			$('.dropdown-menu:visible').not(currentMenu).hide(); // TODO: Fix selector
		}

		// Position menu
		function positionMenu (toggle, menu, isPullRight , isAppendToBody) {
			var
				pos    = toggle.offset(),
				height = toggle.outerHeight(),
				width  = toggle.outerWidth();

			menu.css({
				'top': isAppendToBody ? pos.top + height + options.menuTopOffset : height + options.menuTopOffset,
				'left': isAppendToBody ? ( isPullRight  ? 'auto' : pos.left ) : 0,
				'right': isAppendToBody ? ( isPullRight  ? $(window).width() - pos.left - width : 'auto' ) : 'auto'
			});
		}

		// Menu toggle
		function menuToggle(toggle, menu) {
			toggle.toggleClass('open');
			menu.toggle();
		}
	};
})(jQuery);