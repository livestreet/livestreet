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


// для опроса
function addField(btn){
        tr = btn;
        while (tr.tagName != 'TR') tr = tr.parentNode;
        var newTr = tr.parentNode.insertBefore(tr.cloneNode(true),tr.nextSibling);
        checkFieldForLast();
}
function checkFieldForLast(){	
        btns = document.getElementsByName('drop_answer');      
        for (i = 0; i < btns.length; i++){
        	btns[i].disabled = false;            
        }
        if (btns.length<=2) {
        	btns[0].disabled = true;
        	btns[1].disabled = true;
        }
}
function dropField(btn){	
        tr = btn;
        while (tr.tagName != 'TR') tr = tr.parentNode;
        tr.parentNode.removeChild(tr);
        checkFieldForLast();
}