(function($) {
	"use strict";

	ls.stream.options.elements.userItem = function (element) {
		return $('<li id="' + ls.stream.options.selectors.activityBlockUsersItemId + element.uid + '">' +
					 '<input type="checkbox" ' + 
					        'class="input-checkbox" ' +
					        'data-user-id="' + element.uid + '" ' +
					        'checked="checked" /> ' +
					        '<a href="' + element.user_web_path + '"><img src="' + element.user_avatar_48 + '" /></a> ' +
					        '<a href="' + element.user_web_path + '">' + element.user_login + '</a>' +
				 '</li>');
	}
})(jQuery);