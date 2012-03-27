var ls = ls || {};
ls.toolbar = ls.toolbar || {};

/**
 * Функционал тул-бара (плавающая пимпа) списка топиков
 */
ls.toolbar.topic = (function ($) {

	this.iCurrentTopic=-1;

	this.init = function() {
		var vars = [], hash;
		var hashes = window.location.hash.replace('#','').split('&');
		for(var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}

		if (vars.goTopic!==undefined) {
			if (vars.goTopic=='last') {
				this.iCurrentTopic=$('.js-topic').length-2;
			} else {
				this.iCurrentTopic=parseInt(vars.goTopic)-1;
			}
			this.goNext();
		}
	};

	/**
	 * Прокрутка следующему топику
	 */
	this.goNext = function() {
		this.iCurrentTopic++;
		var topic=$('.js-topic:eq('+this.iCurrentTopic+')');
		if (topic.length) {
			$.scrollTo(topic, 500);
		} else {
			this.iCurrentTopic=$('.js-topic').length-1;
			// переход на следующую страницу
			var page=$('.js-paging-next-page');
			if (page.length && page.attr('href')) {
				window.location=page.attr('href')+'#goTopic=0';
			}
		}

		return false;
	};

	/**
	 * Прокрутка предыдущему топику
	 */
	this.goPrev = function() {
		this.iCurrentTopic--;
		if (this.iCurrentTopic<0) {
			this.iCurrentTopic=0;
			// на предыдущую страницу
			var page=$('.js-paging-prev-page');
			if (page.length && page.attr('href')) {
				window.location=page.attr('href')+'#goTopic=last';
			}
		} else {
			var topic=$('.js-topic:eq('+this.iCurrentTopic+')');
			if (topic.length) {
				$.scrollTo(topic, 500);
			}
		}
		return false;
	};

	return this;
}).call(ls.toolbar.topic || {},jQuery);