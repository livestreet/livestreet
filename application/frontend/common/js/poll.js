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
		// Роутер голосования
		sRouterVoteUrl: aRouter['ajax'] + 'poll/vote/',

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
		sPollItemOptionSelector:    '.js-poll-item-option',
		sPollButtonVoteSelector:    '.js-poll-button-vote',
		sPollButtonAbstainSelector: '.js-poll-button-abstain',

		// Селекторы результата опроса
		sPollResultSelector:           '.js-poll-result',
		sPollResultItemSelector:       '.js-poll-result-item',
		sPollResultButtonSortSelector: '.js-poll-result-button-sort',

		// Html варианта ответа
		sAddItemHtml: '<li class="poll-add-item js-poll-add-item">' +
					      '<input type="checkbox" disabled="disabled">' +
					      '<input type="hidden" name="answers[_NUMBER_][id]" value="_ANSWER_ID_">' +
					      '<input type="text" name="answers[_NUMBER_][title]" class="poll-add-item-input js-poll-add-item-input" value="_ANSWER_TITLE_">' +
					      '<i class="icon-remove poll-add-item-remove js-poll-add-item-remove" title="' + ls.lang.get('delete') + '"></i>' +
					  '</li>'
	};

	this.aAnswersInit=[];
	this.iCountAnswers=0;

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, defaults, options);

		$(this.options.sPollSelector).each(function () {
			var oPoll = $(this),
				iPollId = oPoll.data('poll-id');

			// Голосование за вариант
			oPoll.find(self.options.sPollButtonVoteSelector).on('click', function () {
				var form = oPoll.find('form');

				self.vote(form,this);
			});
			// Воздержаться
			oPoll.find(self.options.sPollButtonAbstainSelector).on('click', function () {
				var form = oPoll.find('form');

				self.vote(form,this,true);
			});
			// Сортировка
			oPoll.on('click', self.options.sPollResultButtonSortSelector, function () {
				self.toggleSort(oPoll);
			});
		});
	};

	this.initFormUpdate = function() {
		this.initFormCreate();
	};

	this.initFormCreate = function() {
		var self = this;

		var oPollAdd=$('#form-poll-create').find(self.options.sAddSelector);
		$.each(self.aAnswersInit,function(k,v){
			self.addItem(oPollAdd,v);
		});


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
	};

	this.createPoll = function(form,button) {
		ls.ajax.submit(aRouter.ajax+'poll/create/', form, function(result){
			$('#poll-form-items').append(result.sPollItem);

			$('#modal-poll-create').modal('hide');
		},{ submitButton: $(button) });
	};

	this.updatePoll = function(form,button) {
		ls.ajax.submit(aRouter.ajax+'poll/update/', form, function(result){
			$('#poll-form-item-'+result.iPollId).replaceWith(result.sPollItem);

			$('#modal-poll-create').modal('hide');
		},{ submitButton: $(button) });
	};

	this.removePoll = function(id,tmp) {
		ls.ajax.load(aRouter.ajax+'poll/remove/', { id: id, tmp: tmp }, function(result){
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				$('#poll-form-item-'+id).fadeOut('slow', function() {
					$(this).remove();
				});
			}
		});
		return false;
	};

	/**
	 * Добавляет вариант ответа
	 * 
	 * @param  {Object} oPollAdd Блок добавления опроса
	 */
	this.addItem = function(oPollAdd,params) {
		var defaults = {
			number: "0",
			answer_id: '',
			answer_title: '',
			disable_remove: false,
			disable_update: false
		}
		params = $.extend({}, defaults, params);

		if(oPollAdd.find(this.options.sAddItemSelector).length == this.options.iMaxItems) {
			ls.msg.error(null, ls.lang.get('topic_question_create_answers_error_max'));
			return false;
		}

		var self = this,
			sTpl = this.options.sAddItemHtml;

		sTpl = sTpl.replace(/_NUMBER_/g, this.iCountAnswers);
		sTpl = sTpl.replace(/_ANSWER_ID_/g, params.answer_id);
		sTpl = sTpl.replace(/_ANSWER_TITLE_/g, params.answer_title);


		oNewItem = $(sTpl);
		if (params.disable_remove) {
			oNewItem.find(this.options.sAddItemRemoveSelector).remove();
		}
		if (params.disable_update) {
			oNewItem.find(this.options.sAddItemInputSelector).attr('disabled','disabled');
		}

		oPollAdd.find(this.options.sAddListSelector).append(oNewItem);
		oNewItem.find('input[type=text]').focus();
		this.iCountAnswers++;
	};

	this.addItemInit = function(params) {
		this.aAnswersInit.push(params);
	};

	this.clearItemInit = function() {
		this.aAnswersInit=[];
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
	 * @param  {Object} form Форма с данными опроса
	 * @param  {Object} button Копка для анимации загрузки
	 */
	this.vote = function(form,button,abstain) {
		form=$(form);
		var formData=form.serializeJSON();

		ls.hook.marker('voteBefore');

		ls.ajax.submit(this.options.sRouterVoteUrl, form, function(result){
			var oPoll = $('[data-poll-id=' + formData.id + ']');
			oPoll.html(result.sText);

			ls.hook.run('ls_pool_vote_after', [form, result], oPoll);
		}, { submitButton: $(button), params: { abstain: abstain ? 1 : 0 } });
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