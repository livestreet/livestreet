var ls = ls || {};
ls.toolbar = ls.toolbar || {};

/**
 * Функционал тул-бара (плавающая пимпа) списка топиков
 */
ls.toolbar.topic = (function ($) {

	this.iCurrentTopic=-1;

	this.init = function() {
		var self = this;
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


		// Переход по страницам
		$(document).on('keyup', function (e) {
			var key = e.keyCode || e.which;

			if (e.ctrlKey) {
				switch (key) {
					case 38: // Up Arrow
						self.goPrev();
						break;
					case 40: // Down Arrow
						self.goNext();
						break;
				}
			}
		});
	};

	this.reset = function() {
		this.iCurrentTopic=-1;
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
			ls.pagination.next(true);
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
			ls.pagination.prev(true);
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

/**
 * Функционал кнопки "UP"
 */
ls.toolbar.up = (function ($) {

	this.init = function() {
		$(window).scroll(function(){
			if ($(window).scrollTop() > $(window).height() / 2) {
				$('#toolbar_scrollup').fadeIn(500);
			} else {
				$('#toolbar_scrollup').fadeOut(500);
			}
		});
	};

	this.goUp = function() {
		ls.toolbar.topic.reset();
		$.scrollTo(0, 500);
		return false;
	};

	return this;
}).call(ls.toolbar.up || {},jQuery);