function ajaxQuestionVote(idTopic,idAnswer) {
	new Request.JSON({
		url: aRouter['ajax']+'vote/question/',
		noCache: true,
		data: { idTopic: idTopic, idAnswer: idAnswer, security_ls_key: LIVESTREET_SECURITY_KEY },
		onSuccess: function(result){
			if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}
            if (result.bStateError) {
            	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
            } else {            	
            	msgNoticeBox.alert(result.sMsgTitle,result.sMsg);
            	if ($('topic_question_area_'+idTopic)) {
            		$('topic_question_area_'+idTopic).set('html',result.sText);
            	}  
            }
		},
		onFailure: function(){
			msgErrorBox.alert('Error','Please try again later');
		}
	}).send();
}