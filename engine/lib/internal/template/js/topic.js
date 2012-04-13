var ls = ls || {};

/**
 * Опросы
 */
ls.topic = (function ($) {

	this.preview = function(form, preview) {
		form=$('#'+form);
		preview=$('#'+preview);
		var url = aRouter['ajax']+'preview/topic/';
		'*previewBefore*'; '*/previewBefore*';
		ls.ajaxSubmit(url, form, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				preview.html(result.sText);

				ls.hook.run('ls_topic_preview_after',[form, preview, result]);
			}
		});
	};

	return this;
}).call(ls.topic || {},jQuery);