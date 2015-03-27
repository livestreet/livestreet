/**
 * Управление опросами
 *
 * @module ls/poll-manage
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsPollManage", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Мод. окно добавления опроса
				modal_add: aRouter.ajax + 'poll/modal-create',

				// Мод. окно редактирования опроса
				modal_edit: aRouter.ajax + 'poll/modal-update',

				// Добавление
				add: aRouter.ajax + 'poll/create/',

				// Редактирование
				update: aRouter.ajax + 'poll/update/',

				// Удаление
				remove: aRouter.ajax + 'poll/remove/'
			},

			// Селекторы
			selectors: {
				// Список добавленных опросов
				list: '.js-poll-manage-list',

				// Опрос
				item: '.js-poll-manage-item',

				// Кнопка удаления опроса
				item_remove: '.js-poll-manage-item-remove',

				// Кнопка редактирования опроса
				item_edit: '.js-poll-manage-item-edit',

				// Кнопка добавления
				add: '.js-poll-manage-add',

				form: {
					form:        '#js-poll-form',
					add:         '.js-poll-form-answer-add',
					list:        '.js-poll-form-answer-list',
					item:        '.js-poll-form-answer-item',
					item_id:     '.js-poll-form-answer-item-id',
					item_text:   '.js-poll-form-answer-item-text',
					item_remove: '.js-poll-form-answer-item-remove',
					submit:      '.js-poll-form-submit'
				}
			},
			// Максимальное кол-во вариантов которое можно добавить в опрос
			max: 20
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			this.elements = {
				list:   this.element.find( this.options.selectors.list ),
				add:    this.element.find( this.options.selectors.add ),
				remove: this.element.find( this.options.selectors.item_remove ),
				edit:   this.element.find( this.options.selectors.item_edit )
			};

			this.id = this.element.data('target-id');
			this.type = this.element.data('type');

			//
			// События
			//

			// Показывает форму добавления
			this._on( this.elements.add, { 'click': this.formShowAdd } );

			// Показывает форму редактирования опроса
			this.element.on( 'click' + this.eventNamespace, this.options.selectors.item_edit, function () {
				_this.formShowEdit( $(this).data('poll-id'), $(this).data('poll-target-tmp') )
			});

			// Удаляет опрос
			this.element.on( 'click' + this.eventNamespace, this.options.selectors.item_remove, function () {
				_this.remove( $(this) )
			});
		},

		/**
		 * Показывает форму
		 *
		 * @param {String} url    Ссылка возвращающая модальное окно
		 * @param {Object} params Параметры
		 */
		formShow: function( url, params ) {
			var _this = this;

			ls.modal.load( url, params, {
				aftershow: function ( e, modal ) {
					var form = modal.element.find( _this.option( 'selectors.form.form' ) ),
						list = form.find( _this.option( 'selectors.form.list' ) );

					// Отправка формы
					form.on( 'submit', function (e) {
						_this[ form.data('action') ]( form, list, modal );
						e.preventDefault();
					});

					// Добавление ответа
					form.find( _this.option( 'selectors.form.add' ) ).on( 'click', _this.answerAdd.bind( _this, list ));
					form.on( 'keydown', _this.option( 'selectors.form.item_text' ) , 'ctrl+return', _this.answerAdd.bind( _this, list ) );

					// Удаление
					form.on( 'click', _this.option( 'selectors.form.item_remove' ),	function () {
						_this.answerRemove( list, $( this ) );
					});
				},
				center: false
			});
		},

		/**
		 * Показывает форму добавления
		 */
		formShowAdd: function() {
			this.formShow( this.option( 'urls.modal_add' ), { target_type: this.type, target_id: this.id } );
		},

		/**
		 * Показывает форму редактирования
		 *
		 * @param {Number} id   ID опроса
		 * @param {String} hash Хэш опроса
		 */
		formShowEdit: function( id, hash ) {
			this.formShow( this.option( 'urls.modal_edit' ), { id: id, target_tmp: hash } );
		},

		/**
		 * Добавляет вариант ответа
		 *
		 * @param {jQuery} list Список ответов
		 */
		answerAdd: function( list ) {
			var answers = list.find( this.option( 'selectors.form.item' ) );

			// Ограничиваем кол-во добавляемых ответов
			if ( answers.length == this.option( 'max' ) ) {
				ls.msg.error( null, ls.lang.get( 'poll.notices.error_answers_max', { count: this.option( 'max' ) } ) );
				return;
			} else if ( answers.length == 2 ) {
				answers.find( this.option( 'selectors.form.item_remove' ) ).show();
			}

			var item = $( this.option( 'selectors.form.item' ) + '[data-is-template=true]' ).clone().removeAttr( 'data-is-template' ).show();

			list.append( item );
			item.find( this.option( 'selectors.form.item_text' ) ).focus();
		},

		/**
		 * Удаляет вариант ответа
		 *
		 * @param {jQuery} list   Список ответов
		 * @param {jQuery} button Кнопка удаления
		 */
		answerRemove: function( list, button ) {
			var answers = list.find( this.option( 'selectors.form.item' ) );

			if ( answers.length == 3 ) {
				answers.find( this.option( 'selectors.form.item_remove' ) ).hide();
			}

			button.closest( this.option( 'selectors.form.item' ) ).fadeOut(200, function () {
				$(this).remove();
			});
		},

		/**
		 * Проставляет индексы инпутам ответа
		 *
		 * @param {jQuery} list Список ответов
		 */
		answerIndex: function( list ) {
			list.find( this.option( 'selectors.form.item' ) ).each(function ( index, item ) {
				var item = $(item),
					id   = item.find( this.option( 'selectors.form.item_id' ) ),
					text = item.find( this.option( 'selectors.form.item_text' ) );

				id.attr( 'name', 'answers[' + index + '][id]' );
				text.attr( 'name', 'answers[' + index + '][title]' );
			}.bind(this));
		},

		/**
		 * Добавляет опрос
		 *
		 * @param {jQuery} form  Форма
		 * @param {jQuery} list  Список ответов
		 * @param {jQuery} modal Модальное окно с формой
		 */
		add: function( form, list, modal ) {
			this.answerIndex( list );

			ls.ajax.submit( this.option( 'urls.add' ), form, function( response ) {
				this.elements.list.append( response.sPollItem );
				modal.hide();
			}.bind(this), { submitButton: modal.element.find( 'button[type=submit]' ) });
		},

		/**
		 * Обновление опроса
		 *
		 * @param {jQuery} form  Форма
		 * @param {jQuery} list  Список ответов
		 * @param {jQuery} modal Модальное окно с формой
		 */
		update: function( form, list, modal ) {
			this.answerIndex( list );

			ls.ajax.submit( this.option( 'urls.update' ), form, function( response ) {
				this.elements.list.find( this.option( 'selectors.item' ) + '[data-poll-id=' + response.iPollId + ']' ).replaceWith( response.sPollItem );
				modal.hide();
			}.bind(this), { submitButton: modal.element.find( 'button[type=submit]' ) });
		},

		/**
		 * Удаляет опрос
		 *
		 * @param {jQuery} button Кнопка удаления
		 */
		remove: function( button ) {
			ls.ajax.load( this.option( 'urls.remove' ), { id: button.data('poll-id'), tmp: button.data('poll-target-tmp') }, function ( response ) {
				button.closest( this.option( 'selectors.item' ) ).fadeOut('slow', function() {
					$(this).remove();
				});
			}.bind(this));
		},
	});
})(jQuery);