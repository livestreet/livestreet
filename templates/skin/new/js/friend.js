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



function ajaxToggleUserFrend(obj,idUser) {   
	obj=$(obj);
	JsHttpRequest.query(
    	DIR_WEB_ROOT+'/include/ajax/userFriend.php',                       
        { idUser: idUser },
        function(result, errors) {  
        	if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}
            if (result.bStateError) {
            	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
            } else {            	
            	msgNoticeBox.alert(result.sMsgTitle,result.sMsg);
            	if (obj)  {
            		obj.set('text',result.sToggleText);
            		if (result.bState) {
            			obj.getParent('li').removeClass('add');
            			obj.getParent('li').addClass('del');
            		} else {
            			obj.getParent('li').removeClass('del');
            			obj.getParent('li').addClass('add');
            		}
            	}
            }                               
        },
        true
    );
	
	
	
	
	
	
	
	/*
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg); 
            	
            	if (req.responseJS.bState) {
            		document.getElementById('user_frend_add').style.display="none";
            		document.getElementById('user_frend_del').style.display="inline";
            	} 
            	if (!req.responseJS.bState) {
            		document.getElementById('user_frend_add').style.display="inline";
            		document.getElementById('user_frend_del').style.display="none";
            	}            	
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/userFrend.php', true);    
    req.send( { idUser: idUser, type: type } );
    */
}