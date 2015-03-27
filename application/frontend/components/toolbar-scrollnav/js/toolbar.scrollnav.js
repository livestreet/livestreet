/**
 * Навигация по топикам
 *
 * @module ls/toolbar/topics
 * @dependencies Factory Widget, scrollTo, hotkeys, lsPagination
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 *
 * TODO: Унифицировать
 * TODO: Добавить коллбэки и хуки
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsToolbarTopics", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Хоткеи
			keys: {
				// Комбинация клавиш для перехода к следующему объекту
				next: 'ctrl+shift+down',

				// Комбинация клавиш для перехода к предыдущему объекту
				prev: 'ctrl+shift+up'
			},

			// Селекторы
			selectors: {
				// Кнопка прокрутки к следующему объекту
				next: '.js-toolbar-topics-next',

				// Кнопка прокрутки к предыдущему объекту
				prev: '.js-toolbar-topics-prev',

				// Объект
				item: '.js-topic',

				// Пагинация
				pagination: '.js-pagination-topics'
			},

			// Продолжительность прокрутки, мс
			duration: 500,

			// Параметр в хэше урл указывающий к какому объекту прокручивать
			// после загрузки страницы (first или last)
			param: 'gotopic'
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			// Элементы
			this.elements = {
				next:       this.element.find(this.options.selectors.next),
				prev:       this.element.find(this.options.selectors.prev),
				pagination: $(this.options.selectors.pagination).eq(0),
				items:      $(this.options.selectors.item)
			};

			// Текущий объект
			this.reset();

			// Обработка параметров в хэше url'а
			this._checkUrl();

			//
			// События
			//

			// Обработка нажатий по кнопкам след/пред
			this._on( this.elements.next, { 'click': this.next } );
			this._on( this.elements.prev, { 'click': this.prev } );

			// Обработка хоткеев
			this.document.bind( 'keydown' + this.eventNamespace, this.options.keys.next, this.next.bind(this) );
			this.document.bind( 'keydown' + this.eventNamespace, this.options.keys.prev, this.prev.bind(this) );
		},

		/**
		 * Обработка параметров в хэше url'а
		 */
		_checkUrl: function () {
			// Проверяем наличие параметра options.param в хэше url'а
			var goto = new RegExp( this.option( 'param' ) + '=(last|first)', 'i' ).exec( location.hash );

			if ( goto ) {
				// С помощью goto[1] получаем значение параметра options.param (first или last)
				var item = this.elements.items[ goto[1] ]();

				// Скроллим через небольшой промежуток времени,
				// чтобы страница успела прогрузиться
				setTimeout( this.scroll.bind(this, item), 500 );
			}
		},

		/**
		 * Переход к объекту
		 *
		 * @param {String} name Название функции
		 */
		_go: function ( name ) {
			// Получаем объект к которому нужно перейти
			var next = ! this.current ? this.elements.items.eq(0) : this.current[ name ]( this.options.selectors.item );

			// Скроллим к след/пред объекту
			// Если на текущей странице больше нет объектов, переходим на другую
			next.length ? this.scroll( next ) : this.elements.pagination.lsPagination( name, true );
		},

		/**
		 * Переход к следующему объекту
		 */
		next: function () {
			this._go( 'next' );
		},

		/**
		 * Переход к предыдущему объекту
		 */
		prev: function () {
			this._go( 'prev' );
		},

		/**
		 * Скролл к текущему объекту
		 */
		scroll: function ( item ) {
			$.scrollTo( this.current = item, this.options.duration, { offset: 0 } );
		},

		/**
		 * Сброс текущего активного объекта
		 */
		reset: function () {
			this.current = null;
		}
	});
})(jQuery);