var ls = ls || {};

/**
* Голосование
*/
ls.vote = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Селекторы
		selectors : {
			vote:    '.js-vote',
			up:      '.js-vote-up',
			down:    '.js-vote-down',
			abstain: '.js-vote-abstain',
			count:   '.js-vote-count',
			rating:  '.js-vote-rating'
		},

		// Классы
		classes : {
			voted: 		'voted',
			plus: 		'voted-up',
			minus:  	'voted-down',
			positive:	'vote-count-positive',
			negative:  	'vote-count-negative',
			voted_zero: 'voted-zero',
			zero: 	 	'vote-count-zero',
			not_voted:  'not-voted'
		},

		type: {
			comment: {
				url:        aRouter['ajax'] + 'vote/comment/',
				targetName: 'idComment'
			},
			topic: {
				url:        aRouter['ajax'] + 'vote/topic/',
				targetName: 'idTopic'
			},
			blog: {
				url:        aRouter['ajax'] + 'vote/blog/',
				targetName: 'idBlog'
			},
			user: {
				url:        aRouter['ajax'] + 'vote/user/',
				targetName: 'idUser'
			}
		}
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, defaults, options);
		
		$(this.options.selectors.vote).each(function () {
			var oVote = $(this);

			var oVars = {
				vote:     oVote,
				up:       oVote.find(self.options.selectors.up),
				down:     oVote.find(self.options.selectors.down),
				abstain:  oVote.find(self.options.selectors.abstain),
				count:    oVote.find(self.options.selectors.count),
				rating:   oVote.find(self.options.selectors.rating),
				id:       oVote.data('vote-id'),
				type:     oVote.data('vote-type')
			};

			// Плюс
			oVars.up.on('click', function (e) {
				self.vote(oVars.id, 1, oVars.type, oVars);
				e.preventDefault();
			});

			// Минус
			oVars.down.on('click', function (e) {
				self.vote(oVars.id, -1, oVars.type, oVars);
				e.preventDefault();
			});

			// Воздержаться
			oVars.abstain.on('click', function (e) {
				self.vote(oVars.id, 0, oVars.type, oVars);
				e.preventDefault();
			});
		});
	};

	/**
	 * Голосование
	 * 
	 * @param  {Number} iTargetId ID объекта
	 * @param  {Number} iValue    Значение
	 * @param  {String} sType     Тип голосования
	 * @param  {Object} oVars     Переменные текущего голосования
	 */
	this.vote = function(iTargetId, iValue, sType, oVars) {
		var self = this;

		if ( ! this.options.type[sType] ) return false;

		var params = {
			value: iValue
		};
		params[this.options.type[sType].targetName] = iTargetId;

		ls.hook.marker('voteBefore');

		ls.ajax(this.options.type[sType].url, params, function (result) {
			var args = [iTargetId, iValue, sType, oVars, result];
			this.onVote.apply(this, args);
		}.bind(this));
	};

	/**
	 * Коллбэк вызываемый при успешном голосовании
	 * 
	 * @param  {Number} iTargetId ID объекта
	 * @param  {Number} iValue    Значение
	 * @param  {String} sType     Тип голосования
	 * @param  {Object} oVars     Переменные текущего голосования
	 * @param  {Object} result    Объект возвращемый сервером
	 */
	this.onVote = function(iTargetId, iValue, sType, oVars, result) {
		if (result.bStateError) {
			ls.msg.error(null, result.sMsg);
		} else {
			ls.msg.notice(null, result.sMsg);

			oVars.vote
				.addClass(this.options.classes.voted)
				.removeClass(this.options.classes.negative + ' ' + this.options.classes.positive + ' ' + this.options.classes.not_voted + ' ' + this.options.classes.zero);

			if (iValue > 0) {
				oVars.vote.addClass(this.options.classes.plus);
			} else if (iValue < 0) {
				oVars.vote.addClass(this.options.classes.minus);
			} else if (iValue == 0) {
				oVars.vote.addClass(this.options.classes.voted_zero);
			}
			
			if (oVars.count.length > 0 && result.iCountVote) {
				oVars.count.text(parseInt(result.iCountVote));
			}

			result.iRating = parseFloat(result.iRating);

			if (result.iRating > 0) {
				oVars.vote.addClass(this.options.classes.positive);
				oVars.rating.text('+' + result.iRating);
			} else if (result.iRating < 0) {
				oVars.vote.addClass(this.options.classes.negative);
				oVars.rating.text(result.iRating);
			} else if (result.iRating == 0) {
				oVars.vote.addClass(this.options.classes.zero);
				oVars.rating.text(0);
			}

			var method = 'onVote' + ls.tools.ucfirst(sType);

			if (typeof this[method] == 'function') {
				this[method].apply(this, [iTargetId, iValue, sType, oVars, result]);
			}
		}
	};

	/**
	 * Голосование за топик
	 * 
	 * @param  {Number} iTargetId ID объекта
	 * @param  {Number} iValue    Значение
	 * @param  {String} sType     Тип голосования
	 * @param  {Object} oVars     Переменные текущего голосования
	 * @param  {Object} result    Объект возвращемый сервером
	 */
	this.onVoteTopic = function(iTargetId, iValue, sType, oVars, result) {
		oVars.vote.addClass('js-tooltip-vote-topic').tooltip('enter');
	};

	return this;
}).call(ls.vote || {},jQuery);