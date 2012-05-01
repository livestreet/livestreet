var ls = ls || {};

/**
* Опросы
*/
ls.poll = (function ($) {
	
	/**
	* Голосование в опросе
	*/
	this.vote = function(idTopic, idAnswer) {
		var url = aRouter['ajax']+'vote/question/';
		var params = {idTopic: idTopic, idAnswer: idAnswer};
		ls.hook.marker('voteBefore');
		ls.ajax(url, params, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);
				var area = $('#topic_question_area_'+idTopic);
				ls.hook.marker('voteDisplayBefore');
				area.html(result.sText);
				ls.hook.run('ls_pool_vote_after',[idTopic, idAnswer,result],area);
			}
		});
	};

	/**
	* Добавляет вариант ответа
	*/
	this.addAnswer = function() {
		if($("#question_list li").length == 20) {
			ls.msg.error(null, ls.lang.get('topic_question_create_answers_error_max'));
			return false;
		}
		var newItem = $("#question_list li:first-child").clone();
		newItem.find('a').remove();
		var removeAnchor = $('<a href="#"/>').text(ls.lang.get('delete')).click(function(e){
			e.preventDefault();
			return this.removeAnswer(e.target);
		}.bind(this));
		newItem.appendTo("#question_list").append(removeAnchor);
		newItem.find('input').val('');
		ls.hook.run('ls_pool_add_answer_after',[removeAnchor],newItem);
	};
	
	/**
	* Удаляет вариант ответа
	*/
	this.removeAnswer = function(obj) {
		$(obj).parent("li").remove();
		return false;
	};

	this.switchResult = function(obj, iTopicId) {
		if ($('#poll-result-sort-'+iTopicId).css('display') == 'none') {
			$('#poll-result-original-'+iTopicId).hide();
			$('#poll-result-sort-'+iTopicId).show();
			$(obj).toggleClass('active');
		} else {
			$('#poll-result-sort-'+iTopicId).hide();
			$('#poll-result-original-'+iTopicId).show();
			$(obj).toggleClass('active');
		}
		return false;
	};
	
	return this;
}).call(ls.poll || {},jQuery);