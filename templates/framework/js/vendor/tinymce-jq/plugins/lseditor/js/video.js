var lsVideoDialog = {
	init : function() {
		
	},

	insert : function(value) {
		if (value!='') {
			tinyMCEPopup.editor.execCommand('mceInsertContent', false, value); 
		}
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(lsVideoDialog.init, lsVideoDialog);

