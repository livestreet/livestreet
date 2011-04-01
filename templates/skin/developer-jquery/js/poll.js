function ajaxPollVote(idTopic, idAnswer) {
	$.post(aRouter['ajax']+'vote/question/', { idTopic: idTopic, idAnswer: idAnswer, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result) {
		if (result.bStateError) {
			$.notifier.error(null, result.sMsg);
		} else {            	
			$.notifier.notice(null, result.sMsg);
			$('#topic_question_area_'+idTopic).html(result.sText);
		}
	});
}

// Добавляет вариант ответа
function addField() {
	if($("#question_list li").length == 20) {
		$.notifier.error(null, LANG_POLL_ERROR);
		return false;
	}
	$("#question_list li:first-child").clone().appendTo("#question_list").append($('<a href="#" style="margin-left: 5px;">'+LANG_DELETE+'</a>').bind("click", deleteField));
}

// Удаляет вариант ответа
function deleteField() {
	$(this).parent("li").remove(); 
	return false;
}