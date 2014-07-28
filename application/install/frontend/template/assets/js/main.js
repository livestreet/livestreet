var install = (function ($) {

	this.goNextStep = function() {
		console.log(333);
		$('#action_next').click();
		console.log(333);
	};

	return this;
}).call(install || {}, jQuery);