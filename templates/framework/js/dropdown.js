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
			var 
				$this        = $(this),
				$menu        = $('#' + $this.data('dropdown-menu')),
				isFloatRight = $this.data('dropdown-align') != undefined,
				isAjax       = $this.data('dropdown-ajax') != undefined;
				isChangeText = $this.data('dropdown-change-text') == undefined;

			$menu.appendTo('body');

			// Set text
			if (isChangeText)  {
				var activeText = $menu.find('li.active').text();
				$this.text(activeText.length > 0 ? activeText : options.defaultActiveText);
			}

			// Resize
			$(window).resize(function () {
				positionMenu($this, $menu, isFloatRight);
			});

			// Click
			$this.click(function () {
				positionMenu($this, $menu, isFloatRight);
				menuToggle($this, $menu);
				hideDropdowns($this, $menu);
				return false;
			});

			if (isAjax) {
				$menu.find('li > a').click(function () {
					if (isChangeText) $this.text($(this).text());
					$menu.find('li').removeClass('active');
					$(this).parent('li').addClass('active');
					menuToggle($this, $menu);
				});
			}
		});

		// Hide dropdowns
		function hideDropdowns (currentDropdown, currentMenu) {
			objects.not(currentDropdown).removeClass('active');
			$('.dropdown-menu:visible').not(currentMenu).hide(); // TODO: Fix selector
		}

		// Position menu
		function positionMenu (toggle, menu, isFloatRight) {
			var
				pos    = toggle.offset(),
				height = toggle.outerHeight(),
				width  = toggle.outerWidth();

			menu.css({
				'top': pos.top + height + options.menuTopOffset,
				'left': isFloatRight ? 'auto' : pos.left,
				'right': isFloatRight ? $(window).width() - pos.left - width : 'auto'
			});
		}

		// Menu toggle
		function menuToggle(toggle, menu) {
			toggle.toggleClass('active');
			menu.toggle();
		}
	};
})(jQuery);