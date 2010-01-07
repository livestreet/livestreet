var lsImageDialog = {
	init : function() {
		
	},

	insert : function() {
		
	},
	
	ajaxUploadImg: function(value) {    
    	var req = new JsHttpRequest();    
    	req.onreadystatechange = function() {
        	if (req.readyState == 4) { 
            	if (req.responseJS.bStateError) {
            		tinyMCEPopup.getWindowArg('msgErrorBox').alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);            		
            	} else {            		          		
            		tinyMCEPopup.editor.execCommand('mceInsertContent', false, req.responseJS.sText); 
            		tinyMCEPopup.close();   	
            	}
        	}
    	}       	
    	req.open(null, tinyMCEPopup.getWindowArg('DIR_WEB_ROOT')+'/include/ajax/uploadImg.php', true);    
    	req.send( { value: value, security_ls_key: tinyMCEPopup.getWindowArg('LIVESTREET_SECURITY_KEY') } );
	}
};

tinyMCEPopup.onInit.add(lsImageDialog.init, lsImageDialog);

