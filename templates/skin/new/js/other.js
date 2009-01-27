function ajaxTextPreview(textId,save) { 
	var text;    
	if (BLOG_USE_TINYMCE && tinyMCE && (ed=tinyMCE.get(textId))) {
		text = ed.getContent();
	} else {
		text = $(textId).value;	
	}	
	JsHttpRequest.query(
    	DIR_WEB_ROOT+'/include/ajax/textPreview.php',                       
        { text: text, save: save },
        function(result, errors) {  
        	if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}
            if (result.bStateError) {
            	msgErrorBox.alert('Ошибка','Возникли проблемы при обработке предпросмотра');
            } else {    	
            	$('text_preview').set('html',result.sText);
            }                               
        },
        true
    );
}