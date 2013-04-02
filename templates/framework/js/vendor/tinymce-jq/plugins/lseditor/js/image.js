var lsImageDialog = {
	init : function() {
		
	},

	insert : function() {
		
	},
	
	ajaxUploadImg: function(form) {
		if (typeof(form)=='string') {
			form=jQuery('#'+form);
		}
		
		var options={
			type: 'POST',
			url: tinyMCEPopup.getWindowArg('ajaxurl')+'upload/image/',
			dataType: 'json',
			data: { security_ls_key: tinyMCEPopup.getWindowArg('LIVESTREET_SECURITY_KEY') },
			success: function(response){
				if (response.bStateError) {
					tinyMCEPopup.getWindowArg('msgErrorBox').alert(req.responseJS.sMsgTitle,response.sMsg);
				} else {
					tinyMCEPopup.editor.execCommand('mceInsertContent', false, response.sText);
					tinyMCEPopup.close();
				}
			}
		}
		
		form.ajaxSubmit(options);
    }
};

tinyMCEPopup.onInit.add(lsImageDialog.init, lsImageDialog);

