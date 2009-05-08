function ajaxQuestionVote(idTopic,idAnswer) {	
	JsHttpRequest.query(
    	DIR_WEB_ROOT+'/include/ajax/questionVote.php',                       
        { idTopic: idTopic, idAnswer: idAnswer },
        function(result, errors) {  
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
        true
    );	
}