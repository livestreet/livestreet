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
}