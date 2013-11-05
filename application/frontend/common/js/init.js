/**
 * Основной файл инициализации
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

jQuery(document).ready(function($) {
	$('html').removeClass('no-js');

	/**
	 * Определение браузера
	 */
	if ($.browser.opera) {
		$('body').addClass('opera opera' + parseInt($.browser.version, 10));
	}

	if ($.browser.mozilla) {
		$('body').addClass('mozilla mozilla' + parseInt($.browser.version, 10));
	}

	if ($.browser.webkit) {
		$('body').addClass('webkit webkit' + parseInt($.browser.version, 10));
	}

	if ($.browser.msie) {
		$('body').addClass('ie');
		if (parseInt($.browser.version, 0) > 8) {
			$('body').addClass('ie' + parseInt($.browser.version, 10));
		}
	}


	/**
	 * Фикс бага с z-index у встроенных видео
	 */
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
	 * Поиск по тегам
	 */
	$('.js-tag-search-form').submit(function(){
		var val=$(this).find('.js-tag-search').val();
		if (val) {
			window.location = aRouter['tag']+encodeURIComponent(val)+'/';
		}
		return false;
	});
});