jQuery(document).ready(function($) {
	$('html').removeClass('no-js');


	// Определение браузера
	if ($.browser.opera) {
		$('body').addClass('opera opera' + parseInt($.browser.version));
	}
	if ($.browser.mozilla) {
		$('body').addClass('mozilla mozilla' + parseInt($.browser.version));
	}
	if ($.browser.webkit) {
		$('body').addClass('webkit webkit' + parseInt($.browser.version));
	}
	if ($.browser.msie) {
		$('body').addClass('ie');
		if (parseInt($.browser.version) > 8) {
			$('body').addClass('ie' + parseInt($.browser.version));
		}
	}

	// Фикс бага с z-index у встроенных видео
	$("iframe").each(function(){
		var ifr_source = $(this).attr('src');

		if(ifr_source) {
			var wmode = "wmode=opaque";

			if (ifr_source.indexOf('?') != -1)
				$(this).attr('src',ifr_source+'&'+wmode);
			else
				$(this).attr('src',ifr_source+'?'+wmode);
		}
	});


	/**
	 * Editor help
	 */
	$('.js-tags-help-link').click(function(){
		var target=ls.registry.get('tags-help-target-id');
		if (!target || !$('#'+target).length) {
			return false;
		}
		target=$('#'+target);
		if ($(this).data('insert')) {
			var s=$(this).data('insert');
		} else {
			var s=$(this).text();
		}
		$.markItUp({target: target, replaceWith: s});
		return false;
	});



	/**
	 * Tag search
	 */
	$('.js-tag-search-form').submit(function(){
		var val=$(this).find('.js-tag-search').val();
		if (val) {
			window.location = aRouter['tag']+encodeURIComponent(val)+'/';
		}
		return false;
	});


	/**
	 * Preview image
	 */
	$('.js-topic-preview-image').each(function () {
		$(this).imagesLoaded(function () {
			var $this = $(this),
				$preview = $this.closest('.js-topic-preview-loader').removeClass('loading');
				
			$this.height() < $preview.height() && $this.css('top', ($preview.height() - $this.height()) / 2 );
		});
	});

	
	/**
	 * IE
	 */
	
	// эмуляция border-sizing в IE
	var inputs = $('input.input-text, textarea');
	ls.ie.bordersizing(inputs);

	// эмуляция placeholder'ов в IE
	inputs.placeholder();
});