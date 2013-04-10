var ls = ls || {};

/**
 * JS функционал админки
 */
ls.admin = (function ($) {

	this.addCategoryBlog = function(form) {
		var url = aRouter.admin+'blogcategory/add/';
		ls.ajaxSubmit(url, form, function(result) {
			if (typeof(form)=='string') {
				form=$('#'+form);
			}

			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				$(form.parents('.modal-ajax')).jqmHide();
				window.location.href=window.location.href;
			}
		}.bind(this));
	};

	this.editCategoryBlog = function(form) {
		var url = aRouter.admin+'blogcategory/edit/';
		ls.ajaxSubmit(url, form, function(result) {
			if (typeof(form)=='string') {
				form=$('#'+form);
			}

			if (result.bStateError) {
				ls.msg.error(result.sMsgTitle,result.sMsg);
			} else {
				$(form.parents('.modal-ajax')).jqmHide();
				window.location.href=window.location.href;
			}
		}.bind(this));
	};

	return this;
}).call(ls.admin || {},jQuery);