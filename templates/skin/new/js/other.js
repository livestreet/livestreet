function ajaxTextPreview(textId,save) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;  
                    
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert('Ошибка','Возникли проблемы при обработке предпросмотра');            	
            } else {               	
            	document.getElementById('text_preview').innerHTML = req.responseJS.sText;    
            	//$('text_preview2').set('text', req.responseJS.sText);    	
            }
        }
    }      
    var text;
    
	if (BLOG_USE_TINYMCE && tinyMCE && (ed=tinyMCE.get(textId))) {
		text = ed.getContent();
	} else {
		text = $(textId).value;
	
	} 
    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/textPreview.php', true);    
    req.send( { text: text, save: save } );
}