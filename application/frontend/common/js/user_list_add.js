/**
 * Пополняемый список пользователей
 *
 * @module ls/user_list_add
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.user_list_add", {
		/**
		 * Дефолтные опции
		 */
		options: {
			urls: {
				add: null,
				remove: null
			},
			// Селекторы
			selectors: {
				// Блок со списком объектов
				list: '.js-user-list-add-users',
				// Объект
				item: '.js-user-list-small-item',
				// Кнопка удаления объекта
				item_remove: '.js-user-list-add-user-remove',
				// Сообщение о пустом списке
				empty: '.js-user-list-small-empty',
				// Форма добавления
				form: '.js-user-list-add-form'
			},
			// Анимация при скрытии объекта
			hide: {
				effect: 'slide',
				duration: 200,
				direction: 'left'
			},
			// Кастомные аякс параметры
			params: {}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			// Получаем список элементов
			this.elements = {};
			this.elements.container = this.element;
			this.elements.list      = this.elements.container.find(this.options.selectors.list);
			this.elements.empty     = this.elements.container.find(this.options.selectors.empty);
			this.elements.form      = this.elements.container.find(this.options.selectors.form);
			this.elements.form_text = this.elements.form.find('input[type=text]');

			// Получаем кастомные аякс параметры
			this.options.params = $.extend({}, this.options.params, ls.utils.getDataOptions(this.elements.container, 'param'));

			// Ивент удаления
			this.elements.list.on('click' + this.eventNamespace, this.options.selectors.item_remove, function (e) {
				_this.remove( $(this).data('user-id') );
				e.preventDefault();
			});

			// Ивент добавления
			this.elements.form.on('submit' + this.eventNamespace, function (e) {
				var aItemList = _this.getItems();

				if ( aItemList.length ) {
					ls.utils.formLock(_this.elements.form);
					_this.add( aItemList );
				}

				e.preventDefault();
			});
		},

		/**
		 * Получает объекты для добавления
		 *
		 * @return {Array} Массив с объектами
		 */
		getItems: function () {
			var sValue = this.elements.form_text.val();

			return $.map(sValue.split(','), function(sItem, iIndex) {
				return $.trim(sItem) || null;
			});
		},

		/**
		 * Добавление объекта
		 */
		add: function(aUserList) {
			if ( ! aUserList ) return;

			var oParams = {
				aUserList: aUserList,
			};

			oParams = $.extend({}, oParams, this.options.params);

			ls.ajax.load(this.options.urls.add, oParams, function(oResponse) {
				this._onAdd(oResponse);

				this._trigger("afteradd", null, { context: this, response: oResponse });
			}.bind(this));
		},

		_onUserAdd: function (oItem) {
			return;
		},

		/**
		 * Коллбэк вызываемый при добавлении объекта
		 */
		_onAdd: function (oResponse) {
			var aUsers = this._getUsersAll();

			// Составляем список добавляемых объектов
			var sItemsHtml = $.map(oResponse.aUserList, function (oItem, iIndex) {
				if (oItem.bStateError) {
					ls.msg.error(null, oItem.sMsg);
				} else {
					ls.msg.notice(null, ls.lang.get('common.success.add'));

					this._trigger("afterobjectadd", null, { context: this, oItem: oItem, response: oResponse });

					this._onUserAdd(oItem);

					// Не добавляем юзера если он уже есть в списке
					if ( aUsers.filter('[data-user-id=' + oItem.iUserId + ']').length ) {
						return null;
					} else {
						return oItem.sHtml;
					}
				}
			}.bind(this)).join('');

			if ( sItemsHtml ) {
				// Скрываем сообщение о пустом списке
				this.elements.empty.hide();
				// Добавляем объекты
				this.elements.list.show().prepend(sItemsHtml);
			}

			ls.utils.formUnlock( this.elements.form );
			this.elements.form_text.focus().val('');
		},

		/**
		 * Удаление объекта
		 */
		remove: function(iUserId) {
			if ( ! this.options.urls.remove ) return;

			var _this = this,
				oParams = {
					iUserId: iUserId
				};

			oParams = $.extend({}, oParams, this.options.params);

			ls.ajax.load(this.options.urls.remove, oParams, function(oResponse) {
				ls.msg.notice(null, ls.lang.get('common.success.remove'));

				this._hide( this._getUserById( iUserId ), this.options.hide, function () {
					$(this).remove();

					// Скрываем список если объектов в нем больше нет
					if ( ! _this.elements.list.find(_this.options.selectors.item).length ) {
						_this.elements.list.hide();
						_this.elements.empty.show();
					}
				});

				this._trigger("afterremove", null, { context: this, response: oResponse, oParams: oParams });
			}.bind(this));
		},

		/**
		 * Получает пользователя по ID
		 *
		 * @private
		 * @param  {Number} iUserId , ID объекта
		 * @return {jQuery}           Объект
		 */
		_getUserById: function(iUserId) {
			return this.elements.list.find(this.options.selectors.item + '[data-user-id=' + iUserId + ']');
		},

		/**
		 * Получает всех пользователей
		 */
		_getUsersAll: function() {
			return this.elements.list.find(this.options.selectors.item);
		}
	});
})(jQuery);