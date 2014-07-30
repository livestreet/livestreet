var install = (function ($) {

	this.goNextStep = function() {
		$('#action_next').click();
	};

	return this;
}).call(install || {}, jQuery);