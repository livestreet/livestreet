/**
 * Каптча
 */

var ls = ls || {};

ls.captcha = (function ($) {
	var oCaptcha = null;

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Селектор каптчи
		selectors: {
			captcha: '.js-form-auth-captcha'
		}
	};

	/**
	 * Инициализация
	 */
	this.init = function(options) {
		this.options = $.extend({}, defaults, options);

		oCaptcha = $(this.options.selectors.captcha);

		// Подгружаем каптчу при открытии окна регистрации
		$('[data-option-target=tab-pane-registration]').tab('option', 'onActivate', function () {
			this.updateCaptcha();
		}.bind(this));

		// Обновляем каптчу при клике на нее
		oCaptcha.on('click', function () {
			this.updateCaptcha();
		}.bind(this));
	};

	/**
	 * Получает url каптчи
	 * @return {String} URL каптчи
	 */
	this.getCaptchaUrl = function () {
		return DIR_ENGINE_LIBS + '/external/kcaptcha/index.php?' + SESSION_NAME + '=' + SESSION_ID + '&n=' + Math.random();
	};

	/**
	 * Обновляет каптчу
	 */
	this.updateCaptcha = function () {
		oCaptcha.css('background-image', 'url(' + this.getCaptchaUrl() + ')');
	};

	return this;
}).call(ls.captcha || {}, jQuery);