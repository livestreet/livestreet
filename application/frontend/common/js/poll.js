/**
 * Опросы
 * 
 * @module ls/poll
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.poll = (function ($) {
	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Роутеры
		routers: {
			vote:   aRouter['ajax'] + 'poll/vote/',
			add:    aRouter['ajax'] + 'poll/create/',
			update: aRouter['ajax'] + 'poll/update/',
			remove: aRouter['ajax'] + 'poll/remove/'
		},

		// Селекторы
		selectors: {
			modal: '#modal-poll-create',
			
			poll: {
				poll:      '.js-poll',
				vote_form: '.js-poll-vote-form',
				vote:      '.js-poll-vote',
				abstain:   '.js-poll-abstain'
			},

			form: {
				form:        '#js-poll-form',
				list:        '.js-poll-form-list',
				item:        '.js-poll-form-list-item',
				item_remove: '.js-poll-form-list-item-remove',
				submit:      '.js-poll-form-submit'
			},

			answer: {
				list: '.js-poll-form-answer-list',
				add:  '.js-poll-form-answer-add',
				item: {
					item:   '.js-poll-form-answer-item',
					text:   '.js-poll-form-answer-item-text',
					id:     '.js-poll-form-answer-item-id',
					remove: '.js-poll-form-answer-item-remove'
				}
			},

			// Селекторы результата опроса
			result: {
				container: '.js-poll-result',
				item:      '.js-poll-result-item',
				sort:      '.js-poll-result-sort'
			}
		},

		// Максимальное кол-во вариантов ответов
		iMaxAnswers: 20
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var _this = this,
			oDocument = $(document);

		this.options = $.extend({}, defaults, options);

		/**
		 * Форма добавления
		 */

		// Сабмит формы
		oDocument.on('submit', this.options.selectors.form.form, function (e) {
			var oForm = $(this);

			_this[ oForm.data('action') == 'add' ? 'add' : 'update' ](oForm, $(_this.options.selectors.form.submit));

			e.preventDefault();
		});

		// Добавление варианта
		oDocument.on('click', this.options.selectors.answer.add, function () {
			_this.answerAdd($(_this.options.selectors.answer.list));
		});

		// Добавление варианта по нажатию Ctrl + Enter
		oDocument.on('keyup', this.options.selectors.answer.text, function (e) {
			var key = e.keyCode || e.which;

			if (e.ctrlKey && key == 13) {
				_this.answerAdd($(_this.options.selectors.answer.list));
			}
		});

		// Удаление варианта
		oDocument.on('click', this.options.selectors.answer.item.remove, function () {
			_this.answerRemove($(this));
		});

		// Удаление опроса
		oDocument.on('click', this.options.selectors.form.item_remove, function () {
			var oButton = $(this),
				oItem = oButton.closest(_this.options.selectors.form.item);

			_this.remove(oItem.data('poll-id'), oItem.data('poll-target-tmp'));
		});


		/**
		 * Опрос
		 */

		$(this.options.selectors.poll.poll).each(function () {
			var oPoll = $(this),
				iPollId = oPoll.data('poll-id');

			// Голосование за вариант
			oPoll.find(_this.options.selectors.poll.vote).on('click', function () {
				var form = oPoll.find('form');

				_this.vote(form, $(this));
			});

			// Воздержаться
			oPoll.find(_this.options.selectors.poll.abstain).on('click', function () {
				var form = oPoll.find('form');

				_this.vote(form, $(this), true);
			});

			// Сортировка
			oPoll.on('click', _this.options.selectors.result.sort, function () {
				_this.toggleSort(oPoll);
			});
		});
	};

	/**
	 * Добавление опроса
	 *
	 * @param {Object} oForm    Форма добавления опроса
	 * @param {Object} oButton  Кнопка добавления
	 */
	this.add = function(oForm, oButton) {
		this.answerIndex(oForm);

		ls.ajax.submit(this.options.routers.add, oForm, function(oResponse) {
			$(this.options.selectors.form.list).append(oResponse.sPollItem);
			$(this.options.selectors.modal).modal('hide');
		}.bind(this), { submitButton: oButton });
	};

	/**
	 * Обновление опроса
	 *
	 * @param {Object} oForm    Форма добавления опроса
	 * @param {Object} oButton  Кнопка сохранения
	 */
	this.update = function(oForm, oButton) {
		this.answerIndex(oForm);

		ls.ajax.submit(this.options.routers.update, oForm, function(oResponse) {
			$(this.options.selectors.form.item + '[data-poll-id=' + oResponse.iPollId + ']').replaceWith(oResponse.sPollItem);
			$(this.options.selectors.modal).modal('hide');
		}.bind(this), { submitButton: oButton });
	};

	/**
	 * Удаление опроса
	 *
	 * @param {Number} iId       ID ответа
	 * @param {String} sTempHash Хэш объекта
	 */
	this.remove = function(iId, sTempHash) {
		ls.ajax.load(this.options.routers.remove, { id: iId, tmp: sTempHash }, function (oResponse) {
			if (oResponse.bStateError) {
				ls.msg.error(null, oResponse.sMsg);
			} else {
				$(this.options.selectors.form.item + '[data-poll-id=' + iId + ']').fadeOut('slow', function() {
					$(this).remove();
				});
			}
		}.bind(this));
	};

	/**
	 * Добавляет вариант ответа
	 * 
	 * @param {Object} oAnswerList Блок с ответами
	 */
	this.answerAdd = function(oAnswerList) {
		// Ограничиваем кол-во добавляемых ответов
		if (oAnswerList.find(this.options.selectors.answer.item.item).length == this.options.iMaxAnswers) {
			ls.msg.error(null, ls.lang.get('poll.notices.error_answers_max'));
			return false;
		}

		var oAnswerItem = $(this.options.selectors.answer.item.item + '[data-is-template=true]').clone().removeAttr('data-is-template').show();

		oAnswerList.append(oAnswerItem);
		oAnswerItem.find(this.options.selectors.answer.item.text).focus();
	};

	/**
	 * Удаляет вариант ответа
	 * 
	 * @param {Object} oRemoveButton Кнопка удаления
	 */
	this.answerRemove = function(oRemoveButton) {
		oRemoveButton.closest(this.options.selectors.answer.item.item).fadeOut(200, function () {
			$(this).remove();
		});
	};

	/**
	 * Проставляет индексы инпутам ответа
	 *
	 * @param {Object} oForm Форма добавления опроса
	 */
	this.answerIndex = function(oForm) {
		oForm.find(this.options.selectors.answer.item.item).each(function (iIndex, oElement) {
			var oAnswerItem     = $(oElement),
				oAnswerItemId   = oAnswerItem.find(this.options.selectors.answer.item.id),
				oAnswerItemText = oAnswerItem.find(this.options.selectors.answer.item.text);

			oAnswerItemId.attr('name', 'answers[' + iIndex + '][id]');
			oAnswerItemText.attr('name', 'answers[' + iIndex + '][title]');
		}.bind(this));
	};

	/**
	 * Голосование в опросе
	 * 
	 * @param  {Object}  oForm     Форма с данными опроса
	 * @param  {Object}  oButton   Копка для анимации загрузки
	 * @param  {Boolean} bAbstain  Воздержаться при голосовании
	 */
	this.vote = function(oForm, oButton, bAbstain) {
		var oFormData = oForm.serializeJSON();

		ls.hook.marker('voteBefore');

		ls.ajax.submit(this.options.routers.vote, oForm, function(result) {
			var oPoll = $(this.options.selectors.poll.poll + '[data-poll-id=' + oFormData.id + ']').find(this.options.selectors.poll.vote_form);

			oPoll.html(result.sText);

			ls.hook.run('ls_pool_vote_after', [oForm, result], oPoll);
		}.bind(this), { submitButton: oButton, params: { abstain: bAbstain ? 1 : 0 } });
	};

	/**
	 * Сортировка результатов
	 * 
	 * @param  {Object} oPoll Блок опроса
	 */
	this.toggleSort = function(oPoll) {
		var oButton     = oPoll.find(this.options.selectors.result.sort),
			oPollResult = oPoll.find(this.options.selectors.result.container),
			aItems      = oPollResult.find(this.options.selectors.result.item),
			sSortType   = oButton.hasClass(ls.options.classes.states.active) ? 'poll-item-pos' : 'poll-item-count';

		aItems.sort(function (a, b) {
			a = $(a).data(sSortType);
    		b = $(b).data(sSortType);

		    if (a > b) {
		        return -1;
		    } else if (a < b) {
		        return 1;
		    } else {
		        return 0;
		    }
		});

		oButton.toggleClass(ls.options.classes.states.active);
		oPollResult.empty().append(aItems);
	};
	
	return this;
}).call(ls.poll || {},jQuery);