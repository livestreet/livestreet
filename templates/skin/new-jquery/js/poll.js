var ls = ls || {};

/**
* Опросы
*/
ls.poll = (function ($) {
	
	/**
	* Голосование в опросе
	*/
	this.vote = function(idTopic, idAnswer) {
		ls.ajax(aRouter['ajax']+'vote/question/', {idTopic: idTopic, idAnswer: idAnswer}, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				ls.msg.notice(null, result.sMsg);
				$('#topic_question_area_'+idTopic).html(result.sText);
			}
		});
	}

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
		newItem.appendTo("#question_list").append($('<a href="#" class="dashed">'+ls.lang.get('delete')+'</a>').click(function(ev){
			return this.removeAnswer(ev.target);
		}.bind(this)));
		newItem.find('input').val('');
	}
	
	/**
	* Удаляет вариант ответа
	*/
	this.removeAnswer = function(obj) {
		$(obj).parent("li").remove();
		return false;
	}
	
	return this;
}).call(ls.poll || {},jQuery);