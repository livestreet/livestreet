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



function checkAllTalk(checkbox) {
	$$('.form_talks_checkbox').each(function(chk){
		if (checkbox.checked) {
			chk.checked=true;
		} else {
			chk.checked=false;
		}		
	});	
}



function showImgUploadForm() {
	var divOverlay=$$('.overlay')[0];
	var divForm=$('window_load_img');
	divOverlay.setStyle('display','block');
	divForm.setStyle('display','block');	
}

function hideImgUploadForm() {
	var divOverlay=$$('.overlay')[0];
	var divForm=$('window_load_img');
	divOverlay.setStyle('display','none');
	divForm.setStyle('display','none');
}

function ajaxUploadImg(value,sToLoad) {
	sToLoad=$(sToLoad);
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.responseJS.bStateError) {
				msgErrorBox.alert('Ошибка','Возникли проблемы при загрузке изображения, попробуйте еще разок. И на всякий случай проверьте правильность URL картинки');
			} else {				
				sToLoad.insertAtCursor(req.responseJS.sText);
				hideImgUploadForm();
			}
		}
	}
	req.open(null, DIR_WEB_ROOT+'/include/ajax/uploadImg.php', true);
	req.send( { value: value } );
}