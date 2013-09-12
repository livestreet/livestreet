(function($) {
	"use strict";

	ls.blog.toggleInfo = function() {
		var $toggle = $('#js-blog-toggle'),
			$blog = $('#js-blog'),
			$blogFullInfo = $('#js-blog-full-info');

		if ($blog.hasClass('open')) {
			$blog.removeClass('open');

			$blogFullInfo.slideUp(300, function () {
				$toggle.text(ls.lang.get('blog_expand_info'));
			});
		} else {
			$blog.addClass('open');
			$toggle.text(ls.lang.get('blog_fold_info'));
			$blogFullInfo.slideDown();
		}

		return false;
	};


	ls.hook.add('ls_blog_toggle_join_after', function (idBlog, result) {
		$(this).removeClass('active');
	});
})(jQuery);