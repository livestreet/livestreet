/**
 * Modal
 *
 * @module modal
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

(function($) {
	"use strict";

	var scrollbarWidth, html, body, _window = $(window);

	// Overlay
	// ----------

	var _overlay = (function ($) {
		this.element = $('<div class="modal-overlay js-modal-overlay" />');

		/**
		 * Init
		 */
		this.init = function () {
			html = $('html');
			body = $('body');
			scrollbarWidth = this.getScrollbarWidth();

			body.append( this.element );
			this.resize();
		};

		/**
		 * Show
		 */
		this.show = function () {
			// Скрываем скролл
			html.css('overflow', 'hidden');

			// Добавляем отступ чтобы контент не смещался после убирания скроллбара
			if ( body.outerHeight() > _window.height() ) body.css('margin-right', scrollbarWidth);

			this.element.css({
				overflow: 'auto'
			});

			this.element.show({
				effect: 'fade',
				duration: 300
			});
		};

		/**
		 * Hide
		 */
		this.hide = function () {
			this.element.hide({
				effect: 'fade',
				duration: 300,
				complete: function () {
					html.css('overflow', 'auto');
					body.css('margin-right', 0);
				}
			});
		};

		/**
		 * Resize
		 */
		this.resize = function () {
			this.element.height( _window.height() );

			// Центрирование мод. окна при ресайзе
			// Необходимо из за того что в FF и IE анимация воспроизводится
			// криво при margin: 0 auto
			var modal = this.getActiveModal();
			if ( ! modal.length ) return;
			modal.css('margin-left', ( _overlay.element.width() - modal.outerWidth() ) / 2);
		};

		/**
		 * Is overlay visible or not
		 */
		this.isVisible = function () {
			return this.element.is(':visible');
		};

		/**
		 * Return active modal window
		 */
		this.getActiveModal = function () {
			return _overlay.element.find('[data-type=modal]:visible').eq(0);
		};

		/**
		 * Получает ширину скроллбара в браузере
		 */
		this.getScrollbarWidth = function () {
			var holder = $('<div>').css({ 'width': 100, 'height': 100, 'overflow': 'auto', 'position': 'absolute', 'bottom': 0, 'left': 0 });
			var width = 100 - $('<div>').css('height', 200).appendTo( holder.appendTo('body') ).width();
			holder.remove();
			return width;
		};

		return this;
	}).call(_overlay || {}, jQuery);


	// Loader
	// ----------

	var _loader = (function ($) {
		this.element = $('<div class="modal-loader loading js-modal-loader" />');

		/**
		 * Init
		 */
		this.init = function () {
			_overlay.element.append( this.element );
		};

		/**
		 * Show
		 */
		this.show = function () {
			this.element.show();
		};

		/**
		 * Hide
		 */
		this.hide = function () {
			this.element.hide();
		};

		return this;
	}).call(_loader || {}, jQuery);


	// Plugin
	// ----------

	$.widget( "livestreet.lsModal", $.livestreet.lsComponent, {
		/**
		 * Дефолтные опции
		 */
		options: {
			selectors: {
				// Кнопка закрытия модального
				close: '[data-type=modal-close]'
			},
			// Анимация при показе
			show: {
				effect: 'slide',
				duration: 300,
				direction: 'up'
			},
			// Анимация при скрытии
			hide: {
				effect: 'drop',
				duration: 200,
				direction: 'up'
			},
			// Центрирование окна по вертикали
			center: true,

			// Ajax
			url: null,
			params: null,

			// Callbacks
			create: null,
			aftershow: null,
			afterhide: null
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function() {
			this._super();

			this._toggle = $( '[data-modal-target=' + this.element.attr('id') + ']' );

			// Переносим все модальные в оверлей
			if ( this.options.url ) {
				_overlay.element.append(this.element);
			} else {
				this.document.on('ready' + this.eventNamespace, function (e) {
					_overlay.element.append(this.element);
				}.bind(this));
			}

			// События
			// ----------

			// Триггер при клике по которому появляется модальное окно
			this._on( this._toggle, {
				click: function (e) {
					this.toggle();
					e.preventDefault();
				}
			});

			// Кнопки закрытия модального окна
			this._on( this.getElement( 'close' ), { click: this.hide });

			this._trigger("create", null, this);
		},

		/**
		 * Показавает модальное окно
		 */
		show: function () {
			var isOverlayVisible = _overlay.isVisible();

			_overlay.getActiveModal().lsModal('hide', false);

			if ( ! isOverlayVisible ) _overlay.element.css({ 'display' : 'block', 'visibility' : 'hidden' });
			this.element.css({ 'display' : 'block', 'visibility' : 'hidden' });

			this.element.css({
				// Центрируем по вертикали только если высота
				// модального меньше высоты окна
				'margin-top': this.options.center && this.element.outerHeight() < _overlay.element.height() ? ( _overlay.element.height() - this.element.outerHeight() ) / 2 : 50,
				// В FF и IE исправляет баг с анимацией
				'margin-left': ( _overlay.element.width() - this.element.outerWidth() ) / 2
			});

			if ( ! isOverlayVisible ) _overlay.element.css({ 'display' : 'none', 'visibility' : 'visible' });
			this.element.css({ 'display' : 'none', 'visibility' : 'visible' });

			// Показываем модальное
			if ( ! isOverlayVisible ) _overlay.show();

			this._show(this.element, this.options.show, function () {
				if (this.options.aftershow) {
					if (typeof( this.options.aftershow ) == 'string') {
						$.proxy(eval(this.options.aftershow), this);
					}
				}
				this._trigger("aftershow", null, this);
			}.bind(this));
		},

		/**
		 * Скрывает модальное окно
		 */
		hide: function ( hideOverlay ) {
			hideOverlay = typeof hideOverlay === 'undefined' ? true : hideOverlay;

			_overlay.element.css('overflow', 'hidden');

			// Если есть другие открытые окна, то оверлей не скрываем
			if ( hideOverlay && ! _overlay.getActiveModal().not(this.element).length ) _overlay.hide();

			this._hide(this.element, this.options.hide, function () {
				if ( this.options.url ) this.element.remove();

				this._trigger("afterhide", null, this);
			}.bind(this));
		},

		/**
		 * Показавает/скрывает модальное окно
		 */
		toggle: function () {
			this[ this.element.is(':visible') ? 'hide' : 'show' ]();
		}
	});


	// Ajax
	// ----------

	ls.modal = (function ($) {
		/**
		 * Load modal from url
		 *
		 * @param  {String} url     URL
		 * @param  {Object} params  Params
		 * @param  {Object} options Options
		 */
		this.load = function (url, params, options) {
			if ( ! _overlay.isVisible() ) _overlay.show();
			_overlay.getActiveModal().lsModal('hide', false);
			_loader.show();

			options.url = url;
			options.params = params || {};

			ls.ajax.load(url, params, function (result) {
				if (result.bStateError) {
					_loader.hide();
					_overlay.hide();
					ls.msg.error('Error', result.sMsg);
				} else {
					_loader.hide();
					$( $.trim( result['sText'] ) ).lsModal( options ).lsModal('show');
				}
			}, {
				error: function () {
					_loader.hide();
					_overlay.hide();
					ls.msg.error('Error');
				}
			});
		};

		/**
		 * Перезагрузка активного аякс окна
		 */
		this.reload = function () {
			var modal = _overlay.getActiveModal();

			if ( ! modal.length ) return;

			var options = modal.data('livestreet-modal').options;

			modal.remove();
			ls.modal.load( options.url, options.params, options );
		};

		return this;
	}).call(ls.modal || {}, jQuery);



	// События
	// ----------

	// Клик по оверлею
	_overlay.element.on('click', function (e) {
		if ( e.target == this ) {
			_overlay.getActiveModal().lsModal('hide');
			_loader.hide();
		}
	});

	// Закрытие модального по нажатию на Esc
	$(document).on('keyup.modal', function (e) {
		var modal = _overlay.getActiveModal();

		if ( e.keyCode === 27 && modal.length ) modal.lsModal('hide');
	});


	// Инициализация
	$(document).on('ready', function (e) {
		_overlay.init();
		_loader.init();

		// Ajax
		$(document).on('click', '[data-type=modal-toggle][data-modal-url]', function (e) {
			var options = ls.utils.getDataOptions($(this), 'modal');
			var params = ls.utils.getDataOptions($(this), 'param') || {};

			ls.modal.load(options.url, params, options);
			e.preventDefault();
		});
	});

	_window.on('resize', function () {
		_overlay.resize();
	});
})(jQuery);