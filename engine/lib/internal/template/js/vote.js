var ls = ls || {};

/**
* Голосование
*/
ls.vote = (function ($) {
	/**
	* Опции
	*/
	this.options = {
		classes: {
			voted: 		'voted',
			plus: 		'voted-up',
			minus:  	'voted-down',
			positive:	'vote-count-positive',
			negative:  	'vote-count-negative',
			voted_zero: 'voted-zero',
			zero: 	 	'vote-count-zero',
			not_voted:  'not-voted'
		},
		prefix_area: 'vote_area_',
		prefix_total: 'vote_total_',
		prefix_count: 'vote_count_',

		type: {
			comment: {
				url: aRouter['ajax']+'vote/comment/',
				targetName: 'idComment'
			},
			topic: {
				url: aRouter['ajax']+'vote/topic/',
				targetName: 'idTopic'
			},
			blog: {
				url: aRouter['ajax']+'vote/blog/',
				targetName: 'idBlog'
			},
			user: {
				url: aRouter['ajax']+'vote/user/',
				targetName: 'idUser'
			}
		}
	};

	this.vote = function(idTarget, objVote, value, type) {
		if (!this.options.type[type]) return false;

		objVote = $(objVote);
		var params = {};
		params['value'] = value;
		params[this.options.type[type].targetName] = idTarget;
		
		ls.hook.marker('voteBefore');
		ls.ajax(this.options.type[type].url, params, function(result) {
			var args = [idTarget, objVote, value, type, result];
			this.onVote.apply(this,args);
		}.bind(this));
		return false;
	}

	this.onVote = function(idTarget, objVote, value, type, result) {
		if (result.bStateError) {
			ls.msg.error(null, result.sMsg);
		} else {
			ls.msg.notice(null, result.sMsg);
			
			var divVoting = $('#'+this.options.prefix_area+type+'_'+idTarget);

			divVoting.addClass(this.options.classes.voted);

			if (value > 0) {
				divVoting.addClass(this.options.classes.plus);
			}
			if (value < 0) {
				divVoting.addClass(this.options.classes.minus);
			}
			if (value == 0) {
				divVoting.addClass(this.options.classes.voted_zero);
			}
			
			var divTotal = $('#'+this.options.prefix_total+type+'_'+idTarget);
			var divCount = $('#'+this.options.prefix_count+type+'_'+idTarget);
			
			if (divCount.length>0 && result.iCountVote) {
				divCount.text(parseInt(result.iCountVote));
			}

			result.iRating = parseFloat(result.iRating);

			divVoting.removeClass(this.options.classes.negative);
			divVoting.removeClass(this.options.classes.positive);
			divVoting.removeClass(this.options.classes.not_voted);

			if (result.iRating > 0) {
				divVoting.addClass(this.options.classes.positive);
				divTotal.text('+'+result.iRating);
			}else if (result.iRating < 0) {
				divVoting.addClass(this.options.classes.negative);
				divTotal.text(result.iRating);
			}else if (result.iRating == 0) {
				divVoting.addClass(this.options.classes.zero);
				divTotal.text(0);
			}

			var method='onVote'+ls.tools.ucfirst(type);
			if ($.type(this[method])=='function') {
				this[method].apply(this,[idTarget, objVote, value, type, result]);
			}

		}
		
		$(this).trigger('vote',[idTarget, objVote, value, type, result]);
	}



	this.onVoteUser = function(idTarget, objVote, value, type, result) {
		$('#user_skill_'+idTarget).text(result.iSkill);
	}

	return this;
}).call(ls.vote || {},jQuery);