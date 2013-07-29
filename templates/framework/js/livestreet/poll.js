/**
 * Опросы
 */

var ls = ls || {};


ls.poll = (function ($) {
	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Роутер голосования
		sRouterVoteUrl: aRouter['ajax'] + 'vote/question/',

		// Максимальное кол-во вариантов ответов
		iMaxItems: 20,

		// Селекторы добавления опроса
		sAddSelector:           '.js-poll-add',
		sAddListSelector:       '.js-poll-add-list',
		sAddItemSelector:       '.js-poll-add-item',
		sAddItemRemoveSelector: '.js-poll-add-item-remove',
		sAddItemInputSelector:  '.js-poll-add-item-input',
		sAddButtonSelector:     '.js-poll-add-button',

		// Селекторы опроса
		sPollSelector:              '.js-poll',
		sPollListSelector:          '.js-poll-list',
		sPollItemSelector:          '.js-poll-item',
		sPollItemOptionSelector:    '.js-poll-item-option',
		sPollButtonVoteSelector:    '.js-poll-button-vote',
		sPollButtonAbstainSelector: '.js-poll-button-abstain',

		// Селекторы результата опроса
		sPollResultSelector:           '.js-poll-result',
		sPollResultItemSelector:       '.js-poll-result-item',
		sPollResultButtonSortSelector: '.js-poll-result-button-sort',

		// Html варианта ответа
		sAddItemHtml: '<li class="poll-add-item js-poll-add-item">' +
					      '<input type="text" name="answer[]" class="poll-add-item-input js-poll-add-item-input">' +
					      '<i class="icon-remove poll-add-item-remove js-poll-add-item-remove" title="' + ls.lang.get('delete') + '"></i>' +
					  '</li>'
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, defaults, options);

		// Добавление
		$(this.options.sAddSelector).each(function () {
			var oPollAdd = $(this);

			// Добавление варианта
			oPollAdd.find(self.options.sAddButtonSelector).on('click', function () {
				self.addItem(oPollAdd);
			}.bind(self));

			// Добавление варианта по нажатию Ctrl + Enter
			oPollAdd.on('keyup', self.options.sAddItemInputSelector, function (e) {
				var key = e.keyCode || e.which;

				if (e.ctrlKey && key == 13) {
					self.addItem(oPollAdd);
				}
			});

			// Удаление
			oPollAdd.on('click', self.options.sAddItemRemoveSelector, function () {
				self.removeItem(this);
			});
		});

		// Голосование
		$(this.options.sPollSelector).each(function () {
			var oPoll = $(this),
				iPollId = oPoll.data('poll-id');

			// Голосование за вариант
			oPoll.find(self.options.sPollButtonVoteSelector).on('click', function () {
				var iCheckedItemId = oPoll.find(self.options.sPollItemOptionSelector + ':checked').val();

				if (iCheckedItemId) {
					self.vote(iPollId, iCheckedItemId);
				} else {
					return false;
				}
			});

			// Воздержаться
			oPoll.find(self.options.sPollButtonAbstainSelector).on('click', function () {
				self.vote(iPollId, -1);
			});

			// Воздержаться
			oPoll.on('click', self.options.sPollResultButtonSortSelector, function () {
				self.toggleSort(oPoll);
			});
		});
	};

	/**
	 * Добавляет вариант ответа
	 * 
	 * @param  {Object} oPollAdd Блок добавления опроса
	 */
	this.addItem = function(oPollAdd) {
		if(oPollAdd.find(this.options.sAddItemSelector).length == this.options.iMaxItems) {
			ls.msg.error(null, ls.lang.get('topic_question_create_answers_error_max'));
			return false;
		}

		var self = this,
			oNewItem = $(this.options.sAddItemHtml);

		oPollAdd.find(this.options.sAddListSelector).append(oNewItem);
		oNewItem.find('input[type=text]').focus();
	};
	
	/**
	 * Удаляет вариант ответа
	 * 
	 * @param  {Number} oRemoveButton Кнопка удаления
	 */
	this.removeItem = function(oRemoveButton) {
		$(oRemoveButton).closest(this.options.sAddItemSelector).remove();
	};
	
	/**
	 * Голосование в опросе
	 * 
	 * @param  {Number} iPollId ID опроса
	 * @param  {Number} iItemId ID выбранного пункта
	 */
	this.vote = function(iPollId, iItemId) {
		var oParams = {
			idTopic: iPollId, 
			idAnswer: iItemId
		};

		ls.hook.marker('voteBefore');

		ls.ajax(this.options.sRouterVoteUrl, oParams, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var oPoll = $('[data-poll-id=' + iPollId + ']');
				oPoll.html(result.sText);

				ls.msg.notice(null, result.sMsg);

				ls.hook.run('ls_pool_vote_after', [iPollId, iItemId, result], oPoll);
			}
		});
	};

	/**
	 * Сортировка результатов
	 * 
	 * @param  {Object} oPoll Блок опроса
	 */
	this.toggleSort = function(oPoll) {
		var oButton     = oPoll.find(this.options.sPollResultButtonSortSelector),
			oPollResult = oPoll.find(this.options.sPollResultSelector),
			aItems      = oPollResult.find(this.options.sPollResultItemSelector),
			sSortType   = oButton.hasClass('active') ? 'poll-item-pos' : 'poll-item-count';

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

		oButton.toggleClass('active');
		oPollResult.empty().append(aItems);
	};
	
	return this;
}).call(ls.poll || {},jQuery);